<?php

    namespace Reliq\Nodes;

    class HavingNode extends Node {
        public function __construct(Node $node) {
            parent::__construct(null, $node);
        }
    }
