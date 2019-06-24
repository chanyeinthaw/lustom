<?php


namespace Lumos\Lustom\Docblock;


abstract class Processor {
    protected $params;

    abstract public function name() : string ;
    abstract public function process();

    public function setParams(array $params) {
        $this->params = $params;
    }
}