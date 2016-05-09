<?php

class PerfilController extends Zend_Controller_Action
{
  public function cadastroAction()
  {
    if(!empty($_SESSION['user']))
    $this->redirect('index');


  }
  public function verificaruserAction(){

    $this->_helper->layout->disableLayout();
    $dados = $this->_getAllParams();
    $modelUser = new Application_Model_User();
    $row = $modelUser->fetchRow("user = '{$dados['user']}'");
    if($row){
      echo "<span class='mif-warning fg-white'>   Este usuário já existe</span>";
    }else{
      echo "<span class='mif-checkmark fg-white'>   Usuário disponivel</span>";

    }
  }
  public function verificaremailAction(){

    $this->_helper->layout->disableLayout();
    $dados = $this->_getAllParams();
    $modelUser = new Application_Model_User();
    $row = $modelUser->fetchRow("user = '{$dados['email']}'");
    if($row){
      echo "<span class='mif-warning fg-white'>   Este email já existe</span>";
    }else{
      echo "<span class='mif-checkmark fg-white'>   Email disponivel</span>";

    }
  }
  public function cadastrarAction(){
    $dados = $this->_getAllParams();
    $modelUser = new Application_Model_User();
    $modelUser->gravar($dados);
    $row = $modelUser->fetchRow('user = "'. $dados['user'] .'" and pass = "'.$dados['pass'].'"');
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
  }
}
