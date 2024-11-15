<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress\WordPress;

use Img4Web\Vendors\PiotrPress\WordPress\Hooks\Action;
use Img4Web\Vendors\PiotrPress\Elementor\Element;

\defined( 'ABSPATH' ) or exit;

if( ! \class_exists( __NAMESPACE__ . '\Notice' ) ) {
    class Notice {
        const INFO = 'info';
        const SUCCESS = 'success';
        const WARNING = 'warning';
        const ERROR = 'error';

        private string $message;
        private string $type;
        private bool $dismissible;
        private bool $alt;

        public function __construct( string $message, ?string $type = null, bool $dismissible = false, bool $alt = false ) {
            $this->message = $message;
            $this->dismissible = $dismissible;
            $this->alt = $alt;

            if( \in_array( $type, [ null, self::SUCCESS, self::INFO, self::WARNING, self::ERROR ] ) ) $this->type = $type;
            else throw new \InvalidArgumentException( "Unknown notice type: $type" );
        }

        public function __toString() {
            return (string) new Element( 'div', [
                'class' => [
                    'notice',
                    ( $this->type ? "notice-{$this->type}" : null ),
                    ( $this->dismissible ? 'is-dismissible' : null ),
                    ( $this->alt ? 'notice-alt' : null )
                ]
            ], (string) new Element( 'p', [], $this->message ) );
        }

        #[ Action( 'admin_notices' ) ]
        public function display() {
            echo $this;
        }
    }
}