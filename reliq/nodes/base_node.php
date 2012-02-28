<?php

    namespace Reliq\Nodes;

    use Reliq\Factory;

    abstract class BaseNode extends Node {
        public function as_x($string) {
            return Factory::as_x($this, $this->make_sql_node($string));
        }

        public function eq($right) {
            return Factory::eq($this, $this->make_sql_node($right));
        }

        public function func($name) {
            $arguments = func_get_args();
            array_shift($arguments);
            array_map(function(&$arg) {
                    if (!($arg instanceof Node)) {
                        $arg = Factory::sql($arg);
                    }
                }, $arguments);

            return Factory::node($name, $arguments);
        }

        public function in() {
            $arguments = func_get_args();
            $arguments = array_map(function($arg) {
                    if (!($arg instanceof Node)) {
                        $arg = Factory::sql($arg);
                    }
                    return $arg;
                }, $arguments);

            return Factory::in($this, $arguments);
        }

        public function not_in() {
            $arguments = func_get_args();
            $arguments = array_map(function($arg) {
                    if (!($arg instanceof Node)) {
                        $arg = Factory::sql($arg);
                    }
                    return $arg;
                }, $arguments);

            return Factory::not_in($this, $arguments);
        }

        public function not_eq($right) {
            return Factory::not_eq($this, $this->make_sql_node($right));
        }

        public function like($right) {
            return Factory::like($this, $this->make_sql_node($right));
        }

        public function not_like($right) {
            return Factory::not_like($this, $this->make_sql_node($right));
        }

        public function is($right) {
            return Factory::is($this, $this->make_sql_node($right));
        }

        public function is_not($right) {
            return Factory::is_not($this, $this->make_sql_node($right));
        }

        public function count() {
            return Factory::count($this);
        }

        public function sum() {
            return Factory::sum($this);
        }

        public function max() {
            return Factory::max($this);
        }

        public function min() {
            return Factory::min($this);
        }

        public function avg() {
            return Factory::avg($this);
        }

        public function gt($other) {
            return Factory::gt($this, $this->make_sql_node($other));
        }

        public function gte($other) {
            return Factory::gte($this, $this->make_sql_node($other));
        }

        public function lt($other) {
            return Factory::lt($this, $this->make_sql_node($other));
        }

        public function lte($other) {
            return Factory::lte($this, $this->make_sql_node($other));
        }

        public function set($other) {
            return Factory::set($this, $this->make_sql_node($other));
        }

        private function make_sql_node($string) {
            if (!($string instanceof Node)) {
                $string = Factory::sql($string);
            }

            return $string;
        }
    }
