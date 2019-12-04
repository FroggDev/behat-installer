<?php
namespace froggdev\BehatContexts\FeaturedContext;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\RawMinkContext;
use froggdev\BehatContexts\Util\UserVarsTrait;

abstract class AbstractUserVarsContext extends RawMinkContext implements Context
{
    ##########
    # TRAITS #
    ##########

    use UserVarsTrait;

    ##########
    # EVENTS #
    ##########

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function setUpTestEnvironment(BeforeScenarioScope $scope): void
    {
        // read saved current user vars
        $this->getUserVars();
    }

    /**
     * @AfterScenario
     */
    public function AfterScenario(): void
    {
        // restore current user vars
        $this->setUserVars();
    }

    ###########
    # CONTEXT #
    ###########

    /**
     * @Given Je defini que ":clef" vaut ":valeur"
     *
     * @param string $key
     * @param string $value
     */
    public function setVar(string $key , string $value): void
    {
        $this->userVars[$key] = $this->replaceUserVar($value);
    }

    /**
     * @Given Je defini que ":clef" vaut la valeur du champ ":css"
     *
     * @param string $key
     * @param string $css
     */
    public function setVarFromInput(string $key , string $css): void
    {
        $this->userVars[$key] = $this
            ->getSession()
            ->getPage()
            ->find('css', $css)
            ->getAttribute('value');
    }

    /**
     * @Given Je defini que ":clef" vaut la valeur du champ ":css" en javascript
     *
     * @param string $key
     * @param string $css
     * @throws DriverException
     * @throws UnsupportedDriverActionException
     */
    public function setVarFromInputJS(string $key , string $css): void
    {
        $this->userVars[$key] = $this
            ->getSession()
            ->getDriver()
            ->evaluateScript("function(){ return document.querySelector('" . $css . "').value; }()");
    }

    /**
     * @Given J'efface les anciennes variables
     */
    public function iCleanOldVars(): void
    {
        // Suppression du fichier
        $this->removeUserVars();
    }
}
