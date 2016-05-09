<?php
class Application_Model_User extends Zend_Db_Table
{
    protected $_name = 'user';

    public function gravar($dados){

      $row = $this->createRow();
      $row->setFromArray($dados);
      $row->save();

    }

}
