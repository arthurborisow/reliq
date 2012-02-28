<?php

    namespace Reliq\Exceptions;

    class WrongColumnsForUpdateException extends \Exception {
        public function __construct() {
            parent::__construct('Wrong format arguments for update statement.
            Must be: $manager->set($table->column1->set(\'value1\'),
            $table->column2->set(\'value2\')');
        }
    }