<?php

    namespace Reliq\Managers;

    use Reliq\Nodes\Node;
    use Reliq\Exceptions\WrongNodeTypeException;
    use Reliq\Exceptions\WrongLimitException;

    use Reliq\Visitors\SqlVisitor;

    use Reliq\Factory;

    class Manager {
        protected $statement = null;

        public static function factory($table) {
            $manager = new static($table);
            return $manager;
        }

        public function where() {
            $arguments = func_get_args();
            foreach ($arguments as $argument) {
                if (!($argument instanceof Node)) {
                    throw new WrongNodeTypeException;
                }
                $this->statement->where($argument);
            }
            return $this;
        }

        public function limit($limit) {
            if(!is_int($limit)) {
                throw new WrongLimitException;
            }
            $limit = (int)$limit;
            if ($limit < 0) {
                throw new WrongLimitException;
            }
            $this->statement->limit(Factory::sql($limit));
            return $this;
        }

        public function to_sql() {
            $visitor = new SqlVisitor;
            return $visitor->visit($this->statement);
        }

    }
