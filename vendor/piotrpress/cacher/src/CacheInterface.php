<?php declare( strict_types = 1 );

namespace Img4Web\Vendor\PiotrPress;

interface CacheInterface {
    public function get( string $key, callable $callback, mixed ...$args ) : mixed;
    public function clear( string $key = null ) : bool;
}