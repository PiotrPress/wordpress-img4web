<?php declare( strict_types = 1 );

namespace Img4Web\Vendor\PiotrPress;

interface TemplaterInterface {
    public function render( string $template, array $context = [] ) : string;
    public function display( string $template, array $context = [] ) : void;
}