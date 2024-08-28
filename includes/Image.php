<?php declare( strict_types = 1 );

namespace Img4Web;

\defined( 'ABSPATH' ) or exit;

if( ! \class_exists( __NAMESPACE__ . '\Image' ) ) {
    class Image {
        const URL = 'https://api.img4web.app';
        const INPUT_TYPES = [ 'image/jpeg', 'image/png', 'image/gif' ];
        const OUTPUT_TYPES = [ 'image/webp', 'image/avif' ];

        protected string $file;
        protected string $type;

        static public function isInputType( string $type ) : bool {
            return \in_array( \strtolower( $type ), self::INPUT_TYPES );
        }

        static public function isOutputType( string $type ) : bool {
            return \in_array( \strtolower( $type ), self::OUTPUT_TYPES );
        }

        public function __construct( string $file ) {
            if( ! self::isInputType( $this->type = \wp_get_image_mime( $this->file = $file ) ) )
                throw new \RuntimeException( 'Unsupported type: ' . $this->type );
        }

        public function getFile() : string {
            return $this->file;
        }

        public function getType() : string {
            return $this->type;
        }

        public function convert( string $file, string $type, int $quality = -1 ) : bool {
            if( ! \is_writable( $dir = \dirname( $file ) ) ) throw new \RuntimeException( 'Directory is not writable: ' . $dir );
            if( ! self::isOutputType( $type ) ) throw new \RuntimeException( 'Unsupported type: ' . $type );

            $response = \wp_remote_post( \esc_url_raw( \defined( 'CONVERTER_URL' ) ? \CONVERTER_URL : self::URL ), $this->getArgs( $file, $type, $quality ) );
            switch( true ) {
                case \is_wp_error( $response ) : @\unlink( $file ); throw new \RuntimeException( $response->get_error_message() );
                case 200 !== \wp_remote_retrieve_response_code( $response ) : @\unlink( $file ); throw new \RuntimeException( \wp_remote_retrieve_response_message( $response ) );
                case 200 === \wp_remote_retrieve_response_code( $response ) && 0 === @\filesize( $file ) : @\unlink( $file ); throw new \RuntimeException( 'Empty response' );
                default : return true;
            }
        }

        protected function getArgs( string $file, string $type, int $quality ) : array {
            return [
                'headers' => [
                    'Accept' => $type,
                    'Content-Type' => $this->type,
                    'X-Image-Quality' => (string)$quality,
                    'Authorization' => 'Basic ' . $this->getToken(),
                    'Referer' => \get_bloginfo( 'url' ),
                ],
                'user-agent' => $this->getUserAgent(),
                'timeout' => 120,
                'stream' => true,
                'filename' => $file,
                'body' => $this->getContent()
            ];
        }

        protected function getToken() : string {
            return \base64_encode( 'X-Access-Token:' . ( \get_option( 'img4web', [] )[ 'token' ] ?? '' ) );
        }

        protected function getUserAgent() : string {
            return \sprintf( 'WordPress/%s; %s/%s', \get_bloginfo( 'version' ), Plugin::getName(), Plugin::getVersion() );
        }

        protected function getContent() : string {
            if( ! \is_file( $this->file ) ) throw new \RuntimeException( 'File does not exist: ' . $this->file );
            if( ! \is_readable( $this->file ) ) throw new \RuntimeException( 'File is not readable: ' . $this->file );
            return @\file_get_contents( $this->file ) ?? '';
        }
    }
}