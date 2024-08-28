<?php declare( strict_types = 1 );

namespace Img4Web;

use Img4Web\Vendor\PiotrPress\Singleton;

\defined( 'ABSPATH' ) or exit;

if( ! \class_exists( __NAMESPACE__ . '\Command' ) ) {
    class Command {
        const SUCCESS = 'Success';
        const SKIPPED = 'Skipped';
        const FAILED = 'Failed';

        use Singleton;

        protected function __construct() {
            if( \defined( 'WP_CLI' ) && \WP_CLI )
                \WP_CLI::add_command( 'convert', [ $this, 'convert' ] );
        }

        /**
         * Convert images to WEBP and/or AVIF format.
         *
         * ## OPTIONS
         *
         * [<file>...]
         * : path to the image file to convert (if not provided, all images will be converted)
         * ---
         *
         * [--type=<type>...]
         * : type of the image to convert
         * ---
         * default: webp,avif
         * options:
         *   - webp
         *   - avif
         * ---
         *
         * [--quality=<quality>]
         * : quality of the image (0-100)
         * ---
         * default: -1
         * ---
         *
         * [--overwrite]
         * : overwrite existing files
         * ---
         *
         * [--network]
         * : convert images from all sites in the network
         * ---
         *
         * [--success=<success>]
         * : show successful conversions
         * ---
         *
         * [--skipped=<skipped>]
         * : show skipped conversions
         * ---
         *
         * [--failed=<failed>]
         * : show failed conversions
         * ---
         *
         * [--stats]
         * : show conversions statistics
         * ---
         *
         * [--dry-run]
         * : do not save the files
         * ---
         *
         * ## EXAMPLES
         *
         *     wp convert
         *
         * @when after_wp_load
         */
        public function convert( $files, $args ) {
            if( ! \is_writable( Plugin::DIR ) ) {
                \WP_CLI::error( 'The directory is not writable: ' . Plugin::DIR );
                exit( 1 );
            }

            $types = \explode( ',', $args[ 'type' ] );
            $quality = $args[ 'quality' ] ?? -1;
            $overwrite = $args[ 'overwrite' ] ?? false;
            $network = $args[ 'network' ] ?? false;
            $success = $args[ 'success' ] ?? true;
            $skipped = $args[ 'skipped' ] ?? true;
            $failed = $args[ 'failed' ] ?? true;
            $stats = $args[ 'stats' ] ?? true;
            $dryRun = $args[ 'dry-run' ] ?? false;

            $results = [];
            foreach( $network ? \get_sites( [ 'fields' => 'ids' ] ) : [ \get_current_blog_id() ] as $siteId ) {
                \switch_to_blog( $siteId );
                $siteUrl = \site_url( $siteId );
                $results[ $siteUrl ] = [
                    'Site' => $siteUrl,
                    self::SUCCESS => 0,
                    self::SKIPPED => 0,
                    self::FAILED => 0
                ];

                foreach( $files ?: $this->getAttachments() as $file ) {
                    if( ! \file_exists( $file ) ) {
                        ! $failed ?: self::log( $file . ' - file does not exists', self::FAILED );
                        $results[ $siteUrl ][ self::FAILED ]++;
                        continue;
                    }

                    $path = Plugin::DIR . ( 0 === \strpos( $file, \WP_CONTENT_DIR ) ? \substr( $file, \strlen( \WP_CONTENT_DIR ) ) : $file );

                    if( ! $dryRun && ! \wp_mkdir_p( $dir = \dirname( $path ) ) ) {
                        ! $failed ?: self::log( "Could not create the directory: $dir", self::FAILED );
                        $results[ $siteUrl ][ self::FAILED ]++;
                        continue;
                    }

                    foreach( $types as $type ) {
                        if( ! $overwrite && \file_exists( "$path.$type" ) && @\filesize( "$path.$type" ) !== 0 ) {
                            ! $skipped ?: self::log( "$path.$type" . ' - file exists', self::SKIPPED );
                            $results[ $siteUrl ][ self::SKIPPED ]++;
                            continue;
                        }

                        try {
                            $image = new Image( $file );
                            if( ! $dryRun ) $image->convert( "$path.$type", "image/$type", $quality );
                            ! $success ?: self::log( "$path.$type", self::SUCCESS );
                            $results[ $siteUrl ][ self::SUCCESS ]++;
                        } catch( \Throwable $exception ) {
                            ! $failed ?: self::log( $file . ' - ' . $exception->getMessage(), self::FAILED );
                            $results[ $siteUrl ][ self::FAILED ]++;
                        }
                    }
                }
                \restore_current_blog();
            }

            ! $stats ?: self::stats( $results, $network );
        }

        protected function getAttachments() : array {
            $attachments = [];
            foreach( \get_posts( [
                'post_type' => 'attachment',
                'numberposts' => -1,
                'post_mime_type' => Image::INPUT_TYPES,
            ] ) as $attachment ) {
                $attachments[] = \get_attached_file( $attachment->ID );
                $attachments = \array_merge( $attachments, $this->getThumbnails( $attachment->ID ) );
            }

            return $attachments;
        }

        protected function getThumbnails( int $attachmentID ) : array {
            $metadata = \wp_get_attachment_metadata( $attachmentID );
            if( ! $metadata[ 'sizes' ] ?? [] ) return [];

            $thumbnails = [];
            foreach( $metadata[ 'sizes' ] as $size )
                $thumbnails[] = \wp_get_upload_dir()[ 'basedir' ] . '/' . \dirname( $metadata[ 'file' ] ) . '/' . $size[ 'file' ];

            return $thumbnails;
        }

        protected static function log( string $message, string $result ) : void {
            switch( $result ) {
                case self::SUCCESS : \WP_CLI::success( $message ); break;
                case self::SKIPPED : \WP_CLI::log( \WP_CLI::colorize( "%YSkipped:%n $message" ) ); break;
                case self::FAILED : \WP_CLI::log( \WP_CLI::colorize( "%RFailed:%n $message" ) ); break;
                default : throw new \InvalidArgumentException( "Unknown log result: $result" );
            }
        }

        protected static function stats( array $results, bool $network ) : void {
            $columns = [ 'Site', 'Total', 'Success', 'Skipped', 'Failed' ];
            foreach( $results as $site => $result )
                $results[ $site ][ 'Total' ] = \array_sum( \array_slice( $result, 1 ) );
            if( $network ) \WP_CLI\Utils\format_items( 'table', $results, $columns );

            $totals = [];
            foreach( \array_slice( $columns, 1 ) as $total )
                $totals[ $total ] = \array_sum( \array_column( $results, $total ) );
            \WP_CLI::log( \WP_CLI::colorize( "%BTotal:%n {$totals[ 'Total' ]}, %GSuccess:%n {$totals[ 'Success' ]}, %YSkipped:%n {$totals[ 'Skipped' ]}, %RFailed:%n {$totals[ 'Failed' ]}" ) );
        }
    }
}