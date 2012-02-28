<?php

    namespace Reliq\Nodes;

    class FuncNode extends BaseNode {
        private $args = array();
        private $name = '';

        public function __construct($name, $args) {
            $this->name = $name;
            $this->args = $args;
            parent::__construct(null, null);
        }

        public function get_name() {
            return $this->name;
        }

        public function get_args() {
            return $this->args;
        }
    }
