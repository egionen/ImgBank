<?php

class IndexController extends Zend_Controller_Action
{


    public function indexAction()
    {



    }
    public function logarAction()
    {
      $dados = $this->_getAllParams();

      $modelUser = new Application_Model_User();
      $row = $modelUser->fetchRow('user = "'. $dados['user'] .'" and pass = "'.$dados['pass'].'"');
      if($row){

        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['user'] = $row['user'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['nome'] = $row['nome'];
        $_SESSION['mensagem'] = "<script>$.Notify({
                                                      caption: 'Logado',
                                                      content: 'Bem Vindo você foi logado com sucesso',
                                                      type: 'success'
                                                    });</script>";

        $this->redirect('index');


      }else{




      }




    }
    public function unsetAction(){

    }
}
