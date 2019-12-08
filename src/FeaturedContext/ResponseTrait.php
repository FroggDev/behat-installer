<?php
namespace froggdev\BehatInstaller\FeaturedContext;

trait ResponseTrait
{
    /**
     * @Given Je vérifie le lien de l'élément ":selecteur_css"
     *
     * @param string $css
     * @throws \Exception
     */
    public function checkLinkFileExist(string $css): void
    {
        // get href link
        $link = $this->getFirstVisibleElement('css',$css)->getAttribute('href');

        // execption if can't download
        if(!$this->fileUrlExist($link)){
            throw new \Exception("The $link can't be found");
        }
    }
}