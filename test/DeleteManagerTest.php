<?php

    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' .
                 DIRECTORY_SEPARATOR . 'reliq'
                 . DIRECTORY_SEPARATOR
                 . 'autoload.php';

    class DeleteManagerTest extends PHPUnit_Framework_TestCase {
        /**
         * @var SelectManager
         */
        protected $manager;
        protected $table;

        /**
         * Sets up the fixture, for example, opens a network connections.
         * This method is called before a test is executed.
         */
        protected function setUp() {
            $this->table = new \Reliq\Table('test', array(
                                                  'driver' => 'mysql',
                                                  'columns' => array(
                                                      'name',
                                                      'email',
                                                      'password'
                                                  )
                                             ));
            $this->manager = \Reliq\Managers\DeleteManager::factory
            ($this->table);

        }

        private function prepare_regex($regex) {
            return '/' . str_replace(array(
                                          ' ',
                                          '*',
                                          '.',
                                          '(',
                                          ')'
                                     ), array(
                                           ' +',
                                           '\*',
                                           '\.',
                                           '\(',
                                           '\)'
                                        ), $regex) . '/';
        }

        public function testSimpleDelete() {
            $pattern = $this->prepare_regex('DELETE FROM `test`');
            $sql = $this->manager->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        public function testLimit() {
            $sql = $this->manager
                    ->limit(10)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('DELETE FROM `test` LIMIT 10');
            $this->assertRegExp($pattern, $sql);
        }

        public function testWhere() {
            $sql = $this->manager
                    ->where($this->table->name->eq('10'))
                    ->to_sql();
            $pattern = $this
                ->prepare_regex('DELETE FROM `test` WHERE `test`.`name` = 10');
            $this->assertRegExp($pattern, $sql);
        }

        public function testBrackets() {
            $sql = $this->manager
                    ->where(\Reliq\Factory::brackets($this->table->name->eq
                                                     ('10')
                                  ->and_x($this->table->password->eq('***'))))
                    ->to_sql();
            $pattern = $this
               ->prepare_regex('DELETE FROM `test` WHERE (`test`.`name` = 10 '
                        . 'AND `test`.`password` = ***)');
            $this->assertRegExp($pattern, $sql);
        }

        public function testAlias() {
            $table2 = $this->table->alias('test_2');
            $manager = \Reliq\Managers\DeleteManager::factory($table2);

            $sql = $manager->to_sql();
            $pattern = $this->prepare_regex('DELETE '
                                            . 'FROM `test` `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testAliasWithWhere() {
            $table2 = $this->table->alias('test_2');
            $manager = \Reliq\Managers\DeleteManager::factory($table2)
                        ->where($table2->name->gt(10));

            $sql = $manager->to_sql();
            $pattern = $this->prepare_regex('DELETE '
                                            . 'FROM `test` `test_2` WHERE '
                                            . '`test_2`.`name` > 10');

            $this->assertRegExp($pattern, $sql);
        }

    }

?>
