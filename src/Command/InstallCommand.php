<?php
namespace App\Command;
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
use froggdev\BehatContexts\Config;

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
		/** @var int */
		private $currentStep;
		/** @var string */
		private $recepiesDir;
		/** @var string */
		private $containerBuilderPath;
		
		use \froggdev\BehatContexts\Util\FileTrait;
		
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
				// set recipies dir to get files
				$this->recepiesDir = getcwd() . '/' . Config::PACKAGE_PATH . '/recipes/';
				// set the symfony container builder full path
				$this->containerBuilderPath =  getcwd() . '\vendor\symfony\dependency-injection\ContainerBuilder.php';

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


			// Display the message intro
			$this->messageFromFile($this->recepiesDir.'/'.Config::PACKAGE_INTRO);
				
			try{					

					/*
					// Fix dependency injection
					$this->fixDependencyInjection();
					// Copy required recipies files
					$this->copyFiles();					
					*/
					// Set the user configuration
					//$this->configuration();

				}
			catch(\Exception $e){
					$this->output->error( $e->getMessage() );
			}

			// Display the message finish
			$this->messageFromFile($this->recepiesDir.'/'.Config::PACKAGE_MESSAGE);

			return self::EXITCODE;
    }
		
		
		private function configuration():void 
		{
				// title
				$this->setTitle(Config::PACKAGE_NAME . ' configuration');
				
				//Init vars
				$exportPath='';
				$smtp = '';
				$smtpPort = '';	
				$mailFrom = '';
				$mailTo = '';
				
				// Ask for user configuration
				$confirmedFull = false;
				while( $confirmedFull===false ){
				
					// Ask user confirmation screenshot for each action
					$doScreenshot = $this->output->confirm('Take a screenshot for each action ?',true);
				
					// Ask user confirmation for export
					$confirmed=false;
					while($confirmed===false){
						$doExport = $this->output->confirm('Move report files when tests are complete in a specified folder ?',true);
						if($doExport) $exportPath = $this->output->ask('Please specify the export path destination');	
						$confirmed = !$doExport ? true : $this->output->confirm('Confirm export path configuration: '.$exportPath.' ?',true);
					}
					
					// Ask user confirmation for email
					$confirmed=false;
					while($confirmed===false){
						$doMail = $this->output->confirm('Send an email once the tests are complete ?',true);
						if($doMail){
							$smtp = $this->output->ask('Please specify the smtp server adress');
							$smtpPort = $this->output->ask('Please specify the smtp server port',Config::SMTP_PORT);	
							$mailFrom = $this->output->ask('Please specify mail from adress');
							$extraMail=true;
							while($extraMail===true){
								$mailTo .= $this->output->ask('Please specify mail from adress').';';
								$extraMail = $this->output->confirm('Add more recipient ?',false);
							}
						}
						$confirmed = !$doMail ? true : $this->output->confirm('Confirm mail configuration: '.$smtp.':'.$smtpPort.' from '.$mailFrom.' to '.$mailTo.' ?',true);
					}

					// Full configuration 
					$this->output->newLine();
					$this->output->warning('Configuration summary');
					$this->output->table(
						['Variable','Value'],
						[
							['doScreenshot' , json_encode($doScreenshot)],
							['doExport: ' , json_encode($doExport)],
							['exportPath: ' , $exportPath],	
							['doMail: ' , json_encode($doMail)],
							['smtp: ' , $smtp],
							['smtpPort: ' , $smtpPort],
							['mailFrom: ' , $mailFrom],
							['mailTo: ' , $mailTo],
						]
					);

					// Full configuration confirmation
					$confirmedFull = $this->output->confirm('Confirm configuration ?',true);
			}

			$this->output->warning('TODO : update configuration file');
			
			// result
			$this->output->success(Config::PACKAGE_NAME . ' successfully configurated');
		}
		
		private function copyFiles(): void
		{
			// title
			$this->setTitle('Copying '.Config::PACKAGE_NAME .' files');
			
			// Copy each files / folder
			foreach(Config::PACKAGE_FILES as $fileFrom => $fileTo){
				if( $copyCommand = (is_file($this->recepiesDir . $fileFrom)) )
					copy( $this->recepiesDir . $fileFrom , $fileTo);
				else
					$this->copyr( $this->recepiesDir . $fileFrom , $fileTo);
			}			
			
			// result
			$this->output->success(Config::PACKAGE_NAME . ' files copied');
		}
		
		
		private function fixDependencyInjection(): void
		{
			// title
			$this->setTitle('Updating Symfony Dependcy Injection');

			// read the entire string
			$fileContent=file_get_contents($this->containerBuilderPath);

			// replace something in the file string - this is a VERY simple example
			$fileContent=str_replace(
				'(null !== $definition->getFile())',
				'(null !== $definition->getFile() && \'\'!==$definition->getFile()&& getcwd().\'/\'!==$definition->getFile())',
				$fileContent
			);

			// write the entire string
			file_put_contents($this->containerBuilderPath, $fileContent);

			// result
			$this->output->success('Symfony Dependcy Injection Fix applied');
		}
		
		private function setTitle(string $title)
		{
			$this->currentStep++;
			
			$this->output->section( 'Step ' . $this->currentStep . '] ' . $title);			
		}
		
		private function messageFromFile(string $file)
		{
			
			$this->output->writeln(file_get_contents($file));
		}

}