<?php

    namespace Reliq;
    
    use Reliq\Exceptions\NoSqlDriverException;
    use Reliq\Managers\SelectManager;
    use Reliq\Managers\DeleteManager;
    use Reliq\Managers\InsertManager;
    use Reliq\Managers\UpdateManager;

    class Table {

        private $driver = '';
        private $table_name = '';
        private $alias_name = '';
        private $columns = array();

        public function __construct($table_name, $options = array()) {
            $this->table_name = $table_name;

            if (!is_array($options) || !array_key_exists('driver', $options)) {
                throw new NoSqlDriverException;
            }

            $this->driver = $options['driver'];
            if (array_key_exists('columns', $options)
                && is_array($options['columns'])
            ) {
                $this->columns = $options['columns'];
            }
        }

        public function get_name() {
            if($this->alias_name) {
                return $this->alias_name;
            }
            return $this->table_name;
        }

        public function get_table() {
            return array($this->table_name, $this->alias_name);
        }

        public function alias($alias_name) {
            $table = clone $this;
            $table->alias_name = $alias_name;
            return $table;
        }

        private function get_columns() {
            // TODO: Get columns if there are none
            return $this->columns;
        }

        private function column_exists($column) {
            if(in_array($column, $this->get_columns())) {
                return true;
            }
            return false;
        }

        public function all() {
            return Factory::quoted($this->get_name() . '.*');
        }

        public function __get($column) {
            if (!$this->column_exists($column)) {
                throw new exceptions\NoColumnException;
            }

            return Factory::quoted($this->get_name() . '.' . $column);
        }

        public function where() {
            $manager = SelectManager::factory($this);
            return call_user_func_array(array($manager, 'where'),
                                        func_get_args());
        }

        public function projections() {
            $manager = SelectManager::factory($this);
            return call_user_func_array(array($manager, 'projections'),
                                        func_get_args());
        }

        public function delete() {
            return DeleteManager::factory($this);
        }

        public function update() {
            return UpdateManager::factory($this);
        }

        public function insert() {
            return InsertManager::factory($this);
        }
    }
