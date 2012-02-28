<?php

    namespace Reliq\Managers;

    use Reliq\Table;
    use Reliq\Factory;

    use Reliq\Exceptions\WrongColumnsForInsertException;

    use Reliq\Nodes\Node;

    class InsertManager extends Manager {
        public function __construct(Table $table) {
            $this->statement = Factory::insert_query($table);
        }

        public function values() {
            $arguments = func_get_args();
            foreach ($arguments as $arg) {
                if (!($arg instanceof Node)) {
                    throw new WrongColumnsForInsertException;
                }
            }
            $this->statement->columns($arguments);
            return $this;
        }

        public function where() {
            throw new \BadFunctionCallException;
        }

    }
