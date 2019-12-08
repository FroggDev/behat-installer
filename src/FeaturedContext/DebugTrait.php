<?php
namespace froggdev\BehatInstaller\FeaturedContext;

trait DebugTrait
{
    /**
     * @Given J'affiche dans la console ":text"
     *
     * @param string $text
     *
     * @throws \Exception
     */
    public function consoleDisplay($text): void
    {
        // replace uservar in param
        echo $this->replaceUserVar($text);
    }

    /**
     * @Given Je sauvegarde le contenu de la page dans le fichier ":fichier"
     *
     * @param string $fichier
     */
    public function iSavePageToFile($fichier): void
    {
        $page = $this->getSession()->getPage()->getContent();

        $this->writeTofile( $fichier , $page );
    }
}

