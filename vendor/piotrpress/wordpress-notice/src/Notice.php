<?php declare( strict_types = 1 );

namespace Img4Web\Vendor\PiotrPress\WordPress;

\defined( 'ABSPATH' ) or exit;

if( ! \class_exists( __NAMESPACE__ . '\Notice' ) ) {
    class Notice {
        const INFO = 'info';
        const WARNING = 'warning';
        const ERROR = 'error';
        const SUCCESS = 'success';

        private string $message;
        private string $type;
        private bool $is_dismissible;

        public function __construct( string $message, ?string $type = null, bool $is_dismissible = true ) {
            $this->message = $message;
            $this->is_dismissible = $is_dismissible;

            if( \in_array( $type, [ null, self::INFO, self::WARNING, self::ERROR, self::SUCCESS ] ) ) $this->type = $type;
            else throw new \InvalidArgumentException( "Unknown notice type: $type" );

            \add_action( 'admin_notices', [ $this, 'display' ] );
        }

        public function display() {
            echo '<div class="notice' . ( $this->type ? ' notice-' . $this->type : '' ) . ( $this->is_dismissible ? ' is-dismissible' : '' ) . '">';
            echo '<p>' . $this->message . '</p>';
            echo '</div>';
        }

    }
}