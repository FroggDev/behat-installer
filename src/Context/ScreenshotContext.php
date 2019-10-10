<?php
namespace froggdev\BehatContexts\Context;

use Exception;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\ScenarioInterface as Scenario;
use Behat\MinkExtension\Context\MinkContext;
use froggdev\BehatContexts\Config;
use froggdev\BehatContexts\Util\FileUtilTrait;
use froggdev\BehatContexts\Util\NavigationUtilTrait;

/**
 * Class ScreenshotContext
 * @package froggdev\BehatContexts\Context
 */
class ScreenshotContext extends MinkContext implements Context
{

    ##########
    # TRAITS #
    ##########

    use FileUtilTrait;

    use NavigationUtilTrait;

    #########
    # VARS #
    ########

    /** @var int incremental to identify each screenshots name in auto screenshots in step */
    private $idScreenshots = 0;

    /** @var bool if the auto screenshot are enabled */
    private $doScreenshot;

    /** @var string where the screenshots whill be saved */
    private $screenshotPath;

    /** @var Scenario */
    private $currentScenario;

    ###############
    # CONSTRUCTOR #
    ###############

    /**
     * ScreenshotContext constructor
     * init this screenshotPath & doScreenshot
     *
     * @param string $screenshotDir
     * @param bool $doScreenshot
     */
    public function __construct(string $screenshotDir=Config::SCREENSHOT_MAIN_DIR_DEFAULT, bool $doScreenshot=true)
    {
        $this->screenshotPath = $this->setTrailingSlash(getcwd()) .'../'. $this->setTrailingSlash($screenshotDir);

        echo 'saving screenshots to [' . $this->screenshotPath .']<br>\n';

		$this->doScreenshot = $doScreenshot;
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
        if(!$screenshotsFeature) exit('Impossible de faire des screenshots ! Veuillez définir un nom à votre Feature pour pouvoir prendre des screenshots');
        if(!$screenshotsTitle) exit('Impossible de faire des screenshots ! Veuillez définir un nom à votre Scenario pour pouvoir prendre des screenshots');

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
            $this->screenshotPath
                .$this->setTrailingSlash($screenshotsPath)
                .$this->setTrailingSlash($screenshotsFeature),
            $screenshotsTitle
        );
    }

    ###########
    # CONTEXT #
    ###########

    /**
     * @Then Je désactive les captures d'écran automatiques
     */
    public function iDisableScreenShots(): void
    {
        $this->doScreenshot=false;
    }

    /**
     * @Given Je prends une capture d'écran ":nom_du_fichier"
     *
     * @param string $filename
     */
    public function iTakeAScreenShot(string $filename): void
    {
        $this->takeAScreenshots(
            $this->screenshotPath . Config::SCREENSHOT_DIR_CUSTOM,
            $filename
        );
    }

    /**
     * @Then J'efface les anciennes captures d'écran
     */
    public function iDeleteOldScreenShots(): void
    {
        $this->delTree($this->screenshotPath . Config::SCREENSHOT_DIR_OK);

        $this->delTree($this->screenshotPath . Config::SCREENSHOT_DIR_KO);
    }

    ###########
    # METHOD  #
    ###########

    /**
     * @param string $path
     * @param string $fileName
     */
    private function takeAScreenshots(string $path, string $fileName): void
    {
        // if session is not started can skip the screenshots
        if( false===$this->getSession()->isStarted() ) return;

        // if driver is not loaded then skip the screenshots
        if (null===$this->getSession()->getDriver()) return;

        //test if alert is opened
        if ($this->hasPopupMessage()) return;

        // Test if can take a screenshots
        try{
            $screenshots = $this->getSession()->getDriver()->getScreenshot();
        }
        catch(Exception $e){
            return;
        }

        // format names to prevent special char toubles
        $fileName = preg_replace('/\W/', '', $fileName);

        // save the screenshots and save as the previously defined filename
        $this->writeTofile(
            $path . $fileName . '.png',
            $screenshots
        );
    }
}
