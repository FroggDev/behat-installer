<?php
namespace froggdev\BehatInstaller\Util;

use froggdev\PhpUtils\FileUtil;

trait UserVarsTrait
{

    # VARS
    #-----

		private $tmpFile='./test.tmp';

    #################################
    # FUNCTION USER VARS MANAGEMENT #
    #################################

    /**
     * save user vars to files
     */
    public function setUserVars(): void
    {
        FileUtil::writeTofile(
            $this->tmpFile,
            serialize($this->userVars)
        );
    }

    /**
     * resotre user vars from files
     */
    public function getUserVars(): void
    {
        if (file_exists($this->tmpFile)) {
            $this->userVars = unserialize(file_get_contents($this->tmpFile));
            unlink($this->tmpFile);
        }
    }

    /**
     * @param string|null $text
     * @return string|null
     */
    public function replaceUserVar(?string $text): ?string
    {
        if (null === $text) {
            return null;
        }

        //search for all {key} in the text
        preg_match_all('/{([^}]*)}/', $text, $matches);
        // replace all {key} by userVars[key] in the text
        if (count($matches) > 0) {
            foreach ($matches[1] as $value) {

                // if not exist return it self value into {}
                if(!isset($this->userVars[$value])) return $value;

                $text = str_replace(
                    '{' . $value . '}',
                    $this->userVars[$value],
                    $text
                );
            }
        }
        return $text;
    }

    public function replaceUserVarEscaped(?string $text): ?string
    {
        return addslashes($this->replaceUserVar($text));
    }

    /**
     * @param array $array
     * @return array|null
     */
    public function replaceAllUserVar(array $array): ?array
    {
        $res = [];

        foreach($array as $k => $v){


            switch(true){

                case 'string'===gettype($v) :
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
     * @param array $array
     * @return array|null
     */
    public function replaceAllUserVarByNameDimensionnal(array $array): ?array
    {
        $res = [];

        foreach($array as $k => $v){


            switch(true){

                case 'string'===gettype($v) :
                    $res[] = $this->replaceUserVar($v);
                    break;

                case 'array'===gettype($v) :
                    $tmp = [];

                    foreach($v as $k1 => $v1) {
                        $tmp[$k1] = $this->replaceUserVar('{' . $v1 . '}');
                    }

                    array_push($res , $tmp);
                    break;
            }
        }
        return $res;
    }

    /**
     * Only one value set
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
