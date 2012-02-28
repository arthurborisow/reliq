<?php

    namespace Reliq\Exceptions;

    class WrongOrderDataException extends \Exception {
        public function __construct() {
            parent::__construct('Order must be a simple string, or Node,
            or array of simple string or node with boolean value which tell
            whether sorting should be ascending.');
        }
    }