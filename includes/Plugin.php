<?php declare( strict_types = 1 );

namespace Img4Web;

use Img4Web\Vendor\PiotrPress\Logger;
use Img4Web\Vendor\PiotrPress\Templater;
use Img4Web\Vendor\PiotrPress\TemplaterInterface;
use Img4Web\Vendor\PiotrPress\WordPress\Hooks;
use Img4Web\Vendor\PiotrPress\WordPress\Notice;
use Img4Web\Vendor\Psr\Log\LoggerInterface;
use Img4Web\Vendor\Psr\Log\LogLevel;

\defined( 'ABSPATH' ) or exit;

if( ! \class_exists( __NAMESPACE__ . '\Plugin' ) ) {
    class Plugin extends Vendor\PiotrPress\WordPress\Plugin {
        const DIR = \WP_CONTENT_DIR . '/converted';

        protected LoggerInterface $logger;
        protected TemplaterInterface $templater;

        protected function __construct( string $file ) {
            parent::__construct( $file );

            $this->logger = new Logger( \sprintf('%s/logs/%s.%s.log', \WP_CONTENT_DIR, self::getSlug(), \date( 'Y-m-d' ) ) );
            $this->templater = new Templater( self::getDir() . 'templates' );

            if( ! \wp_mkdir_p( self::DIR ) ) {
                $this->log( $message = 'Could not create the directory: ' . self::DIR );
                new Notice( $message, Notice::ERROR );
            } elseif( ! \is_writable( self::DIR ) ) {
                $this->log( $message = 'The directory is not writable: ' . self::DIR );
                new Notice( $message, Notice::ERROR );
            } else Hooks::add( $this );
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