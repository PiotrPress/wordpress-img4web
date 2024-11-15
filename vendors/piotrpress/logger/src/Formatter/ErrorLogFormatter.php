<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress\Logger\Formatter;

class ErrorLogFormatter extends TemplateFormatter {
    protected string $template = __DIR__ . '/../../tpl/error_log.php';

    public function __construct( ?string $template = null ) {
        parent::__construct( $template ?? $this->template );
    }
}