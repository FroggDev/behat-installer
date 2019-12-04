<?php
namespace froggdev\BehatContexts\FeaturedContext\Mui;

use Behat\Gherkin\Node\TableNode;
use Exception;

trait SpecificTrait
{

    /**
     * $INPUTNAME must be set and name {'.$INPUTNAME.'}
     * @Then Il serait bien de voir les textes:
     *
     * @param TableNode $tableNode
     * @throws Exception
     */
    public function MuiTextCouldBeDisplayed(TableNode $tableNode): void
    {
        // format table node value and replace vars
        $formatedVars = $this->replaceAllUserVarByNameDimensionnal($tableNode->getTable());

        // fill each fields
        foreach ($formatedVars as $k => $v){

            switch($v[1]){
                case 'UCASE':
                    $v[0] = mb_strtoupper($v[0]);
                    break;
                case 'UCFIRST':
                    $v[0] = ucfirst($v[0]);
                    break;
                case 'LCASE':
                    $v[0] = mb_strtolower($v[0]);
                    break;
                default:
                    break;
            }

            $this->iMayShouldSee( $v[0] );
        }
    }

    /**
     * $INPUTNAME must be set and name {'.$INPUTNAME.'}
     * @Then Je rempli le tableau et je duplique les lignes:
     *
     * @param TableNode $tableNode
     * @throws Exception
     */
    public function MuiSetAndCheckTable(TableNode $tableNode): void
    {
        $this->iWaitMilliSec(300);

        // num lines duplicate
        $num = 0;

        // format table node value and replace vars
        $formatedVars = $this->replaceAllUserVarByName($tableNode->getTable());

        $totals = [];

        // fill each fields (only first ligne)
        foreach ($formatedVars as $k => $v){

            $element = $this->getElementByName($k . '0');

            if(!$element){
                $num = intval($v);
                continue;
            }

            $element->setValue($v);

            $totals[] = floatval(str_replace(',', '.',$v));
        }

        // fill default with 0 (exept first ligne) to remove erros & add one more test if value changed
        foreach ($formatedVars as $k => $v) {
            for ($i = 1; $i < $num+1; $i++) {
                $element = $this->getElementByName($k . $i);

                if (!$element) continue;

                $element->setValue("0");
            }
        }

        // Click on duplicate lines
        for($i=0;$i<$num;$i++){
             $element = $this
                ->getSession()
                ->getPage()
                ->find('css', "IMG[data-id=$i]");

            $this->scrollAndClick($element);
        }

        // Check total
        $multiple=12/($num+1); // calculate for the year
        foreach($totals as $key => $value){
            $this->iMayShouldSee(str_replace( '.',',',''. ( $value * ($num + 1) * $multiple )));
        }
    }

}