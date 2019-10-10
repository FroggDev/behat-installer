<?php
namespace froggdev\BehatContexts\Context;


use Behat\Gherkin\Node\TableNode;

/**
 * Trait FormContext
 * @package froggdev\BehatContexts\Context
 */
trait FormContext
{
    /**
     * @Given Je rempli le champ ":selecteur_css" avec la valeur ":valeur"
     *
     * @param string $css
     * @param string $value
     * @throws \Exception
     */
    public function iFillWith(string $css, string $value): void
    {
        // replace uservar in param
        $value = $this->replaceUserVar($value);

        // fill the input
        $this->getSession()
            ->getPage()
            ->find('css', $css)
            ->setValue($value);
    }

    /**
     * @Given Je rempli le champ ":selecteur_css" avec la valeur ":valeur" en javascript
     *
     * @param string $css
     * @param string $value
     * @throws \Exception
     */
    public function fillImputWithJS(string $css, string $value): void
    {
        // replace uservar in param
        $expectedValue = $this->replaceUserVar($value);

        // set input value
        $this
            ->getSession()
            ->getDriver()
            ->evaluateScript("function(){ document.querySelector('" . $css . "').value='".$expectedValue."'; }()");
    }

    /**
     * @Given Le champ ":selecteur_css" devrait être rempli avec la valeur ":valeur"
     *
     * @param string $css
     * @param string $value
     * @throws \Exception
     */
    public function inputShouldBeFillWith(string $css, string $value): void
    {
        // replace uservar in param
        $expectedValue = $this->replaceUserVar($value);

        // get input value
        $inputValue = $this
            ->getSession()
            ->getPage()
            ->find('css', $css)
            ->getAttribute('value');

        // check if values match
        if (strtolower($inputValue) !== strtolower($expectedValue)) {
            $this->setNotBlockingErrorOccured("input $css value should be $expectedValue instead of $inputValue" );
        }
    }

    /**
     * @Given Le champ ":selecteur_css" devrait être rempli avec la valeur ":valeur" en javascript
     *
     * @param string $css
     * @param string $value
     * @throws \Exception
     */
    public function inputShouldBeFillWithJS(string $css, string $value): void
    {
        // replace uservar in param
        $expectedValue = $this->replaceUserVar($value);

        // get input value
        $inputValue = $this
            ->getSession()
            ->getDriver()
            ->evaluateScript("function(){ return document.querySelector('" . $css . "').value; }()");

        // check if values match
        if ($inputValue !== $expectedValue) {
            throw new \Exception("input $css value should be $expectedValue instead of $inputValue");
        }
    }

    /**
     * @Given Le champ nommé ":name" devrait être rempli avec la valeur ":valeur"
     *
     * @param string $css
     * @param string $value
     * @throws \Exception
     */
    public function inputNameShouldBeFillWith(string $name, string $value): void
    {
        // replace uservar in param
        $expectedValue = $this->replaceUserVar($value);

        // get input value
        $inputValue = $this
            ->getElementByName($name)
            ->getAttribute('value');

        // check if values match
        if (strtolower($inputValue) !== strtolower($expectedValue)) {
            $this->setNotBlockingErrorOccured("input $name value should be $expectedValue instead of $inputValue" );
        }
    }

    /**
     * @Given ":nom_du_champ" devrait être rempli avec la valeur ":valeur" en javascript
     *
     * @param string $field
     * @param string $value
     * @throws \Exception
     */
    public function fieldShouldBeFillWithJS(string $field, string $value): void
    {
        // replace uservar in param
        $expectedValue = $this->replaceUserVar($value);

        $js = <<<JS
    (function(){
        var results = document.evaluate('//*[text()="$field"]', document,null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
        for(var i=0;i++;i<results.snapshotLength){
            if(results.snapshotItem(i).offsetParent!=null){
                break;
            }
        }
        return results.snapshotItem(i).closest("FIELDSET").querySelector("INPUT").value;
    })()

JS;

        // get input value
        $inputValue = $this
            ->getSession()
            ->evaluateScript("return $js");

        // check if values match
        if (strtolower($inputValue) !== strtolower($expectedValue)) {
            throw new \Exception("input $field value should be $expectedValue instead of $inputValue");
        }
    }

    /**
     * @Then Je rempli le formulaire avec les valeurs:
     *
     * @param TableNode $tableNode
     * @throws \Exception
     */
    public function setInputsValues(TableNode $tableNode): void
    {
        // format table node value and replace vars
        $formatedVars = $this->replaceAllUserVar($tableNode->getTable());

        // fill each fields
        foreach ($formatedVars as $value){
            $this->iFillWith($value[0],$value[1]);
        }
    }

    /**
     * @Then Je vérifie le formulaire avec les valeurs:
     *
     * @param TableNode $tableNode
     * @throws \Exception
     */
    public function checkInputsValues(TableNode $tableNode): void
    {
        // format table node value and replace vars
        $formatedVars = $this->replaceAllUserVar($tableNode->getTable());

        // check each fields
        foreach ($formatedVars as $value){
            $this->inputShouldBeFillWith($value[0],$value[1]);
        }
    }

    /**
     * @Then Je vérifie le formulaire avec les valeurs en javascript:
     *
     * @param TableNode $tableNode
     * @throws \Exception
     */
    public function checkInputsValuesJS(TableNode $tableNode): void
    {
        // format table node value and replace vars
        $formatedVars = $this->replaceAllUserVar($tableNode->getTable());

        // check each fields
        foreach ($formatedVars as $value) {

            // get value from inut in JS
            $inputValue = $this
                ->getSession()
                ->getDriver()
                ->evaluateScript("function(){ return document.querySelector('" . $value[0] . "').value; }()");

            // check if values match
            if ($value[1] !== $inputValue) {
                throw new \Exception("input " . $value[0] . " value should be " . $value[1] . " instead of " . $inputValue);
            }
        }
    }

    /**
     * @Given Je selectionne l'élément ":num" option du champ ":css"
     *
     * @param string $css
     */
    public function iSetFirstOption(string $num, string $css): void
    {
        $this
            ->getSession()
            ->getDriver()
            ->evaluateScript("function(){ document.querySelector('$css').selectedIndex=$num; }()");
    }

    /**
     * @Given J'upload le fichier ":fichier" dans le champ ":champ"
     *
     * @param string $file
     * @param string $field
     */
    public function uploadFileToField(string $file,string $field): void
    {
        // get the input
        $input = $this->getSession()->getPage()->find('css',$field);

        // attach the upload file
        $input->attachFile($file);

        // basic method, not working in this case ...
        //$this->attachFileToField($field,$file);
    }

}