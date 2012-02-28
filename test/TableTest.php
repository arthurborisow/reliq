<?php

    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' .
                 DIRECTORY_SEPARATOR . 'reliq'
                 . DIRECTORY_SEPARATOR
                 . 'autoload.php';

    class TableTest extends PHPUnit_Framework_TestCase {
        /**
         * @var Table
         */
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
        }

        /**
         * @expectedException Reliq\Exceptions\NoSqlDriverException
         */
        public function testNoAdapterException() {
            new \Reliq\Table('name', array());
        }

        /**
         * @expectedException Reliq\Exceptions\NoColumnException
         */
        public function testNoColumnException() {
            $this->table->non_existing_column;
        }

        public function testDeleteManager() {
            $this->assertEquals('Reliq\Managers\DeleteManager',
                                get_class($this->table->delete()));
        }

        public function testUpdateManager() {
            $this->assertEquals('Reliq\Managers\UpdateManager',
                                get_class($this->table->update()));
        }

        public function testInsertManager() {
            $this->assertEquals('Reliq\Managers\InsertManager',
                                get_class($this->table->insert()));
        }

        public function testExistingColumn() {
            $c = $this->table->email;
            $this->assertEquals('Reliq\Nodes\QuotedNode', get_class($c));
        }

        public function testChaining() {
            $node = $this->table->email->eq('test')->and_x
            ($this->table->name->eq('test@test'));

            $this->assertEquals('test@test', $node->right()->right
                                           ()->value());
        }

        public function testCount() {
            $node = $this->table->email->count();
            $this->assertEquals('test.email', $node->right()->value());
        }

        public function testCountChain() {
            $node = $this->table->email->count()->eq('test');
            $this->assertEquals('test', $node->right()->value());
        }

        public function testMax() {
            $node = $this->table->email->max();
            $this->assertEquals('test.email', $node->right()->value());
        }

        public function testMin() {
            $node = $this->table->email->min();
            $this->assertEquals('test.email', $node->right()->value());
        }

        public function testSum() {
            $node = $this->table->email->sum();
            $this->assertEquals('test.email', $node->right()->value());
        }

        public function testAvg() {
            $node = $this->table->email->avg();
            $this->assertEquals('test.email', $node->right()->value());
        }

        public function testWhere() {
            $manager = $this->table->where($this->table->name->eq('hello'));
            $this->assertEquals('Reliq\Managers\SelectManager',
                                get_class($manager));
        }

        public function testProjections() {
            $manager = $this->table->projections('*');
            $this->assertEquals('Reliq\Managers\SelectManager',
                                get_class($manager));
        }

        public function testAlias() {
            $table2 = $this->table->alias('test2');
            $this->assertEquals('test2', $table2->get_name());
            $name = $table2->get_table();
            $this->assertEquals('test', $name[0]);
            $this->assertEquals('test2', $name[1]);
        }

        public function testAll() {
            $node = $this->table->all();
            $this->assertEquals('Reliq\Nodes\QuotedNode', get_class($node));
        }

    }

?>
