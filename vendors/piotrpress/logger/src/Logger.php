<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress;

use Img4Web\Vendors\Psr\Log\AbstractLogger;
use Img4Web\Vendors\PiotrPress\Logger\LogRecord;
use Img4Web\Vendors\PiotrPress\Logger\Handler\HandlerInterface;

class Logger extends AbstractLogger {
    private array $handlers = [];

    public function __construct( HandlerInterface ...$handler ) {
        \array_map( [ $this, 'addHandler' ], $handler );
    }

    public function addHandler( HandlerInterface $handler ) : void {
        if( ! \in_array( $handler, $this->handlers, true ) )
            $this->handlers[] = $handler;
    }

    public function removeHandler( HandlerInterface $handler ) : void {
        if( false !== ( $key = \array_search( $handler, $this->handlers, true ) ) )
            unset( $this->handlers[ $key ] );
    }

    public function log( $level, $message, array $context = [] ) : void {
        $logRecord = new LogRecord( $level, $message, $context );
        foreach( $this->handlers as $handler )
            $handler->handle( $logRecord );
    }
}