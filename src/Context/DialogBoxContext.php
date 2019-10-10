<?php
namespace froggdev\BehatContexts\Context;

use froggdev\BehatContexts\Util\DialogBoxTrait;

/**
 * Class DialogBoxContext
 * @package froggdev\BehatContexts\Context
 */
trait DialogBoxContext
{

    ##########
    # TRAITS #
    ##########

    use DialogBoxTrait;

    ###########
    # CONTEXT #
    ###########

    /**
     * @Then Je confirme
     */
    public function iClickOnTheAlertWindow(): void
    {
        //Validate an alert if exist
        try {
            $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
        } catch (\Exception $exception) {}
    }

    /**
     * @Then Je devrai voir le texte ":texte" dans le message d'alerte
     *
     * @param string $message The message.
     * @throws \Exception
     */
    public function assertPopupMessage(string $message): void
    {
        // replace uservar in param
        $expectedMessage = $this->replaceUserVar($message);

        // get alert messsage
        $message = $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text();

        // close the alert
        $this->iClickOnTheAlertWindow();

        if ($expectedMessage!==trim($message)) {
            throw new \Exception('excpected message : ' . $expectedMessage . ' but found ' . $message );
        }
    }

    /**
     * @Then Je devrai voir une alert
     */
    public function hasAlert()
    {
        return $this->hasPopupMessage();
    }

}