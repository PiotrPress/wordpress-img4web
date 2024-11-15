<?php declare( strict_types = 1 );

namespace Img4Web\Vendors\PiotrPress;

class Cacher implements CacherInterface {
    private string $file;
    private int $expire = -1;
    private array $data = [];

    public function __construct( string $file, int $expire = -1 ) {
        $this->file = $file;
        $this->expire = $expire;

        if( $this->expired() ) $this->clear();
        else $this->load();
    }

    public function get( string $key, callable $callback, ...$args ) {
        if( isset( $this->data[ $key ] ) ) return $this->data[ $key ];

        $this->data[ $key ] = \call_user_func( $callback, ...$args );
        $this->save();

        return $this->data[ $key ];
    }

    public function expired() : bool {
        switch( $this->expire ) {
            case -1 : return false;
            case  0 : return true;
            default : return \filemtime( $this->file ) + $this->expire < \time();
        }
    }

    public function clear( string $key = null ) : bool {
        if( $key ) unset( $this->data[ $key ] );
        else $this->data = [];

        return $this->save();
    }

    protected function load() : void {
        $this->data = \is_file( $this->file ) ? @\unserialize( @\file_get_contents( $this->file ) ) ?: [] : [];
    }

    protected function save() : bool {
        return (bool)@\file_put_contents( $this->file, @\serialize( $this->data ) );
    }
}