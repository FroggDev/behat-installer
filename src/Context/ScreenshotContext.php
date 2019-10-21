<?php
namespace froggdev\BehatContexts\Context;


use froggdev\BehatContexts\Config;
use froggdev\BehatContexts\Util\ScreenshotTrait;

/**
 * Trait ScreenshotContext
 * @package froggdev\BehatContexts\Context
 */
trait ScreenshotContext
{


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
}