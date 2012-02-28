<?php

    namespace Reliq\Connections;

    use Reliq\Connections\Drivers;

    class Connection {

        private static $instances = array();
        public static function instance($name = '', array $config = array()) {
            if(!$name) {
                return array_slice(static::$instances, 0, 1);
            }

            if($name && array_key_exists($name, static::$instances)) {
                return static::$instances[$name];
            }

            static::$instances[$name] = static::create_driver($name,
                                                              $config);

            return static::$instances[$name];
        }

        private static function create_driver($name, $config) {
            $class_name = 'Reliq\Connections\Drivers\\' . $name;
            return new $class_name($config);
        }

    }