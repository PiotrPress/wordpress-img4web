<?php declare(strict_types=1);

namespace Img4Web\Vendor\PiotrPress;

use const FILE_APPEND;

use RuntimeException;

use Img4Web\Vendor\Psr\Log\AbstractLogger;
use Img4Web\Vendor\Psr\Log\LogLevel;
use Img4Web\Vendor\Psr\Log\InvalidArgumentException;

use function array_merge;
use function strtoupper;
use function defined;
use function date;
use function is_array;
use function is_object;
use function method_exists;
use function file_put_contents;
use function strtr;

class Logger extends AbstractLogger {
    protected string $file = '';
    protected array $context = [ 'format' => "[{date}] [{level}] {message}\n" ];

    public function __construct( string $file, array $context = [] ) {
        $this->file = $file;
        $this->context = array_merge( $this->context, $context );
    }

    public function log( $level, $message, array $context = [] ) : void {
        if ( ! $this->isLogLevel( $level ) )
            throw new InvalidArgumentException( "Unknown log level: \"{$level}\"." );

        if ( false === file_put_contents( $this->file, $this->formatMessage( $level, $message, $context ), FILE_APPEND ) )
            throw new RuntimeException( "Unable to write to file: \"{$this->file}\"." );
    }

    protected function isLogLevel( $level ) : bool {
        $class = LogLevel::class;
        $const = strtoupper( $level );

        return defined( "{$class}::{$const}" );
    }

    protected function formatMessage( $level, $message, array $context = [] ) : string {
        $replace = [];
        foreach ( $context = array_merge(
            $this->context,
            [ 'date' => date( 'Y-m-d G:i:s' ) ],
            $context,
            [ 'level' => $level, 'message' => $message ] ) as $key => $value )
            if ( ! is_array( $value ) && ( ! is_object( $value ) || method_exists( $value, '__toString' ) ) )
                $replace[ "{{$key}}" ] = $value;

        return strtr( $context[ 'format' ], $replace );
    }
}