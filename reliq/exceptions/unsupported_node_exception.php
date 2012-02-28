<?php

    namespace Reliq\Exceptions;

    class UnsupportedNodeException extends \Exception {
        public function __construct($allowed_nodes) {
            parent::__construct('Allowed methods to create nodes: '
                                . join(', ', $allowed_nodes) . '.');
        }
    }