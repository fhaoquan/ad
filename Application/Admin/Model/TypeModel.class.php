<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class TypeModel extends RelationModel{
    protected $_auto = array(
        array('description', 'htmlspecialchars_decode', self::MODEL_BOTH, 'function'),
    );
}
?>