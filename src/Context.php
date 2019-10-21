<?php
namespace froggdev\BehatContexts;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\ScenarioInterface as Scenario;
use froggdev\BehatContexts\Config;
use froggdev\BehatContexts\Context\Mailbox\MailinatorContext;
use froggdev\BehatContexts\Context\Mailbox\TemprContext;
use froggdev\BehatContexts\Context\Mailbox\YopmailContext;
use froggdev\BehatContexts\Util\DialogBoxTrait;
use froggdev\BehatContexts\Util\ErrorTrait;
use froggdev\BehatContexts\Util\FileTrait;
use froggdev\BehatContexts\Util\ScreenshotTrait;
use froggdev\BehatContexts\Context\AbstractUserVarContext;
use froggdev\BehatContexts\Context\DialogBoxContext;
use froggdev\BehatContexts\Context\WindowsContext;
use froggdev\BehatContexts\Context\SessionContext;
use froggdev\BehatContexts\Context\NavigationContext;
use froggdev\BehatContexts\Context\FormContext;
use froggdev\BehatContexts\Context\DisplayContext;
use froggdev\BehatContexts\Context\ReportContext;
use froggdev\BehatContexts\Context\ErrorContext;
use froggdev\BehatContexts\Context\ScreenshotContext;
		
/**
 * Class Context
 * @package froggdev\BehatContexts\Context
 */
class Context extends AbstractUserVarContext
{
    ##########
    # TRAITS #
    ##########

    use DialogBoxTrait;

    use ErrorTrait;

    use FileTrait;

    use ScreenshotTrait;

    ###########
    # CONTEXT #
    ###########

    // BROWSER

    use DialogBoxContext;

    use WindowsContext;

    use SessionContext;

    use NavigationContext;

    use FormContext;

    use DisplayContext;

    // UTILS

    use ReportContext;

    use ErrorContext;

    use ScreenshotContext;

    // MAIL BOXES

    use YopmailContext;

    use TemprContext;

    use MailinatorContext;

    #########
    # VARS #
    ########

    /** @var int incremental to identify each screenshots name in auto screenshots in step */
    private $idScreenshots = 0;

    /** @var bool if the auto screenshot are enabled */
    private $doScreenshot;

    /** @var string where the report will be saved */
    private $reportPath;

    /** @var Scenario */
    private $currentScenario;

    ###############
    # CONSTRUCTOR #
    ###############

    /**
     * Context constructor
     * init this screenshotPath & doScreenshot
     */
    public function __construct(?string $params=null)
    {

				var_dump("PRAMS = " . $params);

        $currentPath = $this->setTrailingSlash(getcwd());

        $this->reportPath= $currentPath .$this->setTrailingSlash(Config::REPORT_DIR);
        $this->doScreenshot = true;

        
        $this->screenshotPath = "d:\\";//$this->setTrailingSlash(getcwd()) . $this->setTrailingSlash($screenshotDir);

				//$this->doScreenshot = $doScreenshot;

    }

    #########
    # EVENT #
    #########

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function setUpTestEnvironment(BeforeScenarioScope $scope): void
    {
        $this->currentScenario = $scope->getScenario();
    }

    /**
     * Auto take a screenshot after each step
     *
     * @AfterStep
     *
     * @param $scope
     */
    public function afterStep(AfterStepScope $scope): void
    {
        // check if screenshots are disabled, if disabled nothing to do
        if( !$this->doScreenshot )  return;

        // Init the screenshots titles
        $screenshotsFeature = $scope->getFeature()->getTitle();
        $screenshotsTitle   = $this->currentScenario->getTitle() ;

        //@ TODO LANG
        // Test if names are defined to continue
        if(!$screenshotsFeature) exit('\n\nErreur : Impossible de faire des screenshots ! Veuillez définir un nom à votre Feature pour pouvoir prendre des screenshots\n\n');
        if(!$screenshotsTitle) exit('\n\nErreur : Impossible de faire des screenshots ! Veuillez définir un nom à votre Scenario pour pouvoir prendre des screenshots\n\n');

        // Set screenshot ko path
        $screenshotsPath    = Config::SCREENSHOT_DIR_KO;

        // if OK use screenshot ok path + add idScreenshot in the name
        if ($scope->getTestResult()->isPassed()){
            $this->idScreenshots++;
            $screenshotsPath  =  Config::SCREENSHOT_DIR_OK;
            $screenshotsTitle.= '-' . $this->idScreenshots;
        }

        // take the screenshots
        $this->takeAScreenshots(
            $this->reportPath
                .$this->setTrailingSlash($screenshotsPath)
                .$this->setTrailingSlash($screenshotsFeature),
            $screenshotsTitle
        );
    }
}

