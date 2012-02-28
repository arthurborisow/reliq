<?php

    namespace Reliq\Nodes;

    use Reliq\Factory;
    
    class AliasNode extends Node {
        public function __construct($names) {
            parent::__construct(Factory::quoted($names[0]),
                                Factory::quoted($names[1]));
        }
    }
