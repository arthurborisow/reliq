<?php

    namespace Reliq\Nodes;

    class SqlNode extends BaseNode {
        public function __construct($value) {
            $this->value = $value;
        }
    }
