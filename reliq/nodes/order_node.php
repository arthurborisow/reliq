<?php

    namespace Reliq\Nodes;

    class OrderNode extends Node {

        private $asc = true;

        public function __construct(Node $by, $asc = true) {
            parent::__construct(null, $by);
            $this->asc = (bool)$asc;
        }

        public function is_asc() {
            return $this->asc;
        }
    }
