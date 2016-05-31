<?php

class IndexController extends Zend_Controller_Action
{
  public function indexAction(){

      $modelImg = new Application_Model_Img();      
      $rowset = $modelImg->getAll();
      $this->view->dados = $rowset;  

  }
  

  public function imgAction(){
    $request = $this->getRequest();

    $src = $request->getParam('src');
    $modelImg = new Application_Model_Img();
    $modelUser = new Application_Model_User();
    $row = $modelImg->fetchRow('id_img = '. $src);
    $this->view->rowimg = $row;
    $row = $modelUser->fetchRow('id_user = '. $row['id_user']);
    $this->view->rowuser = $row;
    
  }
  public function logarAction(){
      $dados = $this->_getAllParams();
      $modelUser = new Application_Model_User();
      $row = $modelUser->fetchRow('user = "'. $dados['user'] .'" and pass = "'.$dados['pass'].'"');
     
      if($row){
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['user'] = $row['user'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['nome'] = $row['nome'];
        $_SESSION['pass'] = $row['pass'];
        $_SESSION['avatar'] = $row['avatar'];

        $_SESSION['mensagem'] = "<script>$.Notify({
          caption: 'Logado',
          content: 'Bem Vindo você foi logado com sucesso',
          type: 'success'
        });</script>";
        $this->redirect('index');
      }else{
        $_SESSION['mensagem'] = "<script>$.Notify({
          caption: 'Erro',
          content: 'Usuario ou Senha incorretos',
          type: 'alert'
        });</script>";
        $this->redirect('index');    }
      
      }
  public function unsetAction(){
          session_unset();
          $this->redirect('index');
          die;
      }}
