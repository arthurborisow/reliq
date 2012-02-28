<?php

    namespace Reliq\Nodes;

    use Reliq\Factory;

    class Node {
        protected $left = null;
        protected $right = null;
        protected $value = '';

        public function __construct($left, $right) {
            $this->left = $left;
            $this->right = $right;
        }

        public function left() {
            return $this->left;
        }

        public function right() {
            return $this->right;
        }

        public function value() {
            return $this->value;
        }


        public function and_x(Node $right) {
            return Factory::and_x($this, $right);
        }

        public function or_x(Node $right) {
            return Factory::or_x($this, $right);
        }

        public function accept_visitor(Visitor $visitor) {
            return $visitor->visit($this);
        }
    }
