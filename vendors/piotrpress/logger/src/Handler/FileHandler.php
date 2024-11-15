<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress\Logger\Handler;

use Img4Web\Vendors\PiotrPress\Logger\LogRecord;
use Img4Web\Vendors\PiotrPress\Logger\Formatter\FormatterInterface;
use Img4Web\Vendors\PiotrPress\Logger\Formatter\FileFormatter;

class FileHandler extends FormattableHandler implements HandlerInterface {
    private string $file;

    public function __construct( string $file, ?FormatterInterface $formatter = null  ) {
        parent::__construct( $formatter ?? new FileFormatter() );
        $this->file = $file;
    }

    public function handle( LogRecord $record ) : bool {
        return (bool)@\file_put_contents( $this->file, $this->formatter->format( $record ), FILE_APPEND );
    }
}