<?php

    namespace Reliq\Connections\Drivers;

    use Reliq\Exceptions;

    abstract class Driver {
        protected $config = array();
        private $connection_link = null;

        private $last_result = null;

        public function __construct(array $config) {
            $this->config = $this->prepare_config($config);
            if (!$this->config) {
                throw new \WrongDbDriverConfigException;
            }
        }

        public function execute($query) {
            $this->last_result =
                    $this->execute_query($query, $this->connection_link());
        }

        public function free($result = null) {
            $this->free_result($result === null ? $this->last_result : $result);
        }

        protected function connection_link() {
            if ($this->connection_link === null) {
                $this->connection_link = $this->connect();
            }
            return $this->connection_link;
        }

        public abstract function connect();

        protected abstract function prepare_config(array $config);

        protected abstract function execute_query($query, $connection_link);

        protected abstract function free_result($result);
    }
