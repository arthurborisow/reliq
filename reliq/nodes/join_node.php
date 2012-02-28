<?php

    namespace Reliq\Nodes;

    class JoinNode extends Node {
        public function on(Node $node) {
            if ($this->right()) {
                $this->right = $this->right()->and_x($node);
            }
            else {
                $this->right = $node;
            }
        }
    }
