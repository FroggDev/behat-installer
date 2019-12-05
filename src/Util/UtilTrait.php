<?php
namespace froggdev\BehatContexts\Util;

use Behat\Mink\Element\NodeElement;
use Exception;

trait UtilTrait
{
    /**
     * @Given J'affiche un message dans la console ":text"
     */
    public function displayInConsole(string $text)
    {
			$text = $this->replaceUserVar($text);
			
			echo "\n$text\n";
		}
	
    /**
     * @param string $uriOrUrl
     * @return string
     */
		public function getFullUrl(string $uriOrUrl)
		{
			return substr( $uriOrUrl, 0, 4 ) === "http" ? $uriOrUrl : $this->getMinkParameter('base_url') . '/' . $uriOrUrl;
		}

    /**
     * @param string $text
     * @return NodeElement|null
     */
    public function getElementFromText(string $text)
    {
        // return first visible elements
        return $this->getFirstVisibleElement('xpath', '//*[contains(text(), "'.$text.'")]');
    }

    /**
     * @param string $xpathElement
     * @param string $text
     * @return NodeElement|null
     */
    public function getElementFromTextLike(string $xpathElement,string $text)
    {
        // return first visible elements
        return $this->getFirstVisibleElement('xpath', '//'.$xpathElement.'/*[contains(., "'.$text.'")]');
    }

    /**
     * @param NodeElement $element
     * @param string $tagName
     * @return NodeElement|null
     */
    public function getParentElementFromTagName(NodeElement $element, string $tagName)
    {
        $tagName = strtolower($tagName);

        // Get parent field Set
        while($tagName!==$element->getTagName() || 'body'===$element->getTagName()){
            $element=$element->getParent();
        }

        return $element;
    }

    /**
     * @param string $field
     * @param null|string $css
     * @return NodeElement|null
     */
    public function getParentElementFromText(string $field, string $tagName): ?NodeElement
    {
        // Get element from text
        $element = $this->getElementFromText($field);

        // return selected input
        return $this->getParentElementFromTagName($element,$tagName);
    }

    /**
     * @param string $css
     * @param string $tagName
     * @return NodeElement|null
     */
    public function getParentElementFromCss(string $css, string $tagName): ?NodeElement
    {
        // Get element from css
        $element = $this->getFirstVisibleElement('css',$css);

        // return selected input
        return $this->getParentElementFromTagName($element,$tagName);
    }

    /**
     * @param string $xpath
     * @param string $tagName
     * @return NodeElement|null
     */
    public function getParentElementFromXpath(string $xpath, string $tagName): ?NodeElement
    {
        // Get element from css
        $element = $this->getFirstVisibleElement('xpath',$xpath);

        // return selected input
        return $this->getParentElementFromTagName($element,$tagName);
    }

    /**
     * @param NodeElement $element
     * @param string $tagName
     * @return NodeElement|null
     */
    public function getParentElementFromClass(NodeElement $element, string $class)
    {
        // Get parent field Set
        while(!$element->hasClass($class) || 'body'===$element->getTagName()){
            $element=$element->getParent();
        }

        return $element;
    }


    /**
     * @Given Je rempli le champs ":css" avec la valeur ":value" en javascript
     *
     * @param string $css
     * @param string $value
     *
     * @return NodeElement|null
     */
    public function setInputValueInJS(string $css,string $value)
    {
        // replace uservar in param
        $value = $this->replaceUserVar($value);

        return $this
            ->getSession()
            ->getDriver()
            ->evaluateScript("function(){  document.querySelector('" . addslashes($css) . "').value='".addslashes($value)."';  }()");
    }

    /**
     * @param string $css
     * @return NodeElement|null
     */
    public function getInputValueInJS(string $css)
    {
        return $this
            ->getSession()
            ->getDriver()
            ->evaluateScript("function(){ return document.querySelector('" . $css . "').value; }()");
    }


    /**
     * @param string $type
     * @param string $selector
     * @return NodeElement|null
     */
    public function getFirstVisibleElement(string $type , string $selector)
    {
        /** @var  \Behat\Mink\Session $session */
        $session = $this->getSession();

        // Get all matching elements
        /*
        $elements = $session->getPage()->findAll(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath($type,$selector)
        );
        */
        $elements = $session->getPage()->findAll(
            $type,
            $selector
        );

        // Return first visible element
        foreach($elements as $element){
            if($element->isVisible()){
                return $element;
            }
        }

        // If no results
        throw new \InvalidArgumentException(sprintf('Cannot find xpath: "%s"', $selector));
    }

    /**
     * @param string $id
     * @param string $selector
     */
    public function addIdToElementInJS(string $newId , string $selector):void
    {
        $function = '(function(){ elem = document.querySelector("' . $selector . '").id = "' . $newId . '"; })()';
        $this->getSession()->executeScript($function);
    }

    /**
     * Scroll HTML element with the supplied ID in view so that you can click on it (for example)
     */
    public function scrollTo(NodeElement $element)
    {
        $xpath = $element->getXpath();

        $xpath = addslashes($xpath);

        $function = <<<JS
    (
        function(){    

            var elems = document.evaluate('$xpath', document, null, XPathResult.ANY_TYPE, null );

            var elem = elems.iterateNext();
             
            window.scrollTo(0,elem.offsetTop);
        }
    )()
JS;

        $this->getSession()->executeScript($function);
    }

    /**
     * @param NodeElement $element
     */
    public function scrollAndClick(NodeElement $element,int $nb = 0)
    {
        try{
             $element->click();
        }
        catch(Exception $e){
            if($nb<5){
                $nb++;
                $this->iWaitMilliSec(300);
                $this->scrollAndClick($element,$nb);
            }
        }
    }

    /**
     * @param string $name
     * @return NodeElement|null
     */
    public function getElementByName(string $name):?NodeElement
    {
        return $this
            ->getSession()
            ->getPage()
            ->find('css', "[name=\"".$name."\"]");
    }

		public function textExistInPage(string $text): bool
		{
        // check if text exist in the page
        if( strpos( $this->getSession()->getPage()->getText(),$text ) === false ){
					// If not found then get node element and check  into values elements
					$node = $this->getSession()->getPage()->find( 'css', "*[value='$text']" );					
					return $node!==null;
				}
				
				return true;
		}

}