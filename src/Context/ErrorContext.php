<?php
namespace froggdev\BehatContexts\Context;

use Exception;
use froggdev\BehatContexts\Util\ErrorTrait;

/**
 * Trait ErrorContext
 * @package froggdev\BehatContexts\Context
 */
trait ErrorContext
{
    use ErrorTrait;

    /**
     * @When Je verifie si des erreurs non bloquantes sont survenues
     *
     * @throws Exception
     */
    public function checkIfNotBlockingErrorOccured():void
    {

        // If not errors skip func
        if( !isset( $this->userVars['notBlockingError'] ) ){
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
}