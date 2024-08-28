<?php declare( strict_types = 1 );

namespace PiotrPress\Composer\ClassMapper;

use Composer\Command\BaseCommand;
use Composer\Autoload\ClassMapGenerator;
use Composer\Factory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

class Command extends BaseCommand {
    protected function configure() {
        $this
            ->setName( 'map' )
            ->setDescription( 'Generates classmap file based on project files' )
            ->addOption(
                'exclude',
                'e',
                InputOption::VALUE_OPTIONAL,
                'Regex that matches files paths to be excluded from the classmap',
                null
            );
    }

    protected function execute( InputInterface $input, OutputInterface $output ) {
        $dir = $this->getApplication()->getInitialWorkingDirectory();
        $fs = new Filesystem();
        $info = '<info>%s</info>';

        $map = ClassMapGenerator::createMap( $dir, $input->getOption( 'exclude' ), $this->getIO() );
        \array_walk( $map, function( &$file ) use( $dir, $fs ) {
            $file = '/' . \trim( $fs->makePathRelative( $file, $dir ), '/' );
        } );

        $fs->dumpFile( $dir . '/classmap.php', \sprintf( '<?php return %s;', \var_export( $map, true ) ) );
        $output->writeln( \sprintf( $info, 'Classmap file created successfully' ) );

        $file = '/autoload.php';
        $fs->copy( \dirname( __DIR__ ) . '/res' . $file, $dir . $file, true );
        $output->writeln( \sprintf( $info, 'Autoload file created successfully' ) );

        return self::SUCCESS;
    }
}