<?php

    namespace Reliq\Exceptions;

    class WrongNodeTypeException extends \Exception {
        public function __construct() {
            parent::__construct('Wrong type of input. Only \'Node\' is accepted.');
        }
    }