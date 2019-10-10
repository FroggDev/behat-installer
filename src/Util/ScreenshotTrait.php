<?php
namespace froggdev\BehatContexts\Util;

use Exception;

/**
 * Trait ScreenshotTrait
 * @package froggdev\BehatContexts\Util
 */
trait ScreenshotTrait
{
    use FileTrait;

    use DialogBoxTrait;

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