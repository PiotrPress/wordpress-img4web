<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress\Logger;

use Img4Web\Vendors\Psr\Log\InvalidArgumentException;
use Img4Web\Vendors\Psr\Log\LogLevel;

class LogRecord {
    private $level;
    private $message;
    private $context;

    public function __construct( string $level, string $message, array $context = [] ) {
        if( ! self::isLevel( $level ) )
            throw new InvalidArgumentException( 'Unknown log level: ' . $level );

        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
    }

    static public function isLevel( $level ) : bool {
        $class = LogLevel::class;
        $const = \strtoupper( $level );
        return \defined( "{$class}::{$const}" );
    }

    public function getLevel() : string {
        return $this->level;
    }

    public function getMessage() : string {
        return $this->message;
    }

    public function getContext() : array {
        return $this->context;
    }
}