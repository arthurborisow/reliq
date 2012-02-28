<?php

    namespace Reliq\Visitors;

    use Reliq\Nodes;

    class SqlVisitor extends Visitor {
        public function visit_AndXNode(Nodes\AndXNode $node) {
            return $this->binary_node($node, 'AND');
        }

        public function visit_AliasNode(Nodes\AliasNode $node) {
            $return = array($this->visit($node->left()));
            $right = $this->visit($node->right());
            if ($right) {
                $return[] = $right;
            }
            return join(' ', $return);
        }

        public function visit_AsXNode(Nodes\AsXNode $node) {
            return $this->binary_node($node, 'AS');
        }

        public function visit_BracketsNode(Nodes\BracketsNode $node) {
            return '(' . $this->visit($node->right()) . ')';
        }

        public function visit_EqNode(Nodes\EqNode $node) {
            return $this->binary_node($node, '=');
        }

        public function visit_FuncNode(Nodes\FuncNode $node) {
            $temp = array();
            $self = $this;
            array_map(function($arg) use (&$temp, $self) {
                    $temp[] = $self->visit($arg);
                }, $node->get_args());

            return $node->get_name() . '(' . join(', ', $temp) . ')';
        }

        public function visit_HavingNode(Nodes\HavingNode $node) {
            return $this->visit($node->right());
        }

        public function visit_InNode(Nodes\InNode $node) {
            $return = array();
            $return[] = $this->visit($node->left());
            $return[] = 'IN(';

            $temp = array();
            $self = $this;
            array_map(function($in) use (&$temp, $self) {
                    $temp[] = $self->visit($in);
                }, $node->get_ins());
            $return[] = join(', ', $temp);
            $return[] = ')';

            return join(' ', $return);
        }

        public function visit_IsNode(Nodes\IsNode $node) {
            return $this->binary_node($node, 'IS');
        }

        public function visit_IsNotNode(Nodes\IsNotNode $node) {
            return $this->binary_node($node, 'IS NOT');
        }

        public function visit_JoinNode(Nodes\JoinNode $node) {
            return $this->visit_joins('JOIN', $node);
        }

        public function visit_NaturalJoinNode(Nodes\NaturalJoinNode $node) {
            return $this->visit_joins('NATURAL JOIN', $node);
        }

        public function visit_CrossJoinNode(Nodes\CrossJoinNode $node) {
            return $this->visit_joins('CROSS JOIN', $node);
        }

        public function visit_LeftOuterJoinNode(Nodes\LeftOuterJoinNode $node) {
            return $this->visit_joins('LEFT OUTER JOIN', $node);
        }

        public function visit_RightOuterJoinNode(Nodes\RightOuterJoinNode $node) {
            return $this->visit_joins('RIGHT OUTER JOIN', $node);
        }

        public function visit_InnerJoinNode(Nodes\InnerJoinNode $node) {
            return $this->visit_joins('INNER JOIN', $node);
        }

        public function visit_OuterJoinNode(Nodes\OuterJoinNode $node) {
            return $this->visit_joins('OUTER JOIN', $node);
        }

        public function visit_LikeNode(Nodes\LikeNode $node) {
            return $this->binary_node($node, 'LIKE');
        }

        public function visit_NotEqNode(Nodes\NotEqNode $node) {
            return $this->binary_node($node, '!=');
        }

        public function visit_NotInNode(Nodes\NotInNode $node) {
            $return = array();
            $return[] = $this->visit($node->left());
            $return[] = 'NOT IN(';

            $temp = array();
            $self = $this;
            array_map(function($in) use (&$temp, $self) {
                    $temp[] = $self->visit($in);
                }, $node->get_ins());
            $return[] = join(', ', $temp);
            $return[] = ')';

            return join(' ', $return);
        }

        public function visit_OnNode(Nodes\OnNOde $node) {
            return 'ON ' . $this->visit($node->right());
        }

        public function visit_OrXNode(Nodes\OrXNode $node) {
            return $this->binary_node($node, 'OR');
        }

        public function visit_SqlNode(Nodes\SqlNode $node) {
            return $this->escape($node->value());
        }

        public function visit_OrderNode(Nodes\OrderNode $node) {
            return $this->visit($node->right()) . ' ' . ($node->is_asc() ?
                    'ASC' : 'DESC');
        }

        public function visit_UpdateQueryNode(Nodes\UpdateQueryNode $node) {
            $statement = array();
            $self = $this;
            $statement[] = 'UPDATE';
            $statement[] = $this->visit($node->get_from());

            $statement[] = 'SET';
            $temp = array();
            if ($node->get_columns()) {
                array_map(function($set) use (&$temp, $self) {
                        $temp[] = $self->visit($set);
                    }, $node->get_columns());
                $statement[] = implode(', ', $temp);
                $temp = array();
            }

            if ($node->get_where()) {
                $statement[] = 'WHERE';
                $statement[] = $this->visit($node->get_where());
            }

            if ($node->get_limit()) {
                $statement[] = 'LIMIT';
                $statement[] = $this->visit($node->get_limit());
            }

            return join(' ', $statement);
        }

        public function visit_InsertQueryNode(Nodes\InsertQueryNode $node) {
            $statement = array();
            $self = $this;
            $statement[] = 'INSERT';
            $statement[] = 'INTO';
            $statement[] = $this->visit($node->get_into());


            $columns = array();
            $values = array();

            array_map(function($set) use (&$columns, &$values, $self) {
                    $columns[] = $self->visit($set->left());
                    $values[] = $self->visit($set->right());
                }, $node->get_columns());

            $statement[] = '(' . implode(', ', $columns) . ')';

            $statement[] = 'VALUES';
            $statement[] = '(' . implode(', ', $values) . ')';

            if ($node->get_where()) {
                $statement[] = 'WHERE';
                $statement[] = $this->visit($node->get_where());
            }

            if ($node->get_limit()) {
                $statement[] = 'LIMIT';
                $statement[] = $this->visit($node->get_limit());
            }

            return join(' ', $statement);
        }

        public function visit_DeleteQueryNode(Nodes\DeleteQueryNode $node) {
            $statement = array();
            $self = $this;
            $statement[] = 'DELETE';
            $statement[] = 'FROM';
            $statement[] = $this->visit($node->get_from());

            if ($node->get_where()) {
                $statement[] = 'WHERE';
                $statement[] = $this->visit($node->get_where());
            }

            if ($node->get_limit()) {
                $statement[] = 'LIMIT';
                $statement[] = $this->visit($node->get_limit());
            }

            return join(' ', $statement);
        }

        public function visit_SelectQueryNode(Nodes\SelectQueryNode $node) {
            $statement = array();
            $self = $this;
            $statement[] = 'SELECT';
            $temp = array();
            if ($node->get_projections()) {
                array_map(function($projection) use (&$temp, $self) {
                        $temp[] = $self->visit($projection);
                    }, $node->get_projections());

                $statement[] = join(', ', $temp);
                $temp = array();
            }

            $statement[] = 'FROM';
            $statement[] = $this->visit($node->get_from());

            if ($node->get_joins()) {
                array_map(function($join) use (&$statement, $self) {
                        $statement[] = $self->visit($join);
                    }, $node->get_joins());
            }

            if ($node->get_where()) {
                $statement[] = 'WHERE';
                $statement[] = $this->visit($node->get_where());
            }

            if ($node->get_groups()) {
                $statement[] = 'GROUP BY';
                array_map(function($group) use (&$temp, $self) {
                        $temp[] = $self->visit($group);
                    }, $node->get_groups());
                $statement[] = join(', ', $temp);
                $temp = array();
            }

            if ($node->get_having()) {
                $statement[] = 'HAVING';
                $statement[] = $this->visit($node->get_having());
            }

            if ($node->get_orders()) {
                $statement[] = 'ORDER BY';
                array_map(function($order) use (&$temp, $self) {
                        $temp[] = $self->visit($order);
                    }, $node->get_orders());
                $statement[] = join(', ', $temp);
                $temp = array();
            }

            if ($node->get_offset()) {
                $statement[] = 'OFFSET';
                $statement[] = $this->visit($node->get_offset());
            }

            if ($node->get_limit()) {
                $statement[] = 'LIMIT';
                $statement[] = $this->visit($node->get_limit());
            }

            return join(' ', $statement);
        }

        public function visit_NotLikeNode(Nodes\NotLikeNode $node) {
            return $this->binary_node($node, 'NOT LIKE');
        }

        public function visit_AvgNode(Nodes\AvgNode $node) {
            return 'AVG(' . $this->visit($node->right()) . ')';
        }

        public function visit_CountNode(Nodes\CountNode $node) {
            return 'COUNT(' . $this->visit($node->right()) . ')';
        }

        public function visit_MaxNode(Nodes\MaxNode $node) {
            return 'MAX(' . $this->visit($node->right()) . ')';
        }

        public function visit_MinNode(Nodes\MinNode $node) {
            return 'MIN(' . $this->visit($node->right()) . ')';
        }

        public function visit_SumNode(Nodes\SumNode $node) {
            return 'SUM(' . $this->visit($node->right()) . ')';
        }

        public function visit_GtNode(Nodes\GtNode $node) {
            return $this->binary_node($node, '>');
        }

        public function visit_GteNode(Nodes\GteNode $node) {
            return $this->binary_node($node, '>=');
        }

        public function visit_LtNode(Nodes\LtNode $node) {
            return $this->binary_node($node, '<');
        }

        public function visit_LteNode(Nodes\LteNode $node) {
            return $this->binary_node($node, '<=');
        }

        public function visit_SetNode(Nodes\SetNode $node) {
            return $this->binary_node($node, '=');
        }

        public function visit_QuotedNode(Nodes\QuotedNode $node) {
            if($node->value()) {
                return $this->quote($node->value());
            } else {
                return null;
            }
        }

        protected function quote($value) {
            $value = explode('.', $value);
            $value = array_map(function($v) {
                    return '`' . $v . '`';
                }, $value);

            return implode('.', $value);
        }

        private function escape($value) {
            // TODO: The connections MUST escape the value
            return $value;
        }

        protected function visit_joins($type, Nodes\JoinNode $node) {
            $return = array($type, $this->visit($node->left()));
            if ($node->right()) {
                $return[] = $this->visit($node->right());
            }
            return join(' ', $return);
        }

        protected function binary_node(Nodes\Node $node, $delimiter) {
            return $this->visit($node->left()) . ' ' . $delimiter . ' ' .
                   $this->visit($node->right());
        }
    }
