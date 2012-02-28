<?php

    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' .
                 DIRECTORY_SEPARATOR . 'reliq'
                 . DIRECTORY_SEPARATOR
                 . 'autoload.php';

    class SelectManagerTest extends PHPUnit_Framework_TestCase {
        /**
         * @var SelectManager
         */
        protected $manager;
        protected $table;
        protected $table2;

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
            $this->table2 = new \Reliq\Table('test_2', array(
                                                  'driver' => 'mysql',
                                                  'columns' => array(
                                                      'name',
                                                      'email',
                                                      'password'
                                                  )
                                             ));
            $this->manager = \Reliq\Managers\SelectManager::factory($this->table);

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

        public function testProjections() {
            $pattern = $this->prepare_regex('SELECT * FROM `test`');
            $sql = $this->manager->projections('*')->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        public function testProjections2() {
            $pattern = $this->prepare_regex('SELECT `test`.`name`, '
                                            . '`test`.`email` FROM `test`');
            $sql = $this->manager->projections($this->table->name,
                                               $this->table->email)
                    ->to_sql();
            $this->assertRegExp($pattern, $sql);
        }

        public function testJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                                            . 'FROM `test` JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testNaturalJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->natural_join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                                       . 'FROM `test` NATURAL JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testCrossJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->cross_join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                                       . 'FROM `test` CROSS JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testInnerJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->inner_join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                        . 'FROM `test` INNER JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testOuterJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->outer_join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                                          . 'FROM `test` OUTER JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testLeftOuterJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->left_outer_join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                            . 'FROM `test` LEFT OUTER JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testRightOuterJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->right_outer_join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                            . 'FROM `test` RIGHT OUTER JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testLeftJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->left_join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                            . 'FROM `test` LEFT OUTER JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testRightJoin() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->right_join($this->table2)
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                            . 'FROM `test` RIGHT OUTER JOIN `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testOn() {
            $sql = $this->manager
                    ->projections($this->table->name,
                                  $this->table2->name)
                    ->join($this->table2)
                    ->on($this->table->name->eq($this->table2->name))
                    ->to_sql();

            $pattern = $this
                    ->prepare_regex('SELECT `test`.`name`, `test_2`.`name` '
                            . 'FROM `test` JOIN `test_2` ON '
                            . '`test`.`name` = `test_2`.`name`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testGroupBy() {
            $sql = $this->manager
                    ->group_by($this->table->name)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` GROUP BY `test`.`name`');
            $this->assertRegExp($pattern, $sql);
        }

        public function testGroupBy2() {
            $sql = $this->manager
                    ->group_by($this->table->name, $this->table->email)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` GROUP BY `test`.`name`, '
                                    . '`test`.`email`');
            $this->assertRegExp($pattern, $sql);
        }

        public function testGroupBy3() {
            $sql = $this->manager
                    ->group_by($this->table->name)
                    ->group_by($this->table->email)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` GROUP BY `test`.`name`, '
                                    . '`test`.`email`');
            $this->assertRegExp($pattern, $sql);
        }

        public function testGroupBy4() {
            $sql = $this->manager
                    ->group_by('t')
                    ->group_by($this->table->email)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` GROUP BY `t`, '
                                    . '`test`.`email`');
            $this->assertRegExp($pattern, $sql);
        }

        public function testHaving() {
            $sql = $this->manager
                    ->having($this->table->name->count()->gt(1))
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` HAVING '
                                    . 'COUNT(`test`.`name`) > 1');
            $this->assertRegExp($pattern, $sql);
        }

        /**
         * @expectedException Reliq\Exceptions\WrongHavingException
         */
        public function testHaving2() {
            $this->manager
                    ->having('t')
                    ->to_sql();
        }

        public function testOrderBy() {
            $sql = $this->manager
                    ->order_by($this->table->name)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` ORDER BY `test`.`name` ASC');
            $this->assertRegExp($pattern, $sql);
        }

        public function testOrderBy2() {
            $sql = $this->manager
                    ->order_by($this->table->name, $this->table->email)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` ORDER BY `test`.`name` ASC, '
                                    . '`test`.`email` ASC');
            $this->assertRegExp($pattern, $sql);
        }

        public function testOrderBy3() {
            $sql = $this->manager
                    ->order_by($this->table->name)
                    ->order_by(array($this->table->email, false))
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` ORDER BY `test`.`name` ASC, '
                                    . '`test`.`email` DESC');
            $this->assertRegExp($pattern, $sql);
        }

        /**
         * @expectedException Reliq\Exceptions\WrongOrderDataException
         */
        public function testOrderBy4() {
            $this->manager
                    ->order_by(array($this->table->email, false, true))
                    ->to_sql();
        }

        public function testOrderBy5() {
            $sql = $this->manager
                    ->order_by($this->table->name)
                    ->order_by(array('test.email', false))
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` ORDER BY `test`.`name` ASC, '
                                    . '`test`.`email` DESC');
            $this->assertRegExp($pattern, $sql);
        }

        public function testOffset() {
            $sql = $this->manager
                    ->offset(20)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` OFFSET 20');
            $this->assertRegExp($pattern, $sql);
        }

        /**
         * @expectedException \Reliq\Exceptions\WrongOffsetException
         */
        public function testOffset2() {
            $this->manager
                    ->offset('10')
                    ->to_sql();
        }

        /**
         * @expectedException \Reliq\Exceptions\WrongOffsetException
         */
        public function testOffset3() {
            $this->manager
                    ->offset(-1)
                    ->to_sql();
        }

        public function testLimit() {
            $sql = $this->manager
                    ->limit(10)
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` LIMIT 10');
            $this->assertRegExp($pattern, $sql);
        }

        /**
         * @expectedException \Reliq\Exceptions\WrongLimitException
         */
        public function testLimit2() {
            $this->manager
                    ->limit('10')
                    ->to_sql();
        }

        public function testWhere() {
            $sql = $this->manager
                    ->where($this->table->name->eq('10'))
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` WHERE `test`.`name` = 10');
            $this->assertRegExp($pattern, $sql);
        }

        public function testBrackets() {
            $sql = $this->manager
                    ->where(\Reliq\Factory::brackets($this->table->name->eq('10')
                                  ->and_x($this->table->password->eq('***'))))
                    ->to_sql();
            $pattern = $this
                    ->prepare_regex('SELECT FROM `test` WHERE (`test`.`name` = 10 '
                        . 'AND `test`.`password` = ***)');
            $this->assertRegExp($pattern, $sql);
        }

        public function testAlias() {
            $table2 = $this->table->alias('test_2');
            $manager = \Reliq\Managers\SelectManager::factory($table2);

            $sql = $manager->projections($table2->name)->to_sql();
            $pattern = $this->prepare_regex('SELECT `test_2`.`name` '
                                            . 'FROM `test` `test_2`');

            $this->assertRegExp($pattern, $sql);
        }

        public function testJoinAlias() {
            $table2 = $this->table->alias('table_2');
            $sql = $this->manager
                    ->projections($this->table->name, $table2->name)
                    ->join($table2)
                    ->on($this->table->name->eq($table2->name))
                    ->to_sql();
            
            $pattern = $this->prepare_regex('SELECT `test`.`name`, `table_2`.`name` '
                       . 'FROM `test` JOIN `test` `table_2` '
                       . 'ON `test`.`name` = `table_2`.`name`');

            $this->assertRegExp($pattern, $sql);
        }

    }

?>
