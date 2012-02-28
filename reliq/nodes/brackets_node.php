<?php

    namespace Reliq\Nodes;

    class BracketsNode extends Node {
        public function __construct(Node $node) {
            parent::__construct(null, $node);
        }
    }
