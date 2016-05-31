<?php
class Application_Model_User extends Zend_Db_Table
{
    protected $_name = 'user';

    public function gravar($dados){

      $row = $this->createRow();
      if($row->setFromArray($dados)){
      	$_SESSION['mensagem'] = "<script>$.Notify({
    	caption: 'Opa!',
    	content: 'Houve algum erro com o cadastro',
    	type: 'danger'
    	});</script>";

      }
      $row->save();

    }

}
