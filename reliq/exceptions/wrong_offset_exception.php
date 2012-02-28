<?php

    namespace Reliq\Exceptions;

    class WrongOffsetException extends \Exception {
        public function __construct() {
            parent::__construct('OFFSET must be integer and greater than 0.');
        }
    }