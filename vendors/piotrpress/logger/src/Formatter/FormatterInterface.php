<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress\Logger\Formatter;

use Img4Web\Vendors\PiotrPress\Logger\LogRecord;

interface FormatterInterface {
    public function format( LogRecord $record ) : string;
}