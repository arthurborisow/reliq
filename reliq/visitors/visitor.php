<?php

    namespace Reliq\Visitors;
    
    use Reliq\Nodes;

    abstract class Visitor {
        private $connection = null;

        public function __construct($connection = null) {
            $this->connection = $connection;
        }

        public function visit($node) {
            $method = 'visit_' . basename(get_class($node));
            return $this->$method($node);
        }

        public abstract function visit_AliasNode(Nodes\AliasNode $node);

        public abstract function visit_AndXNode(Nodes\AndXNode $node);

        public abstract function visit_AsXNode(Nodes\AsXNode $node);

        public abstract function visit_AvgNode(Nodes\AvgNode $node);

        public abstract function visit_BracketsNode(Nodes\BracketsNode $node);

        public abstract function visit_CountNode(Nodes\CountNode $node);

        public abstract function visit_DeleteQueryNode(Nodes\DeleteQueryNode $node);

        public abstract function visit_EqNode(Nodes\EqNode $node);

        public abstract function visit_FuncNode(Nodes\FuncNode $node);

        public abstract function visit_GtNode(Nodes\GtNode $node);

        public abstract function visit_GteNode(Nodes\GteNode $node);

        public abstract function visit_HavingNode(Nodes\HavingNode $node);

        public abstract function visit_InNode(Nodes\InNode $node);

        public abstract function visit_InsertQueryNode(Nodes\InsertQueryNode $node);

        public abstract function visit_IsNode(Nodes\IsNode $node);

        public abstract function visit_IsNotNode(Nodes\IsNotNode $node);

        public abstract function visit_JoinNode(Nodes\JoinNode $node);

        public abstract function visit_NaturalJoinNode(Nodes\NaturalJoinNode $node);

        public abstract function visit_CrossJoinNode(Nodes\CrossJoinNode $node);

        public abstract function visit_LeftOuterJoinNode(Nodes\LeftOuterJoinNode $node);

        public abstract function visit_RightOuterJoinNode(Nodes\RightOuterJoinNode
            $node);

        public abstract function visit_InnerJoinNode(Nodes\InnerJoinNode $node);

        public abstract function visit_OuterJoinNode(Nodes\OuterJoinNode $node);

        public abstract function visit_LikeNode(Nodes\LikeNode $node);

        public abstract function visit_LtNode(Nodes\LtNode $node);

        public abstract function visit_LteNode(Nodes\LteNode $node);

        public abstract function visit_MaxNode(Nodes\MaxNode $node);

        public abstract function visit_MinNode(Nodes\MinNode $node);

        public abstract function visit_NotEqNode(Nodes\NotEqNode $node);

        public abstract function visit_NotInNode(Nodes\NotInNode $node);

        public abstract function visit_NotLikeNode(Nodes\NotLikeNode $node);

        public abstract function visit_OnNode(Nodes\OnNode $node);

        public abstract function visit_OrXNode(Nodes\OrXNode $node);

        public abstract function visit_OrderNode(Nodes\OrderNode $node);

        public abstract function visit_QuotedNode(Nodes\QuotedNode $node);

        public abstract function visit_SelectQueryNode(Nodes\SelectQueryNode $node);

        public abstract function visit_SetNode(Nodes\SetNode $node);

        public abstract function visit_SqlNode(Nodes\SqlNode $node);

        public abstract function visit_SumNode(Nodes\SumNode $node);

        public abstract function visit_UpdateQueryNode(Nodes\UpdateQueryNode $node);
    }
