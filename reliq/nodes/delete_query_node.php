<?php

    namespace Reliq\Nodes;

    use Reliq\Factory;
    use Reliq\Table;

    class DeleteQueryNode extends Node {
        private $from = null;
        private $where = null;

        private $limit = null;

        public function __construct(Table $from) {
            $this->from = Factory::alias($from->get_table());
        }

        public function get_from() {
            return $this->from;
        }

        public function get_where() {
            return $this->where;
        }

        public function get_limit() {
            return $this->limit;
        }

        public function where(Node $node) {
            if (!$this->where) {
                $this->where = $node;
            }
            else {
                $this->where = $this->where->and_x($node);
            }
        }

        public function limit(Node $limit) {
            $this->limit = $limit;
        }
    }
