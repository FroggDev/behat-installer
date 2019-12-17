<?php
namespace froggdev\BehatInstaller\Command;
/**
 * This file is part of the Froggdev-BeHat-Installer.
 *
 * (c) Frogg <admin@frogg.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Command :
 * ---------
 * php bin/console behat:install 
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
use froggdev\BehatInstaller\Config;
use froggdev\PhpUtils\FileUtil;

/**
 * @author Frogg <admin@frogg.fr>
 *
 * Symfony console
 * @see https://symfony.com/doc/current/console.html
 */
class InstallCommand extends Command
{
    /** @const int EXITCODE the normal code returned when exit the command */
    private const EXITCODE = 0;
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

      // warn about the install
      $this->output->warning('The install will overwrite default BeHat context & configuration, if you already modified thoose file you may lost your modifications');
      $this->output->confirm('Do you want to continue ?',true);
      
			// Display the message intro
			$this->messageFromFile($this->recepiesDir.'/'.Config::PACKAGE_INTRO);
				
			try{
					// Fix dependency injection
					$this->fixDependencyInjection();
					// Copy required recipies files
					$this->copyFiles();					
					// Set the user configuration
					$this->configuration();
          // Display the result message 
          $this->result();
				}
			catch(\Exception $e){
					$this->output->error( $e->getMessage() );
          
          $this->output->error( 'Installation has failed...' );
			}

			return self::EXITCODE;
    }
		
    #########
    # STEPS #
    #########
    
		private function configuration():void 
		{
				// title
				$this->setTitle(Config::PACKAGE_NAME . ' configuration');
				
				//Init vars
				$configs['exportPath']='';
				$configs['smtp'] = '';
				$configs['smtpPort'] = '';	
				$configs['mailFrom'] = '';
				$configs['mailTo'] = '';

				// Ask for user configuration
				$confirmedFull = false;
				while( $confirmedFull===false ){
				
					// Ask user confirmation for report
					$confirmed=false;
					while($confirmed===false){
						$configs['reportPath'] = $this->output->ask('Please specify the HTML report path',Config::REPORT_PATH);	
						$confirmed = $this->output->confirm('Confirm HTML report path configuration: '.$configs['reportPath'].' ?',true);
					}
          
					// Ask user confirmation screenshot for each action
					$configs['doScreenshot'] = json_encode($this->output->confirm('Take a screenshot for each action ?',true));
				
					// Ask user confirmation for export
					$confirmed=false;
					while($confirmed===false){
						$configs['doExport'] = json_encode($this->output->confirm('Move report files when tests are complete in a specified folder ?',true));
						if($configs['doExport']) $configs['exportPath'] = $this->output->ask('Please specify the export path destination',Config::EXPORT_PATH);	
						$confirmed = $configs['doExport']==='false' ? true : $this->output->confirm('Confirm export path configuration: '.$configs['exportPath'].' ?',true);
					}
					
					// Ask user confirmation for email
					$confirmed=false;
					while($confirmed===false){
						$configs['doMail'] = json_encode($this->output->confirm('Send an email once the tests are complete ?',true));
						if($configs['doMail']==='true'){
							$configs['smtp'] = $this->output->ask('Please specify the smtp server adress',Config::SMTP);
							$configs['smtpPort'] = $this->output->ask('Please specify the smtp server port',Config::SMTP_PORT);	
							$configs['mailFrom'] = $this->output->ask('Please specify mail from adress',Config::MAIL_FROM);
							$extraMail=true;
							while($extraMail===true){
								$configs['mailTo'] .= $this->output->ask('Please specify mail to adress',Config::MAIL_TO).';';
								$extraMail = $this->output->confirm('Add more recipient ?',false);
							}
						}
						$confirmed = !$configs['doMail'] ? true : $this->output->confirm('Confirm mail configuration: '.$configs['smtp'].':'.$configs['smtpPort'].' from '.$configs['mailFrom'].' to '.$configs['mailTo'].' ?',true);
					}

          // format config for output table
          $formatedConfig = [];
          foreach($configs as $key => $value) $formatedConfig[] = [$key,$value];
          
					// Full configuration 
					$this->output->newLine();
					$this->output->warning('Configuration summary');
					$this->output->table(
            ['Variable','Value'],
						$formatedConfig
          );

					// Full configuration confirmation
					$confirmedFull = $this->output->confirm('Confirm configuration ?',true);
			}

      // update the configuration			
      FileUtil::regReplaceYamlConfigFile('behat.yml.dist',$configs);
		}
		
		private function copyFiles(): void
		{
			// title
			$this->setTitle('Copying '.Config::PACKAGE_NAME .' files');
			
			$files = json_decode(file_get_contents($this->recepiesDir.'/manifest.json'), true);
			
			// Copy each files / folder
			foreach($files["copy-from-recipe"] as $fileFrom => $fileTo){
				if( $copyCommand = (is_file($fileFrom)) ){
          $this->output->writeln('copying dir <info>'. $fileFrom.'</> to <info>'.$fileTo.'</>');
					copy( $this->recepiesDir . $fileFrom , $fileTo);
        }
				else{
          $this->output->writeln('copying file <info>'. $fileFrom.'</> to <info>'.$fileTo.'</>');
					FileUtil::copyr( $this->recepiesDir . $fileFrom , $fileTo);
        }
			}			
		}		
		
		private function fixDependencyInjection(): void
		{
			// title
			$this->setTitle('Updating Symfony Dependcy Injection');

      $this->output->writeln('fixing <info>' . $this->containerBuilderPath .'</>');
      
			// read the entire string
			$fileContent=file_get_contents($this->containerBuilderPath);

			// replace something in the file string - this is a VERY simple example
			$fileContent=str_replace(
				'(null !== $definition->getFile())',
				'(null !== $definition->getFile() && \'\'!==$definition->getFile()&& strtolower(getcwd().\'/\')!==strtolower($definition->getFile()))',
				$fileContent
			);

			// write the entire string
			file_put_contents($this->containerBuilderPath, $fileContent);
		}

    private function result():void
    {
      $this->setTitle('Result');

			// result
			$this->output->success(Config::PACKAGE_NAME . ' successfully installed');
      
      $this->messageFromFile($this->recepiesDir.'/'.Config::PACKAGE_MESSAGE);      
    }

    #########
    # UTILS #
    #########
		
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
