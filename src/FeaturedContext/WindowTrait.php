<?php
namespace froggdev\BehatContexts\FeaturedContext;

trait WindowTrait
{

    #########################
    # ACTION FRAME & WINDOW #
    #########################

    /**
     * @Given Je redimensionne l'écran en ":largeur" x ":hauteur"
     *
     * @param string $width
     * @param string $height
     */
    public function iSetBrowserWindowSizeToX(string $width,string  $height): void
    {
        $this->getSession()->restart();
        $this->getSession()->resizeWindow((int)$width, (int)$height, 'current');
    }

    /**
     * @Given J'ouvre le conteneur ":selecteur_css" dans une nouvelle fenêtre
     *
     * @param string $css
     */
    public function iOpenAnIframeSameServer(string $css): void
    {
        $this->iOpenAnIframe($css);
    }

    /**
     * @Given J'ouvre le conteneur ":selecteur_css" depuis le serveur ":adresse_du_serveur" dans une nouvelle fenêtre
     *
     * @param string $css
     * @param string|null $server
     */
    public function iOpenAnIframe(string $css,string $server = null): void
    {
        $this->visit($server . $this->getFirstVisibleElement('css', $css)->getAttribute('src'));
    }

    /**
     * @Then Je me place dans le conteneur ":nom_du_conteneur"
     *
     * @param string $id
     */
    public function switchFrame(string $id): void
    {
        $this->getSession()->switchToIframe($id);
    }

    /**
     * @Then Je me place dans le conteneur central
     */
    public function switchCentralFrame(): void
    {
        // back to main frame
        $this->switchMainFrame();

        // then go to central frame
        $this->getSession()->switchToIframe($this->userVars['CENTRALFRAME']);
    }

    /**
     * @Then Je me place dans le conteneur principal
     */
    public function switchMainFrame(): void
    {
        $this->getSession()->getDriver()->switchToIFrame(null);
    }

    /**
     * @Then Je change d'onglet
     */
    public function iSwitchTab(): void
    {
        $windowNames = $this->getSession()->getWindowNames();
        if (count($windowNames) > 1) {
            $this->getSession()->switchToWindow($windowNames[count($windowNames)-1]);
        }
    }

    /**
     * @Then J'ouvre un nouvel onglet
     */
    public function iOpenNewTab(): void
    {
        /** @var \Behat\Mink\Session $session */
        $session = $this->getSession();

        $session->getDriver()->openWindow();
        $session->getDriver()->switchToWindow('_blank');

        $windowNames = $this->getSession()->getWindowNames();
        if (count($windowNames) > 1) {
            $this->getSession()->switchToWindow($windowNames[1]);
        }
    }
}