<?php

    namespace Reliq\Exceptions;

    class WrongColumnsForInsertException extends \Exception {
        public function __construct() {
            parent::__construct('Wrong format arguments for update statement.
            Must be: $manager->values($table->column1->set(\'value1\'),
            $table->column2->set(\'value2\')');
        }
    }
