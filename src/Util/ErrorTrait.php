<?php
namespace froggdev\BehatContexts\Util;

use froggdev\BehatContexts\Config;

trait ErrorTrait
{
    use ScreenshotTrait;

    /** @var string error array separator */
    private $errSeparator = '_@_';

    /** @var int nb not blocking error occured */
    private $nbError = 0;

    /**
     * Add an error to the list
     *
     * @param string $errMsg
     */
    private function setNotBlockingErrorOccured(string $errMsg):void
    {
        $this->takeAScreenshots(
            $this->reportPath . Config::SCREENSHOT_DIR_CUSTOM,
            'ErreurNonBloquante-'.$this->nbError.'.png'
        );


        isset( $this->userVars['notBlockingError'] ) ? $this->userVars['notBlockingError'].= $this->errSeparator . $errMsg : $this->userVars['notBlockingError'] = $errMsg;

        echo "Minor error occured : $errMsg\n";

        $this->nbError++;
    }
}