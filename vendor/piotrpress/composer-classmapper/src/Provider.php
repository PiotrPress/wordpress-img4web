<?php declare( strict_types = 1 );

namespace PiotrPress\Composer\ClassMapper;

use Composer\Plugin\Capability\CommandProvider;

class Provider implements CommandProvider {
    public function getCommands() {
        return [ new Command() ];
    }
}