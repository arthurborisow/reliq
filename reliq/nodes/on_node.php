<?php

    namespace Reliq\Nodes;

    class OnNode extends BaseNode {
        public function __construct($right) {
            parent::__construct(null, $right);
        }
    }
