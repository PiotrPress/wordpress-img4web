<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress\Logger\Handler;

use Img4Web\Vendors\PiotrPress\Logger\LogRecord;

interface HandlerInterface {
    public function handle( LogRecord $record ) : bool;
}