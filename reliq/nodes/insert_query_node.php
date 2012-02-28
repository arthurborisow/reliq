<?php

    namespace Reliq\Nodes;

    use Reliq\Table;
    use Reliq\Factory;

    class InsertQueryNode extends Node {
        private $into = null;
        private $where = null;

        private $columns = array();

        private $limit = null;

        public function __construct(Table $into) {
            $this->into = Factory::alias($into->get_table());
        }

        public function get_into() {
            return $this->into;
        }

        public function get_where() {
            return $this->where;
        }

        public function get_columns() {
            return $this->columns;
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

        public function columns($columns) {
            $this->columns = array_merge($this->columns, $columns);
        }

        public function limit(Node $limit) {
            $this->limit = $limit;
        }
    }
