<?php

    namespace Reliq\Nodes;

    use Reliq\Table;
    use Reliq\Factory;

    class UpdateQueryNode extends Node {
        private $from = null;
        private $where = null;

        private $columns = array();

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
