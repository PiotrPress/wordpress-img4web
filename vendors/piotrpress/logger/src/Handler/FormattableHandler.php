<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress\Logger\Handler;

use Img4Web\Vendors\PiotrPress\Logger\Formatter\FormatterInterface;
use Img4Web\Vendors\PiotrPress\Logger\Formatter\DefaultFormatter;

abstract class FormattableHandler {
    protected FormatterInterface $formatter;

    public function __construct( ?FormatterInterface $formatter = null ) {
        $this->formatter = $formatter ?? new DefaultFormatter();
    }
}