<?php

    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' .
                 DIRECTORY_SEPARATOR . 'reliq'
                 . DIRECTORY_SEPARATOR
                 . 'autoload.php';

    class UpdateManagerTest extends PHPUnit_Framework_TestCase {
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
            $this->manager = \Reliq\Managers\UpdateManager::factory
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

        public function testSimpleUpdate() {
            $pattern = $this->prepare_regex('UPDATE `test` SET `test`.`name` = name');
            $sql = $this->manager
                    ->set($this->table->name->set('name'))
                    ->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        /**
         * @expectedException Reliq\Exceptions\WrongColumnsForUpdateException
         */
        public function testSimpleUpdateException() {
            $sql = $this->manager
                    ->set('test')
                    ->to_sql();
        }

        public function testSimpleUpdate2() {
            $pattern = $this
                    ->prepare_regex('UPDATE `test` SET `test`.`name` = name, '
                                        . '`test`.`password` = ***');
            $sql = $this->manager
                    ->set($this->table->name->set('name'),
                          $this->table->password->set('***'))
                    ->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        public function testSimpleUpdate3() {
            $pattern = $this
                    ->prepare_regex('UPDATE `test` SET `test`.`name` = name, '
                                        . '`test`.`password` = ***');
            $sql = $this->manager
                    ->set($this->table->name->set('name'))
                    ->set($this->table->password->set('***'))
                    ->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        public function testLimit() {
            $sql = $this->manager
                    ->limit(10)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('UPDATE `test` SET LIMIT 10');
            $this->assertRegExp($pattern, $sql);
        }

        public function testWhere() {
            $sql = $this->manager
                    ->where($this->table->name->eq('10'))
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('UPDATE `test` SET WHERE `test`.`name` = 10');
            $this->assertRegExp($pattern, $sql);
        }

        public function testAlias() {
            $table2 = $this->table->alias('test_2');
            $manager = \Reliq\Managers\UpdateManager::factory($table2);

            $sql = $manager->to_sql();
            $pattern = $this->prepare_regex('UPDATE `test` `test_2` '
                                            . 'SET');

            $this->assertRegExp($pattern, $sql);
        }

        public function testAliasWithWhere() {
            $table2 = $this->table->alias('test_2');
            $manager = \Reliq\Managers\UpdateManager::factory($table2)
                        ->where($table2->name->gt(10));

            $sql = $manager->to_sql();
            $pattern = $this->prepare_regex('UPDATE `test` `test_2` '
                                            . 'SET WHERE '
                                            . '`test_2`.`name` > 10');

            $this->assertRegExp($pattern, $sql);
        }

    }

?>
