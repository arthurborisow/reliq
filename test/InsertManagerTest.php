<?php

    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' .
                 DIRECTORY_SEPARATOR . 'reliq'
                 . DIRECTORY_SEPARATOR
                 . 'autoload.php';

    class InsertManagerTest extends PHPUnit_Framework_TestCase {
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
            $this->manager = \Reliq\Managers\InsertManager::factory($this->table);

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

        public function testSimpleInsert() {
            $pattern = $this
                    ->prepare_regex('INSERT INTO `test` (`test`.`name`) '
                                         . 'VALUES (name)');
            $sql = $this->manager
                    ->values($this->table->name->set('name'))
                    ->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        public function testSimpleInsert2() {
            $pattern = $this
             ->prepare_regex('INSERT INTO `test` (`test`.`name`, `test`.`password`) '
                              . 'VALUES (name, ***)');
            $sql = $this->manager
                    ->values($this->table->name->set('name'),
                          $this->table->password->set('***'))
                    ->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        public function testSimpleInsert3() {
            $pattern = $this
                ->prepare_regex('INSERT INTO `test` (`test`.`name`, `test`.`password`) '
                              . 'VALUES (name, ***)');
            $sql = $this->manager
                    ->values($this->table->name->set('name'))
                    ->values($this->table->password->set('***'))
                    ->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        /**
         * @expectedException Reliq\Exceptions\WrongColumnsForInsertException
         */
        public function testWrongColumnsForInsertException() {
            $this->manager
                    ->values('test')
                    ->values($this->table->password->set('***'))
                    ->to_sql();
        }

        /**
         * @expectedException BadFunctionCallException
         */
        public function testWhere() {
            $this->manager
                    ->where('test')
                    ->to_sql();
        }

        public function testAlias() {
            $table2 = $this->table->alias('test_2');
            $manager = \Reliq\Managers\InsertManager::factory($table2);

            $sql = $manager->to_sql();
            $pattern = $this
                    ->prepare_regex('INSERT INTO `test` `test_2` () VALUES ()');

            $this->assertRegExp($pattern, $sql);
        }
    }

?>
