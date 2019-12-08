<?php
namespace froggdev\BehatInstaller\FeaturedContext\Mui;

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use WebDriver\Key;
use Exception;

trait FormTrait
{
    /**
     * @Then Je test le formulaire:
     *
     * @param TableNode $tableNode
     * @throws Exception
     */
    public function MuiCheckForm(TableNode $tableNode): void
    {
        $requires = [];
        $optionals = [];

        // fill each fields
        foreach ($tableNode->getTable() as $k => $v){

            switch($v[1]){
                case 'REQUIRED':
                    $requires[] = $v[0];
                    break;
                case 'OPTIONAL':
                    $optionals[] = $v[0];
                    break;
                case 'VALIDATIONBUTTON':
                    // Submit to test validation
                    $this->MuiIClickButton($v[0]);
                    break;
                case 'VALIDATION':
                    // Submit to test validation
                    $this->iClickOnTheText($v[0]);
                    break;
                default:
                    // Erreur non bloquante
                    $this->setNotBlockingErrorOccured(sprintf("%s is not a valid type in MuiCheckForm", $v[1]));
                    break;
            }

        }

        // Check if fields are required
        foreach($requires as $require){
           $this->MuiCheckInputNameRequired($require);
        }

        // Check if fields are optional
        foreach($optionals as $optional){
           $this->MuiCheckInputNameOptional($optional);
        }

    }

    /**
     * @When Je selectionne ":name"
     *
     * @param string $name
     */
    public function MuiISelectText(string $name)
    {
        $value = $this->replaceUserVar('{'.$name.'}');

        $this->MuiISelectTextWithValue($name,$value);
    }

    /**
     * @When Je selectionne ":name" avec la valeur ":value"
     *
     * @param string $name
     * @param string $value
     */
    public function MuiISelectTextWithValue(string $name,string $value)
    {

            $value = $this->replaceUserVar($value);

            $selectInputSelector = "#select-$name";
            $liSelector = "LI[data-value=\"$value\"]";

            /**@var NodeElement $element */
            $element = $this
                ->getSession()
                ->getPage()
                ->find('css', $selectInputSelector);

            $this->scrollAndClick($element);

        try {
            $elementSelect = $this->getFirstVisibleElement('css', $liSelector);

            $this->scrollAndClick($elementSelect);

            // SPECIAL IE
            if ($element->getText() === '') {

                // use JS to do the action
                $this
                    ->getSession()
                    ->getDriver()
                    ->evaluateScript(
                        "function(){ 
                         document.querySelector('$selectInputSelector').click(); 
                         document.querySelector('$liSelector').click(); 
                     }()"
                    );
            }
        }
        catch(Exception $e){

            // SPECIAL IE
            // use JS to do the action
            $this
                ->getSession()
                ->getDriver()
                ->evaluateScript(
                    "function(){ 
                         document.querySelector('$selectInputSelector').click(); 
                         document.querySelector('$liSelector').click(); 
                     }()"
                );
        }
    }

    /**
     * $INPUTNAME must be set and name {'.$INPUTNAME.'}
     * @Then Je rempli le formulaire:
     *
     * @param TableNode $tableNode
     * @throws Exception
     */
    public function MuiSetInputsValues(TableNode $tableNode): void
    {
        // format table node value and replace vars
        $formatedVars = $this->replaceAllUserVarByName($tableNode->getTable());

        // fill each fields
        foreach ($formatedVars as $k => $v){
            $this->MuiSetInputNameValueWithValue( $k, $v);
            //$this->getElementByName($k)->setValue($v);
        }
    }

    /**
     * $INPUTNAME must be set and name {'.$INPUTNAME.'}
     * @Then Je rempli ":name"
     * @param string $name
     */
    public function MuiSetInputNameValue(string $name): void
    {
        $value = $this->replaceUserVar('{'.$name.'}');

        $this->MuiSetInputNameValueWithValue($name,$value);
    }

    /**
     * @Then Je rempli ":name" avec la valeur ":value"
     * @param string $name
     * @param string $value
     */
    public function MuiSetInputNameValueWithValue(string $name,string $value): void
    {
        $value = $this->replaceUserVar($value);

        /** @var NodeElement $element */
        $element = $this->getElementByName($name);

        $this->scrollAndClick($element);

        try {

            // If date remove manually the content
            if(preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',$value)) {
                $element->setValue(Key::DELETE . Key::BACKSPACE . Key::BACKSPACE . Key::BACKSPACE . Key::BACKSPACE . Key::BACKSPACE . Key::BACKSPACE . Key::BACKSPACE . Key::BACKSPACE . Key::BACKSPACE . Key::BACKSPACE);
            }

            $element->setValue($value);
        }
        catch(Exception $e){
            // Continue here if error
            //case when input is removed for another
            //ex: dateArretIni change input date to input readonly
        }
    }

    /**
     * $INPUTNAME must be set and name {'.$INPUTNAME.'ShouldBe}
     * @Then Je vérifie ":name"
     * @param string $name
     * @throws Exception
     */
    public function MuiCheckInputNameValue(string $name):void
    {
        // replace uservar in param
        $expectedValue = $this->replaceUserVar('{' . $name . (isset($this->userVars[ $name. 'ShouldBe']) ? 'ShouldBe' : '') . '}');

        // get input value
        $inputValue = $this->getElementByName($name)->getAttribute('value');

        //echo "MuiCheckInputNameValue replace $name by {" . $name . (isset($this->userVars['ShouldBe']) ? 'ShouldBe' : '') . "}  = $expectedValue VERSUS $inputValue \n";

        // check if values match
        if (strtolower($inputValue) !== strtolower($expectedValue)) {
            $this->setNotBlockingErrorOccured("input $name value should be $expectedValue instead of $inputValue");
        }
    }

    /**
     * @Then Je vérifie que ":name" est vide
     * @param string $name
     * @throws Exception
     */
    public function MuiCheckInputNameEmpty(string $name):void
    {
        // get input value
        $inputValue = $this->getElementByName($name)->getAttribute('value');

        // check if value empty
        if ($inputValue !== '') {
            $this->setNotBlockingErrorOccured("input $name value should be empty instead of $inputValue");
        }
    }

    /**
     * @Then Le champ ":name" ne devrait pas être en lecture seule
     * @param string $name
     */
    public function MuiInputCouldNotBeReadOnly(string $name)
    {
        /**@var NodeElement $element*/
        $element = $this->getElementByName($name);

        // check if input is read only
        if ($element->hasAttribute('readonly')===true) {
            // Erreur non bloquante
            $this->setNotBlockingErrorOccured(sprintf("input %s should not be in readonly", $name));
        }
    }

    /**
     * @Then Le champ ":name" devrait être en lecture seule
     * @param string $name
     */
    public function MuiInputCouldBeReadOnly(string $name)
    {
        /**@var NodeElement $element*/
        $element = $this->getElementByName($name);

        // check if input is read only
        if ($element->hasAttribute('readonly')===false) {
            // Erreur non bloquante
            $this->setNotBlockingErrorOccured(sprintf("input %s should be in readonly", $name));
        }
    }

    /**
     * @Then Je vérifie que ":name" obligatoire
     * @param string $name
     * @throws Exception
     */
    public function MuiCheckInputNameRequired(string $name):void
    {
        /**@var NodeElement $input*/
        $input = $this->getElementByName($name);

        /**@var NodeElement $element*/
        try{
            $element = $this->getParentElementFromClass($input, 'MuiTextField-root');
        }
        catch(Exception $e){
            // Case for radio element
            $element = $this->getParentElementFromClass($input, 'MuiFormControl-root');
        }

        /**@var NodeElement $error*/
        $error = $element->find('css','.Mui-error');

        if($error) {

            /**@var NodeElement $errorTxt*/
            $errorTxt = $element->find('css','.MuiFormHelperText-root');

            if ($errorTxt->getText()!=='Champs requis') {
                // Erreur non bloquante
                $this->setNotBlockingErrorOccured(sprintf("input %s error text should be [Champs requis] but found " . $error->getText(), $name));
            }
        }else{
            // Erreur non bloquante
            $this->setNotBlockingErrorOccured(sprintf("input %s should be required, no error triggered", $name));
        }
    }

    /**
     * @Then Je vérifie que ":name" optionel
     * @param string $name
     * @throws Exception
     */
    public function MuiCheckInputNameOptional(string $name):void
    {
        // get input value
        $input = $this->getElementByName($name);

        /**@var NodeElement $element*/
        try{
            $element = $this->getParentElementFromClass($input, 'MuiTextField-root');
        }
        catch(Exception $e){
            // Case for radio element
            $element = $this->getParentElementFromClass($input, 'MuiFormControl-root');
        }

        /**@var NodeElement $error*/
        $error = $element->find('css','.Mui-error');

        if( $error ){
            // Erreur non bloquante
            $this->setNotBlockingErrorOccured(sprintf("input %s should not be required", $name));
        }
    }
}
