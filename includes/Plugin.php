<?php declare( strict_types = 1 );

namespace Img4Web;

use Img4Web\Vendors\PiotrPress\Cacher;
use Img4Web\Vendors\PiotrPress\CacherInterface;
use Img4Web\Vendors\PiotrPress\Logger;
use Img4Web\Vendors\PiotrPress\Logger\Handler\FileHandler;
use Img4Web\Vendors\PiotrPress\Templater;
use Img4Web\Vendors\PiotrPress\TemplaterInterface;
use Img4Web\Vendors\PiotrPress\WordPress\Hooks;
use Img4Web\Vendors\PiotrPress\WordPress\Notice;
use Img4Web\Vendors\Psr\Log\LoggerInterface;
use Img4Web\Vendors\Psr\Log\LogLevel;

\defined( 'ABSPATH' ) or exit;

if( ! \class_exists( __NAMESPACE__ . '\Plugin' ) ) {
    class Plugin extends Vendors\PiotrPress\WordPress\Plugin {
        const DIR = \WP_CONTENT_DIR . '/converted';

        protected CacherInterface $cacher;
        protected LoggerInterface $logger;
        protected TemplaterInterface $templater;

        protected function __construct( string $file ) {
            parent::__construct( $file );

            $this->cacher = new Cacher( self::getDir() . '.cache', 'development' === \wp_get_environment_type() ? 0 : -1 );
            $this->logger = new Logger( new FileHandler(
                \sprintf('%s/logs/%s.%s.log', \WP_CONTENT_DIR, self::getSlug(), \date( 'Y-m-d' ) )
            ) );
            $this->templater = new Templater( self::getDir() . 'templates' );

            if( ! \wp_mkdir_p( self::DIR ) ) {
                $this->log( $message = 'Could not create the directory: ' . self::DIR );
                new Notice( $message, Notice::ERROR );
            } elseif( ! \is_writable( self::DIR ) ) {
                $this->log( $message = 'The directory is not writable: ' . self::DIR );
                new Notice( $message, Notice::ERROR );
            } else self::hook( $this );
        }

        public function log( string $message, $level = LogLevel::ERROR ) : bool {
            try {
                $this->logger->log( $level, $message );
            } catch( \Exception $e ) {
                error_log( $e->getMessage() );
                return false;
            }
            return true;
        }

        public function render( string $template, array $context = [] ) : string {
            return $this->templater->render( $template, $context );
        }

        static public function hook( object $object = null, string $callback = '' ) : void {
            Hooks::add( $object, $callback, self::getInstance()->cacher );
        }

        public function activation() : void {}
        public function deactivation() : void {}

//        #[ Filter( 'wp_update_attachment_metadata' ) ]
//        public function onUpload() : void {}
//
//        #[ Filter( 'image_make_intermediate_size' ) ]
//        public function onResize() : void {}
//
//        #[ Filter( 'wp_delete_file' ) ]
//        public function onDelete() : void {}
    }
}