<?php
namespace froggdev\BehatContexts\Command;
/*
 * This file is part of the Froggdev-BeHat-Context.
 *
 * (c) Frogg <admin@frogg.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Command :
 * ---------
 * php bin/console froggdev:BehatContexts:install 
 * STYLES :
 * ------
 */
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
/**
 * @author Frogg <admin@frogg.fr>
 *
 * Symfony console
 * @see https://symfony.com/doc/current/console.html
 */
class InstallCommand extends Command
{
    /** @const int EXITCODE the normal code returned when exit the command */
    public const EXITCODE = 0;
    /** @var SymfonyStyle */
    private $output;
    /** @var Application */
    private $application;
    /** @var KernelInterface */
    private $kernel;
    /** @var ContainerInterface */
    private $container;
		
    /**
     * /!\        DO NOT USE CONTRUCTOR IN COMMANDS      /!\
     * /!\ IT WILL BE CALL ON CONSOLE LOAD WHE CONFIGURE /!\
     */
		 
    /**
     * Set the command name/description/help
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('behat:install')
            // the short description shown while running "php bin/console list"
            ->setDescription('Install & configure BeHat with lot of fonctionnalities (included Selenium).')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Nothing more than the description says !');
    }
    /**
     * Main function
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \App\Exception\Product\ProductTypeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // INIT
        $this->application = $this->getApplication();
        $this->kernel = $this->application->getKernel();
        $this->container = $this->kernel->getContainer();

        // INIT STYLES
        $this->output = new SymfonyStyle($input, $output);
				
        // DO MAIN SCRIPT
        return $this->install();
    }
    /**
     * Main script
     *
     * @return int
     */
    private function install()
    {
			$this->output->title('Welcome to the Froggdev Behat Contexts installer');
			$this->output->newLine();
			
			$this->output->note('This script will install all the stuff required to have BeHat working with Selenium.');
			
			$this->output->note('Installation:');
			$this->output->listing([
				'Add a working selenium for IE & Chrome',
				'Add new context for BeHat',
				'Add advanced working demo',
			]);
			
			$this->output->note('Functionnalities:');
			$this->output->listing([
				'Create a HTML Report once tests are done',
				'(Optionnal) Send mail once tests are done',	
				'(Optionnal) Copy the report to a specified path',	
			]);
			
			
/*
			TODO ASK FOR DEFAULT CONFIG?
*/			
/*
					const PACKAGE_PATH = 'vendor/froggdev/behat-contexts';
		
		const PACKAGE_FILES = [
			'features' => 'features',
			'behat.yml.dist' => 'behat.yml.dist'
		];
		
		const PACKAGE_MESSAGE = 'post-install.txt';
		
*/
				try{
					
					$this->fixDependencyInjection();
					
				}
				catch(\Exception $e){
					$this->output->error( $e->getMessage() );
				}

        return self::EXITCODE;
    }
		
		private function fixDependencyInjection()
		{
			$this->output->section('Updating Symfony Dependcy Injection');
			
			echo "TEST FOLDER == " . __DIR__;

			exit();

			$symfonyContainerBuilderFile = __DIR__ . '../../../../../../vendor\symfony\dependency-injection\ContainerBuilder.php';

			//read the entire string
			$fileContent=file_get_contents($symfonyContainerBuilderFile);

			//replace something in the file string - this is a VERY simple example
			$fileContent=str_replace(
				'if ( null !== $definition->getFile() )',
				'if ( null !== $definition->getFile() && \'\'!==$definition->getFile()&& getcwd().\'/\'!==$definition->getFile() )',
				$fileContent
			);

			//write the entire string
			file_put_contents($symfonyContainerBuilderFile, $fileContent);
			

			
			$this->output->success('Symfony Dependcy Injection Fix applied');
		}
		
}