<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress;

trait Singleton {
    public function __serialize() : array  {
        throw new \Exception( 'Cannot serialize Singleton.' );
    }

    public function __unserialize( array $data ) : void {
        throw new \Exception( 'Cannot unserialize Singleton.' );
    }

    private function __clone() {}

    protected function __construct() {}

    static final public function getInstance() : ?self {
        static $instance = null;

        if( $instance ) return $instance;

        $reflection = new \ReflectionClass( static::class );

        $instance = $reflection->newInstanceWithoutConstructor();

        $constructor = $reflection->getConstructor();
        $constructor->setAccessible( true );
        $constructor->invokeArgs( $instance, \func_get_args() );

        return $instance;
    }
}