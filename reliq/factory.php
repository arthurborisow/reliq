<?php

    namespace Reliq;

    use Reliq\Exceptions as exceptions;

    class Factory {
        private static $allowed_nodes = array('alias', 'and_x', 'as_x',
                                              'avg', 'brackets', 'count',
                                              'cross_join', 'delete_query',
                                              'eq', 'func', 'gt', 'gte',
                                              'having', 'in', 'inner_join',
                                              'insert_query',
                                              'is', 'is_not', 'join',
                                              'left_outer_join', 'like',
                                              'lt', 'lte', 'max', 'min',
                                              'natural_join', 'not_eq',
                                              'not_in', 'not_like', 'on',
                                              'or_x', 'order', 'outer_join',
                                              'quoted', 'right_outer_join',
                                              'select_query', 'sql', 'set',
                                              'sum', 'update_query');

        public static function __callStatic($name, $args) {
            if (!in_array($name, static::$allowed_nodes)) {
                throw
                new exceptions\UnsupportedNodeException(static::$allowed_nodes);
            }

            $class_name = explode('_', $name);
            $class_name = array_map(function($partial) {
                    return ucfirst($partial);
                }, $class_name);
            $class_name[] = 'Node';
            $class_name = 'Reliq\\Nodes\\' . implode($class_name);
            $reflector = new \ReflectionClass($class_name);
            return $reflector->newInstanceArgs($args);
        }
    }
