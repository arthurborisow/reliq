<?php

    namespace Reliq\Nodes;

    use Reliq\Table;
    use Reliq\Factory;

    class SelectQueryNode extends Node {

        private $projections = array();
        private $from = null;
        private $joins = array();
        private $where = null;
        private $orders = array();
        private $groups = array();
        private $having = null;

        private $limit = null;
        private $offset = null;

        public function __construct(Table $from) {
            $this->from = Factory::alias($from->get_table());
        }

        public function get_projections() {
            return $this->projections;
        }

        public function get_from() {
            return $this->from;
        }

        public function get_joins() {
            return $this->joins;
        }

        public function get_where() {
            return $this->where;
        }

        public function get_orders() {
            return $this->orders;
        }

        public function get_groups() {
            return $this->groups;
        }

        public function get_having() {
            return $this->having;
        }

        public function get_limit() {
            return $this->limit;
        }

        public function get_offset() {
            return $this->offset;
        }

        public function join(JoinNode $node) {
            $this->joins[] = $node;
        }

        public function get_last_join() {
            if (count($this->joins) < 1) {
                return null;
            }
            return $this->joins[count($this->joins) - 1];
        }

        public function where(Node $node) {
            if (!$this->where) {
                $this->where = $node;
            }
            else {
                $this->where = $this->where->and_x($node);
            }
        }

        public function group_by(Node $node) {
            $this->groups[] = $node;
        }

        public function having(Node $node) {
            if (!$this->having) {
                $this->having = $node;
            }
            else {
                $this->having = $this->having->and_x($node);
            }
        }

        public function order_by(OrderNode $node) {
            $this->orders[] = $node;
        }

        public function limit(Node $limit) {
            $this->limit = $limit;
        }

        public function offset(Node $offset) {
            $this->offset = $offset;
        }

        public function projections() {
            $arguments = func_get_args();
            foreach ($arguments as $argument) {
                if (!($argument instanceof Node)) {
                    if ($argument instanceof Table) {
                        $argument = Factory::sql($argument->get_name() . '.*');
                    }
                    else {
                        $argument = Factory::sql($argument);
                    }
                }

                $this->projections[] = $argument;
            }
        }
    }
