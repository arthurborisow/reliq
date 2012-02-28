<?php

    namespace Reliq\Connections\Drivers;

    class MysqlDriver extends Driver {

        public function connect() {
            return call_user_func_array('mysqli_connect', $this->config);
        }

        protected function prepare_config(array $config) {
            foreach(array('host', 'username', 'database') as $key) {
                if(!array_key_exists($key, $config)) {
                    return false;
                }
            }

            $options = array(
                'host' => $config['host'],
                'username' => $config['username'],
                'password' => array_key_exists('password',
                                               $config) ? $config['password']
                        : '',
                'database' => $config['database']
            );

            if(array_key_exists('port', $config)) {
                $options['port'] = $config['port'];
            }

            if(array_key_exists('socket', $config)) {
                if(!array_key_exists('port', $config)) {
                    $options['port'] = 0;
                }

                $options['socket'] = $config['socket'];
            }

            return $options;
        }


        protected function execute_query($query, $connection_link) {
            return mysqli_query($connection_link, $query);
        }

        protected function free_result($result) {
            mysqli_free_result($result);
        }
    }
