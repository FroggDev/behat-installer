<?php
namespace froggdev\BehatContexts\Util;

use Behat\Mink\Element\NodeElement;
use Exception;

trait MathTrait{
	
    /**
     * @param int $n
     * @return int
     */
    public static function factoriel($n)
    {
        if($n==0) return 1; else return $n*self::factoriel($n-1);
    }
}