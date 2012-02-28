<?php

    namespace Reliq\Managers;

    use Reliq\Table;
    use Reliq\Factory;

    class DeleteManager extends Manager {
        public function __construct(Table $table) {
            $this->statement = Factory::delete_query($table);
        }

    }
