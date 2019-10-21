<?php
namespace froggdev\BehatContexts\Util;

/**
 * Trait UserVarTrait
 * @package froggdev\BehatContexts\Util
 */
trait UserVarTrait
{
    /** @var string TMPFILE use to store var between each scenario to send to another one */
    private $tmpFile = './test.tmp';

    /** @var array $userVars store some user var as an array with key  */
    private $userVars = [];

    /**
     * save user vars to files
     */
    private function setUserVars(): void
    {
        $this->writeTofile(
            $this->tmpFile,
            serialize($this->userVars)
        );
    }

    /**
     * resotre user vars from files
     */
    private function getUserVars(): void
    {
        if (file_exists($this->tmpFile)) {
            $this->userVars = unserialize(file_get_contents($this->tmpFile));
        }
    }

    /**
     * reset the user vars
     */
    private function resetUserVars():void
    {
        // reset var
        $this->userVars=[];
    }

    /**
     * remove the user vars temp file
     */
    private function removeUserVars():void
    {
        // Remove file from disk
        @unlink($this->tmpFile);

        // Clean var
        $this->resetUserVars();
    }

    /**
     * replace all {var} intext by the userVar[key]
     *
     * @param string|null $text
     * @return string|null
     */
    public function replaceUserVar(?string $text): ?string
    {
        if (!$text) return null;

        //search for all {key} in the text
        preg_match_all('/{([^}]*)}/', $text, $matches);

        // replace all {key} by userVars[key] in the text
        if (count($matches) > 0) {

            // for each result do a replace
            foreach ($matches[1] as $value) {

                // if not exist return it self value into {}
                if(!isset($this->userVars[$value])) {

                    echo "var $value not found, skipping it ...";

                    continue;
                }

                // doing the replace
                $text = str_replace(
                    '{' . $value . '}',
                    $this->userVars[$value],
                    $text
                );
            }
        }

        return $text;
    }


    /**
     * Replace var and escape it
     *
     * @param string|null $text
     * @return string|null
     */
    public function replaceUserVarEscaped(?string $text): ?string
    {
        return addslashes($this->replaceUserVar($text));
    }

    /**
     * Replace all var recursivelly
     *
     * @param array $array
     * @return array|null
     */
    public function replaceAllUserVar(array $array): ?array
    {
        $res = [];

        foreach($array as $k => $v){

            switch(gettype($v)){

                case 'string' :
                    $res[] = $this->replaceUserVar($v);
                    break;

                case 'array' :
                    $res[] = $this->replaceAllUserVar($v);
                    break;
            }
        }

        return $res;
    }

    /**
     * Only one value set : name must match {var}
     *
     * @param array $array
     * @return array|null
     */
    public function replaceAllUserVarByName(array $array): ?array
    {
        $res = [];

        foreach($array as $k => $v){
            foreach($v as $key => $value) {
                $res[$value] = $this->replaceUserVar('{' . $value . '}');
            }
        }

        return $res;
    }
}