<?php

    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' .
                 DIRECTORY_SEPARATOR . 'reliq'
                 . DIRECTORY_SEPARATOR
                 . 'autoload.php';

    /**
     * Test class for Factory.
     * Generated by PHPUnit on 2011-08-12 at 13:40:13.
     */
    class FactoryTest extends PHPUnit_Framework_TestCase {


        public function test__callStatic() {
            $node = Reliq\Factory::eq(new Reliq\Nodes\SqlNode('t'), 'tt');
            $this->assertEquals('Reliq\Nodes\EqNode', get_class($node));
        }

        /**
         * @expectedException Reliq\Exceptions\UnsupportedNodeException
         */
        public function testException() {
            Reliq\Factory::non_existing_node();
        }
    }

?>
