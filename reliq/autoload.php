<?php

    spl_autoload_register(function($class_name) {
            $class_name = explode('\\', $class_name);
            $package = array_shift($class_name);
            
            if ($package != 'Reliq') {
                return false;
            }

            $name = array_pop($class_name);

            $name = preg_replace('/(?<!^)([A-Z])([a-z\d]*)/', '_$1$2',
                                 $name);

            $class_name[] = $name . '.php';
            $class_path = dirname(__FILE__) . DIRECTORY_SEPARATOR
                          . strtolower(implode(DIRECTORY_SEPARATOR,
                                               $class_name));
            if(file_exists($class_path)) {
                require_once $class_path;
            } else {
                return false;
            }
        });