<?php declare( strict_types = 1 );

namespace Img4Web\Vendor\PiotrPress;

class Cacher implements CacheInterface {
    protected array $data = [];

    public function __construct(
        protected string $file
    ) {
        $this->load();
    }

    public function get( string $key, callable $callback, mixed ...$args ) : mixed {
        if ( isset( $this->data[ $key ] ) ) return $this->data[ $key ];

        $this->data[ $key ] = \call_user_func( $callback, ...$args );
        $this->save();

        return $this->data[ $key ];
    }

    public function clear( string $key = null ) : bool {
        if ( $key ) unset( $this->data[ $key ] );
        else $this->data = [];

        return $this->save();
    }

    protected function load() : void {
        $this->data = \is_file( $this->file ) ? @\unserialize( @\file_get_contents( $this->file ) ) : [];
    }

    protected function save() : bool {
        return (bool)@\file_put_contents( $this->file, @\serialize( $this->data ) );
    }
}