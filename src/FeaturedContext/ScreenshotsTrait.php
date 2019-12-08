<?php
namespace froggdev\BehatInstaller\FeaturedContext;

use froggdev\BehatInstaller\Config;

trait ScreenshotsTrait
{

    #####################
    # ACTION SCREENSHOT #
    #####################

    // TODO : Fusionner les deux mais ca n a pas marché ???

    /**
     * @Given Je prends une capture d'écran ":nom_du_fichier"
     *
     * @param string $filename
     */
    public function iTakeAScreenShot(string $filename): void
    {
        $this->takeAScreenshots(
            $this->reportPath . '/' . Config::SCREENSHOT_DIR_OK,
            '_custom',
            $filename
        );
    }

    /**
     * @Given Je prends une capture d'écran ":nom_du_fichier" après :sec secondes
     *
     * @param string $filename
     *
     * @param string $sec
     */
    public function iTakeAScreenShotWait(string $filename,$sec): void
    {
        if(null!=$sec){
            $this->iWaitSec($sec);
        }

        $this->takeAScreenshots(
            $this->reportPath . '/' . Config::SCREENSHOT_DIR_OK,
            '_custom',
            $filename
        );
    }

    /**
     * @Then J'efface les anciennes captures d'écran
     */
    public function iDeleteOldScreenShots(): void
    {
        $this->delTree($this->userVars['screenshots']);
        $this->delTree($this->userVars['screenshotsErr']);
    }

    /**
     * @Then Je désactive les captures d'écran automatiques
     */
    public function iDisableScreenShots(): void
    {
        $this->userVars['disableScreenshots']=true;
    }

    #######################
    # FUNCTION SCREENSHOT #
    #######################

    /**
     * @param string $path
     * @param string $folder
     * @param string $fileName
     */
    private function takeAScreenshots(string $path, string $folder, string $fileName): void
    {
        // if session is not started can skip the screenshots
        if( false===$this->getSession()->isStarted() ){
            return;
        }

        // if driver is not loaded then skip the screenshots
        if (null===$this->getSession()->getDriver()) {
            return;
        }

        //test if alert is opened
        if ($this->hasPopupMessage()){
            return;
        }

        // Test if can take a screenshots
        try{
            $screenshots = $this->getSession()->getDriver()->getScreenshot();
        }
        catch(\Exception $e){
            return;
        }

        // format names
        $folder = preg_replace('/\W/', '', $folder);
        $fileName = preg_replace('/\W/', '', $fileName);

        // save the screenshots and save as the previously defined filename
        $this->writeTofile(
            $path . '/' . $folder . '/' . $fileName . '.png',
            $screenshots
        );
    }

}