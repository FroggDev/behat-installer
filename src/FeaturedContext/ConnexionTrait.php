<?php
namespace froggdev\BehatContexts\FeaturedContext;

use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;

trait ConnexionTrait
{

    ################
    # ACTION LOGIN #
    ################

    /**
     * @Given /^Je me connecte(?: à "([^"]*)" avec l'utilisateur "([^"]*)" et le mot de passe "([^"]*)")?$/
     *
     * @param string|null $server
     * @param string|null $user
     * @param string|null $password
     */
    public function iLogin(?string $server = null , ?string $user = null , ?string $password  = null ): void
    {
        //$this->getSession()->start();
        //$this->visit('https://www.google.com');
        //$this->iWaitSec(2);

        // go to login page
        $this->iGoOn($this->userVars['loginPage']);

        $this->iWaitSec(2);

        // do login mechanism
        $this->doLogin($user,$password);
    }

    /**
     * @Given /^Je me reconnecte(?: avec l'utilisateur "([^"]*)" et le mot de passe "([^"]*)")?$/
     *
     * @param string|null $user
     * @param string|null $password
     */
    public function iReLogin( ?string $user = null , ?string $password  = null ): void
    {
        // do login mechanism
        $this->doLogin($user,$password);
    }


    /**
     * @When Je me déconnecte
     */
    public function iLogOut(): void
    {
        // back to the main frame
        $this->switchMainFrame();

        // click on logout button
        $this->iClickSelector( $this->userVars['BUTTONLOGOUT'] );
    }


    /**
     * test if login inputs exist
     */
    private function testLoginInputs()
    {
        // get mink session
        $session = $this->assertSession();

        // test exist login
        $session->elementsCount( 'css', $this->userVars['INPUTLOGIN'],1);

        // test exist password
        $session->elementsCount( 'css', $this->userVars['INPUTPASS'],1);

        // test exist submit
        $session->elementsCount('css', $this->userVars['BUTTONLOGIN'],1);

        /** @var \Behat\Mink\Session $session */
        $session = $this->getSession();
        // get mink session
        $page = $session->getPage();

        // test exist login
        $element = $page->find( 'css', $this->userVars['INPUTLOGIN']);
        if(!$element) throw new \Exception("Cannot login : input ".$this->userVars['INPUTLOGIN']." not found");

        // test exist password
        $element = $page->find( 'css', $this->userVars['INPUTPASS']);
        if(!$element) throw new \Exception("Cannot login : input ".$this->userVars['INPUTPASS']." not found");

        // test exist submit
        $element = $page->find('css', $this->userVars['BUTTONLOGIN']);
        if(!$element) throw new \Exception("Cannot login : input ".$this->userVars['BUTTONLOGIN']." not found");

    }

    /**
     * Do the login mechanism
     * @param string|null $user
     * @param string|null $password
     */
    private function doLogin(?string $user, ?string $password)
    {
        // replace uservar in param
        $user = $this->replaceUserVar($user)??$this->userVars['user'];

        // replace uservar in param
        $password = $this->replaceUserVar($password)??$this->userVars['password'];

        // close the alert if exist
        $this->iClickOnTheAlertWindow();

        // test if login inputs exist
        $this->testLoginInputs();

        // get current session
        /** @var Session $session */
        $session = $this->getSession();

        // get page content
        /** @var DocumentElement $loginPage */
        $loginPage = $session->getPage();

        // set login
        $loginPage->find( 'css', $this->userVars['INPUTLOGIN'])->setValue( $user );

        // set password
        $loginPage->find( 'css', $this->userVars['INPUTPASS'])->setValue($password );

        // submit form
        $loginPage->find('css', $this->userVars['BUTTONLOGIN'])->press();
    }

}

















