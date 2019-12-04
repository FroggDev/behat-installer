<?php
namespace froggdev\BehatContexts\Util;

trait ErrorTrait
{

    /** @var string separateur du tableau des erreur */
    private $errSeparator = '_@_';

    /** @var int nb d'erreur non bloquante */
    private $nbError = 0;

    /**
     * @When Je verifie si des erreurs non bloquantes sont survenues
     *
     * @throws \Exception
     */
    public function checkIfNotBlockingErrorOccured():void
    {

        // If not errors skip func
        if( !isset( $this->userVars['notBlockingError'] ) || $this->userVars['notBlockingError']==="" ){
            return;
        }

        $errs = explode($this->errSeparator , $this->userVars['notBlockingError']);

        // Clean old not blocking errors
        $this->userVars['notBlockingError']="";

        //If error throw exception with report
        if( count($errs)>0 ){
            throw new \Exception( implode("\r\n" , $errs) );
        }
    }

    /**
     * Add an error to the list
     *
     * @param string $errMsg
     */
    public function setNotBlockingErrorOccured(string $errMsg):void
    {
        $this->iTakeAScreenShot('ErreurNonBloquante-'.$this->nbError.'.png');

        isset( $this->userVars['notBlockingError'] ) ? $this->userVars['notBlockingError'].= $this->errSeparator . $errMsg : $this->userVars['notBlockingError'] = $errMsg;

        echo "Minor error occured : $errMsg\n";
        
        $this->nbError++;
    }

}
