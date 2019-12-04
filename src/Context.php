<?php
namespace froggdev\BehatContexts;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\ScenarioInterface as Scenario;
use froggdev\BehatContexts\Config;
use froggdev\BehatContexts\FeaturedContext\AbstractUserVarsContext;
		
/**
 * Class Context
 * @package Context
 */
class Context extends AbstractUserVarsContext
{
    /** @Trait Debug */
    use FeaturedContext\DebugTrait;
		
    #########
    # UTILS #
    #########
    use Util\ErrorTrait;
    use Util\FileTrait;
    use Util\DateTrait;
		use Util\StringTrait;
		use Util\MathTrait;
		use Util\UtilTrait;
		
    #############
    # MAILBOXES #
    #############
    use FeaturedContext\Mailbox\YopmailTrait;
    use FeaturedContext\Mailbox\MailinatorTrait;
    use FeaturedContext\Mailbox\TemprTrait;

    #############
    # CONTEXTES #
    #############
    use FeaturedContext\ConnexionTrait;
    use FeaturedContext\NavigationTrait;
    use FeaturedContext\ScreenshotsTrait;
    use FeaturedContext\SessionTrait;
    use FeaturedContext\AlertTrait;
    use FeaturedContext\FormTrait;
    use FeaturedContext\WindowTrait;
    use FeaturedContext\DisplayTrait;
    use FeaturedContext\ResponseTrait;
    use FeaturedContext\RepportTrait;

    #################
    # MUI CONTEXTES #
    #################
    use FeaturedContext\Mui\NavigationTrait;
    use FeaturedContext\Mui\FormTrait;
    use FeaturedContext\Mui\SpecificTrait;

    #########
    # VARS #
    ########

    /** @var Scenario */
    private $currentScenario;

    /** @var int incremental to identify each screenshots name in auto screenshots in step */
    private $idScreenshots = 0;

    /** @var bool if the auto screenshot are enabled */
    private $doScreenshot;

    /** @var string where the report will be saved */
    private $reportPath;

		//TODO COMMENTS
		private $exportPath;
		

    private $doMail;

		private $mailFrom;
		
		private $mailTo;

		private $smtp;

		private $smtpPort;

		private $hasError=false;

    ###############
    # CONSTRUCTOR #
    ###############

    public function __construct(?array $param0=null,?array $param1=null,?array $param2=null,?array $param3=null,?array $param4=null,?array $param5=null,?array $param6=null,?array $param7=null)
    {
			// Convert arguments to one array params
			$params=[];
			foreach(func_get_args() as $args){
				if($args){
					foreach($args as $key => $arg){
						$params[$key] = $arg;
					}
				}
			}
			
			/*
			echo "=========>";
			var_dump($params);
			echo "<=========";
			*/

			// Set values from params
			$this->doScreenshot = $params['doScreenshot']??Config::DO_SCREENSHOT;
			
			$this->reportPath = $params['reportPath']??Config::REPORT_PATH;
			
			$this->exportPath = $params['exportPath']??Config::EXPORT_PATH;
			
			$this->doMail =  $params['doMail']??Config::DO_MAIL;

			$this->mailFrom = $params['mailFrom']??Config::MAIL_FROM;
			
			$this->mailTo = $params['mailTo']??Config::MAIL_TO;

			$this->smtp =  $params['smtp']??Config::SMTP;

			$this->smtpPort = $params['smtpPort']??Config::SMTP_PORT;
    }

    /**
     * FeatureFeaturedContext destructor.
     * Save the report (screenshots + html result) to test path
     * @throws Exception
     */
    function __destruct() 
		{
        // read saved current user vars
        $this->getUserVars();

        // Prevent multiple report for each desctruct (each scenariis)
        if(!$this->userVars['reportHasBeenDone']){
            // Copy the report
            $this->ISaveTheRepport();
        }

        // set report has been done
        $this->userVars['reportHasBeenDone'] = true;

        // Store user vars
        $this->setUserVars();
    }

    ###################
    # SCENARIO EVENTS #
    ###################

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function setUpTestEnvironment(BeforeScenarioScope $scope): void
    {
				// Get current scenario for screenshots
        $this->currentScenario = $scope->getScenario();
				
        // read saved current user vars
        $this->getUserVars();

        // set report hasnt been done
        $this->userVars['reportHasBeenDone'] = false;
    }

    /**
     * @AfterScenario
     */
    public function AfterScenario(): void
    {
        // restore current user vars
        $this->setUserVars();
    }

    /**
     * @AfterStep
     *
     * @param $scope
     */
    public function afterStep($scope): void
    {
        // only if user vars has been loaded
        if($this->doScreenshot) {

            // check if screenshots are disabled
            if( isset($this->userVars['disableScreenshots']) && true===$this->userVars['disableScreenshots']){
                return;
            }

            // Set screenshot ko values
            $screenshotsPath    = Config::SCREENSHOT_DIR_KO;
						
						// Get current feature name
            $screenshotsFeature = $scope->getFeature()->getTitle();
						
						// Get current scenario step name
            $screenshotsTitle   = $this->currentScenario->getTitle() ;

            // Test if names are defined to continue
            if(!$screenshotsFeature) exit('Impossible de faire des screenshots ! Veuillez définir un nom à votre Feature pour pouvoir prendre des screenshots');
            if(!$screenshotsTitle) exit('Impossible de faire des screenshots ! Veuillez définir un nom à votre Scenario pour pouvoir prendre des screenshots');

            // Set screenshot ok values
            if ($scope->getTestResult()->isPassed()){
                $this->idScreenshots++;
                $screenshotsPath  = Config::SCREENSHOT_DIR_OK;
                $screenshotsTitle.= '-' . $this->idScreenshots;
            }
						else{
							$this->hasError = true;
						}

            // take the screenshots
            $this->takeAScreenshots(
								$this->reportPath .'/'. $screenshotsPath .'/'. $screenshotsFeature,
								'',
                $screenshotsTitle
            );

        }
    }
}

