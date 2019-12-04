<?php
namespace froggdev\BehatContexts\FeaturedContext\Mui;

trait NavigationTrait
{
    /**
     * @When Je clique sur l'icône ":text"
     *
     * @param string $text
     */
    public function MuiIClickIcon(string $text): void
    {
        // replace uservar in param
        $text = $this->replaceUserVarEscaped($text);

        $element = $this->getFirstVisibleElement('css',"BUTTON[title='$text']");

        $this->scrollAndClick($element);
    }

    /**
     * @When Je clique sur le bouton ":text"
     *
     * @param string $text
     */
    public function MuiIClickButton(string $text): void
    {
        // replace uservar in param
        $text = $this->replaceUserVarEscaped($text);

        $element = $this->getParentElementFromCss("SPAN:contains('$text')", 'button');

        $this->scrollAndClick($element);
    }

    /**
     * THIS IS A FIX CAUSE THE MATERIAL TABLE SHOULD SET THE TITLE IN BUTTON INSTEAD OF SPAN
     *
     * @When Je clique sur l'icône du tableau ":text"
     *
     * @param string $text
     */
    public function MuiIClickIconTable(string $text): void
    {
        // replace uservar in param
        $text = $this->replaceUserVarEscaped($text);

        $element = $this->getFirstVisibleElement('css',"SPAN[title='$text']");

        $this->scrollAndClick($element);
    }


}