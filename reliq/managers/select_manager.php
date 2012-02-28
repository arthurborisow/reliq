<?php

    namespace Reliq\Managers;

    use Reliq\Table;
    use Reliq\Factory;

    use Reliq\Exceptions\WrongOffsetException;
    use Reliq\Exceptions\WrongOrderDataException;
    use Reliq\Exceptions\WrongHavingException;

    use Reliq\Nodes\Node;

    class SelectManager extends Manager {
        public function __construct(Table $from) {
            $this->statement = Factory::select_query($from);
        }

        public function projections() {
            call_user_func_array(array($this->statement, 'projections'),
                                 func_get_args());
            return $this;
        }

        public function join(Table $table) {
            $this->statement->join(Factory::join(
                                       Factory::alias($table->get_table()),
                                       null));
            return $this;
        }

        public function natural_join(Table $table) {
            $this->statement->join(Factory::natural_join(
                                       Factory::alias($table->get_table()),
                                       null));
            return $this;
        }

        public function cross_join(Table $table) {
            $this->statement->join(Factory::cross_join(
                                       Factory::alias($table->get_table()),
                                       null));
            return $this;
        }

        public function inner_join(Table $table) {
            $this->statement->join(Factory::inner_join(
                                       Factory::alias($table->get_table()),
                                       null));
            return $this;
        }

        public function outer_join(Table $table) {
            $this->statement->join(Factory::outer_join(
                                       Factory::alias($table->get_table()),
                                       null));
            return $this;
        }

        public function left_outer_join(Table $table) {
            $this->statement->join(Factory::left_outer_join(
                                       Factory::alias($table->get_table()),
                                       null));
            return $this;
        }

        public function left_join(Table $table) {
            return $this->left_outer_join($table);
        }

        public function right_outer_join(Table $table) {
            $this->statement->join(Factory::right_outer_join(
                                       Factory::alias($table->get_table()),
                                       null));
            return $this;
        }

        public function right_join(Table $table) {
            return $this->right_outer_join($table);
        }

        public function on(Node $node) {
            $this->statement->get_last_join()->on(Factory::on($node));
            return $this;
        }

        public function group_by() {
            $statement = $this->statement;
            array_map(function($arg) use (&$statement) {
                    if (is_string($arg)) {
                        $arg = Factory::quoted($arg);
                    }
                    $statement->group_by($arg);
                }, func_get_args());

            return $this;
        }

        public function having() {
            $statement = $this->statement;
            array_map(function($having) use (&$statement) {
                    if (!($having instanceof Node)) {
                        throw new WrongHavingException;
                    }
                    $statement->having($having);
                }, func_get_args());

            return $this;
        }

        public function order_by() {
            $statement = $this->statement;
            array_map(function($order) use (&$statement) {
                    if (!is_array($order)) {
                        $order = array($order, true);
                    }
                    if (count($order) != 2) {
                        throw new WrongOrderDataException;
                    }
                    if (is_string($order[0])) {
                        $order[0] = Factory::quoted($order[0]);
                    }
                    $statement->order_by(Factory::order($order[0], $order[1]));
                }, func_get_args());

            return $this;
        }

        public function offset($offset) {
            if (!is_int($offset)) {
                throw new WrongOffsetException;
            }
            $offset = (int)$offset;
            if ($offset < 0) {
                throw new WrongOffsetException;
            }
            $this->statement->offset(Factory::sql($offset));
            return $this;
        }
    }
