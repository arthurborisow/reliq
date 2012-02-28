<?php

    namespace Reliq\Nodes;

    class InNode extends BaseNode {
        private $ins = array();

        public function __construct($left, $ins) {
            $this->ins = $ins;
            parent::__construct($left, null);
        }

        public function get_ins() {
            return $this->ins;
        }
    }
