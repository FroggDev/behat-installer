<?php
namespace froggdev\BehatInstaller\FeaturedContext;

trait AlertTrait
{
    ################
    # ACTION ALERT #
    ################

    /**
     * @Then Je confirme
     */
    public function iClickOnTheAlertWindow(): void
    {
        //remove alerts if exist
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

    ##################
    # FUNCTION ALERT #
    ##################

    /**
     * @return bool
     */
    public function hasPopupMessage(): bool
    {
        // if session is not started can skip the screenshots
        if( false===$this->getSession()->isStarted() ){
            return false;
        }

        // try to read a popup if exist
        try {
            $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text();
        }catch(\Exception $e){
            return false;
        }

        // all ok, mean a popup exist
        return true;
    }

}