<?php declare( strict_types = 1 );

namespace PiotrPress\Composer\ClassMapper;

use Composer\Plugin\PluginInterface;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;

class Plugin implements PluginInterface, Capable {
    public function activate( Composer $composer, IOInterface $io ) {}
    public function deactivate( Composer $composer, IOInterface $io ) {}
    public function uninstall( Composer $composer, IOInterface $io ) {}

    public function getCapabilities() {
        return [ 'Composer\Plugin\Capability\CommandProvider' => __NAMESPACE__ . '\Provider' ];
    }
}