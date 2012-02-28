<?php

    namespace Reliq\Exceptions;

    class WrongHavingException extends \Exception {
        public function __construct() {
            parent::__construct('Having must be of type Node.');
        }
    }