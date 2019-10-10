<?php
namespace froggdev\BehatContexts\Context;

use Exception;

/**
 * Trait DisplayContext
 * @package froggdev\BehatContexts\Context
 */
trait DisplayContext
{
    /**
     * Check if a text is present in a page
     *
     * @When Je devrai voir le texte ":texte"
     *
     * @param string $text
     * @throws Exception
     */
    public function iShouldSee(string $text) : void
    {
        // replace uservar in param
        $text = $this->replaceUserVar($text);

        // check if text exist in the page
        if( strpos( $this->getSession()->getPage()->getText(),$text ) === false ){
            throw new Exception(sprintf('Cannot find the text "%s" in the page', $text));
        }
    }

    /**
     * Check if a text is present in a page (trigger none blocking error)
     *
     * @When Il serait bien de voir le texte ":texte"
     *
     * @param string $text
     * @throws Exception
     */
    public function iMayShouldSee(string $text) : void
    {
        // replace uservar in param
        $text = $this->replaceUserVar($text);

        // check if text exist in the page
        if( strpos( $this->getSession()->getPage()->getText(),$text ) === false ){
            // Erreur non bloquante
            $this->setNotBlockingErrorOccured(sprintf('Cannot find the text "%s" in the page', $text));
        }
    }

    /**
     * Check if a text is present in a page (trigger none blocking error)
     *
     * @When Il serait bien de voir le texte ":texte" :case
     *
     * @param string $text
     * @throws Exception
     */
    public function iMayShouldSeeWithCase(string $text, string $case) : void
    {
        // replace uservar in param
        $text = $this->replaceUserVar($text);

        switch($case){
            case 'UCASE':
                $text = mb_strtoupper($text);
                break;
            case 'LCASE':
                $text = mb_strtolower($text);
                break;
            case 'UCFIRST':
                $text = ucfirst($text);
                break;
        }

        // check if text exist in the page
        if( strpos( $this->getSession()->getPage()->getText(),$text ) === false ){
            // Erreur non bloquante
            $this->setNotBlockingErrorOccured(sprintf('Cannot find the text "%s" in the page', $text));
        }
    }

    /**
     * Check if a text is not present in a page (trigger none blocking error)
     *
     * @When Il serait bien de ne pas voir le texte ":text"
     *
     * @param string $text
     *
     * @throws Exception
     */
    public function iMayShouldNotSee(string $text) : void
    {
        // replace uservar in param
        $text = $this->replaceUserVar($text);

        // check if text exist in the page
        if( strpos( $this->getSession()->getPage()->getText(),$text ) !== false ){
            // Erreur non bloquante
            $this->setNotBlockingErrorOccured(sprintf('Found the text "%s" in the page but should not', $text));
        }
    }

    /**
     * Check if a text is n'est visible dans la page
     *
     * @When Je ne devrai pas voir le texte ":texte"
     *
     * @param string $text
     * @throws Exception
     */
    public function iShouldNotSee(string $text) : void
    {
        // replace uservar in param
        $text = $this->replaceUserVar($text);

        $element = $this->getSession()->getPage()->find('xpath', '//*[contains(text(), "'.$text.'")]');

        // si n existe pas tout est ok
        if($element==null) return;

        // si existe alors doit etre invisible
        if($element->isVisible()){
            throw new Exception(sprintf('"%s" has been found in the page and should not', $text));
        }
    }

    /**
     * Check if a node element is visible
     *
     * @When Je devrai voir l'élément d'erreur ":selecteur_css"
     *
     * @param string $css
     * @throws Exception
     */
    public function iShouldSeeError(string $css): void
    {
        // Get node element
        $node = $this->getSession()->getPage()->find( 'css', $css );

        // Check if is visible
        if (!$node->isVisible()) {
            throw new Exception("Element \"$css\" is not visible.");
        }
    }

    /**
     * @Given Si la variable ":var" égal ":val" alors je devrai voir le texte ":text"
     *
     * @param string $var
     * @param string $val
     * @param string $text
     * @throws Exception
     */
    public function checkTextIfVar($var,$val,$text)
    {
        if( isset($this->userVars[$var]) && $this->userVars[$var]===$val){
            $this->iShouldSee($text);
        }
    }
}