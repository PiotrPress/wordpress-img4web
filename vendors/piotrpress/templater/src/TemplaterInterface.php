<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress;

interface TemplaterInterface {
    public function render( string $template, array $context = [] ) : string;
    public function display( string $template, array $context = [] ) : void;
}