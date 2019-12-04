<?php
namespace froggdev\BehatContexts\Util;

use Behat\Mink\Element\NodeElement;
use Exception;

trait StringTrait{

    /**
     * @param string $value
     * @param string $delimiter
     * @return array
     */
    public function getSplit(string $value, string $delimiter=','):array
    {
        return array_map( 'trim', explode( $delimiter , $value ) );
    }
}