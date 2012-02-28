<?php

    namespace Reliq\Managers;

    use Reliq\Table;
    use Reliq\Factory;

    use Reliq\Exceptions\WrongColumnsForUpdateException;

    use Reliq\Nodes\Node;

    class UpdateManager extends Manager {
        public function __construct(Table $table) {
            $this->statement = Factory::update_query($table);
        }

        public function set() {
            $arguments = func_get_args();
            foreach($arguments as $arg) {
                if(!($arg instanceof Node)) {
                    throw new WrongColumnsForUpdateException;
                }
            }
            $this->statement->columns($arguments);
            return $this;
        }

    }
