<?php

    namespace Reliq\Exceptions;

    class WrongLimitException extends \Exception {
        public function __construct() {
            parent::__construct('LIMIT must be integer and greater than 0.');
        }
    }