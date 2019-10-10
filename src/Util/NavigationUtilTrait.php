<?php
namespace froggdev\BehatContexts\Util;

use Exception;
use Behat\Mink\Session;

/**
 * Trait NavigationUtilTrait
 * @package froggdev\BehatContexts\Util
 */
trait NavigationUtilTrait
{
    /**
     * Check if a pup up is opened
     *
     * @return bool
     */
    public function hasPopupMessage(): bool
    {
        /** @var Session $session */
        $session = $this->getSession();

        // if session is not started can skip the screenshots
        if( false===$session->isStarted()) return false;

        // try to read a popup if exist
        try {
            $session->getDriver()->getWebDriverSession()->getAlert_text();
        }catch(Exception $e){
            return false;
        }

        // all ok, mean a popup exist
        return true;
    }
}