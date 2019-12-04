<?php
namespace froggdev\BehatContexts\FeaturedContext;

trait NavigationTrait
{

    #####################
    # ACTION NAVIGATION #
    #####################

    /**
     * Require a sesssion
     * @When J'attend :duration secondes
     *
     * @param $duration
     */
    public function iWaitSec($duration): void
    {
        $this->getSession()->wait($duration * 1000);
    }

    /**
     * Require a sesssion
     * @When J'attend :duration millisecondes
     *
     * @param $duration
     */
    public function iWaitMilliSec($duration): void
    {
        $this->getSession()->wait($duration );
    }

    /**
     * Can be run without session
     * @When Je met le programme en pause pendant :duration secondes
     *
     * @param $duration
     */
    public function iPauseForSec($duration): void
    {
        sleep($duration );
    }

    /**
     * @When Je vais sur la page ":url_de_la_page"
     *
     * @param string $uri
     */
    public function iGoOn(string $uri): void
    {
        // replace uservar in param
        $uri = $this->replaceUserVar($uri);

        // check if start with http else add server to uri
        $this->visit( substr( $uri, 0, 4 ) === "http" ? $uri : $this->userVars['server'] . $uri );
    }

    /**
     * @When Je devrai être sur la page ":url_de_la_page"
     *
     * @param string $url
     * @throws \Exception
     */
    public function iShouldBeOnPage(string $url): void
    {
        // replace uservar in param
        $exprectedUrl = $this->userVars['server'] . $this->replaceUserVar($url);

        // current url
        $url = $this->getSession()->getCurrentUrl();

        if( $exprectedUrl!==$url ){
            throw new \Exception(sprintf('The url should be ' . $exprectedUrl . ' found ' . $url ));
        }
    }

    /**
     * @When Je clique sur l'élément ":selecteur_css"
     *
     * @param string $css
     */
    public function iClickSelector(string $css): void
    {
        // replace uservar in param
        $css = $this->replaceUserVar($css);

        $element = $this->getFirstVisibleElement('css',$css);

        $this->scrollAndClick($element);
    }

    /**
     * @When Je me place dans l'élément ":selecteur_css"
     *
     * @param string $css
     */
    public function iFocusSelector(string $css): void
    {
        $this->getFirstVisibleElement('css',$css)->focus();
    }


    /**
     * @When Je clique sur ":text"
     *
     * @param string $text
     */
    public function iClickOnTheText(string $text): void
    {
        // replace uservar in param
        $text = $this->replaceUserVar($text);

        $element = $this->getElementFromText($text);

        $this->scrollAndClick($element);
    }

    /**
     * @When J'essaye de cliquer sur ":text"
     *
     * @param string $text
     */
    public function iTryToClickOnTheText(string $text): void
    {
        try {
            $this->getElementFromText($text)->click();
        }
        catch(\Exception $e){
            // Erreur non bloquante
            $this->setNotBlockingErrorOccured(sprintf('Cannot clique on the text "%s"', $text));
        }
    }

}
