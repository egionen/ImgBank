<?php
class Application_Model_Img extends Zend_Db_Table
{
    protected $_name = 'img';

    public function gravar($dados){

        $row = $this->createRow();
        $row->setFromArray($dados);
        if($row->save()){

            $_SESSION['mensagem'] = "<script>$.Notify({
                    caption: 'Sucesso',
                    content: 'Upload feito com sucesso',
                    type: 'success'
                    });</script>";

        }



    }

}
