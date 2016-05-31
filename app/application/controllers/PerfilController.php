    <?php

class PerfilController extends Zend_Controller_Action{
    public function cadastroAction(){
    if(!empty($_SESSION['user']))
    $this->redirect('index');


    }

    public function perfilAction(){
    if($_SESSION['user'] == ""){
      $this->redirect('index');
    }
   



    }
    public function carregaAction(){
        $this->_helper->layout->disableLayout();

    }

    public function verificarAction(){


    $this->_helper->layout->disableLayout();
    $dados = $this->_getAllParams();
    
    $modelUser = new Application_Model_User();
    if(!empty($dados['user'])){
            $row = $modelUser->fetchRow("user = '{$dados['user']}'");    

        if($row){
            echo "<span class='mif-warning fg-white'>   Este usuário já existe</span>";
        }else{
            echo "<span class='mif-checkmark fg-white'>   Usuário disponivel</span>";
        }
    }

    if(!empty($dados['email'])){
        $row = $modelUser->fetchRow("email = '{$dados['email']}'");
        if($row){
            echo "<span class='mif-warning fg-white'>   Este email já existe</span>";
        }else{
            echo "<span class='mif-checkmark fg-white'>   Email disponivel</span>";
        }

    }
    die;
    }

    public function cadastrarAction(){
    $dados = $this->_getAllParams();
    $modelUser = new Application_Model_User();   
    if(!empty($_POST['url'])){
         $ext = $this->ext($_POST['url']);
        copy($_POST['url'], 'upload/avatar/' . $dados['user'].'.'.$ext);
        $avatar = 'upload/avatar/' . $dados['user'].'.'.$ext;
    }else{
        $ext = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);//NOTE: pegar extensão   
        move_uploaded_file($_FILES['file']['tmp_name'], 'upload/avatar/' . $dados['user'] . '.' . $ext);
        $avatar = 'upload/avatar/' . $dados['user'].'.'.$ext;
   }

     
    $avatar = array(
        'avatar' => $avatar,
         );

    echo "<pre>";
    
    $dados = array_replace($dados, $avatar);
    $modelUser->gravar($dados);
    $this->atualizarDados($dados);
    $_SESSION['mensagem'] = "<script>$.Notify({
    caption: 'Logado',
    content: 'Bem Vindo você foi logado com sucesso',
    type: 'success'
    });</script>";
    $this->redirect('index');
    }
    public function editarAction(){

    $dados = $this->_getAllParams();
    $modelImg = new Application_Model_Img();
    $modelUser = new Application_Model_User();
    unset($dados['controller']);
    unset($dados['action']);
    unset($dados['module']);
    
    if($dados['submit'] == "Deletar"){

        $modelImg->delete('id_img ='. $dados['id_img']);
        $_SESSION['mensagem']= "<script>$.Notify({
                                caption: 'Deletado',
                                content: 'Imagem deletada com sucesso',
                                type: 'success'
                                });</script>";      
        $this->redirect('index');  
    }else{
        unset($dados['submit']);
        if(!empty($dados['id_img'])){
        $modelImg->update($dados, "id_img = ". $dados['id_img']);
        $_SESSION['mensagem'] = "<script>$.Notify({
                                caption: 'Alterado',
                                content: 'Nome alterado com sucesso',
                                type: 'success'
                                });</script>";
        $this->redirect('index/img/src/'.$dados['id_img']);
        }else{
            $modelUser->update($dados, "id_user = ".$_SESSION['id_user']);
            $this->atualizarDados($dados);
             $_SESSION['mensagem'] = "<script>$.Notify({
                                caption: 'Alterado',
                                content: 'Campos alterados com sucesso',
                                type: 'success'
                                });</script>";
            $this->redirect('index');
        }    
    }}

    public function avatarAction(){

        $modelUser = new Application_Model_User();


        $dados = array(
            'id_user' => $_SESSION['id_user'],
            'user' => $_SESSION['user'],
            'avatar' => $_SESSION['avatar'],
            );

        if(!empty($_POST['url'])){
            $ext = $this->ext($_POST['url']);
            copy($_POST['url'], 'upload/avatar/' . $dados['user'].'.'.$ext);
            $avatar =  array(
                "avatar" => 'upload/avatar/' . $dados['user'].'.'.$ext,
                );
        }else{
            $ext = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);//NOTE: pegar extensão   
            move_uploaded_file($_FILES['file']['tmp_name'], 'upload/avatar/' . $dados['user'] . '.' . $ext);
            $avatar =  array(
                "avatar" => 'upload/avatar/' . $dados['user'].'.'.$ext,
                );
        }
        $dados = array_replace($dados, $avatar);
        $modelUser->update($dados, 'id_user ='. $dados['id_user']);
        $_SESSION['mensagem'] = "<script>$.Notify({
          caption: 'Sucesso',
          content: 'Avatar alterado com sucesso',
          type: 'success'
        });</script>";
        $this->redirect('index');

        }

    public function atualizarDados($dados){
    $modelUser = new Application_Model_User();
    $row = $modelUser->fetchRow('user = "'. $dados['user'] .'" and pass = "'.$dados['pass'].'"');
    $_SESSION['id_user'] = $row['id_user'];
    $_SESSION['user'] = $row['user'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['nome'] = $row['nome'];
    $_SESSION['pass'] = $row['pass'];
    $_SESSION['avatar'] = $row['avatar'];

    
    }
    public function uploadAction(){
        $modelImg = new Application_Model_Img();
        $dir = 'upload';//NOTE: diretório
        $arq = $dir . basename($_FILES["file"]["name"]);//NOTE: nome do arquivo
        $img = pathinfo($arq,PATHINFO_EXTENSION);//NOTE: pegar extensão
            //NOTE: pegar por url
            if(!empty($_POST['url'])){
                $url = $_POST['url'];
                $img = $this->ext($url);
                    if($img != "jpg" && $img != "png" && $img != "jpeg"
                    && $img != "gif"  ){
                        $_SESSION['mensagem'] = "<script>$.Notify({
                          caption: 'Somente imagens',
                          content: 'Por favor selecione uma imagem',
                          type: 'alert'
                          });</script>";
                            $uploadOk = 0;
                    }else{
                        $target = $this->randomize($img);  
                        $dados = array(
                                    "nome"      =>   $target,
                                    "dir"       =>   "upload/" . $target,
                                    "data"      =>   date("Y-m-d"),
                                    "id_user"   =>   $_SESSION['id_user'],
                                );
                        $modelImg->gravar($dados);
                            if(copy($url,$dados["dir"])){
                                $this->redirect('index');
                            }else{
                                $_SESSION['mensagem'] = "<script>$.Notify({
                                caption: 'Desculpe',
                                content: 'Ocorreu algum erro',
                                type: 'alert'
                                });</script>";;
                                $this->redirect('index');
                            }
                        }
                }
            if($img != "jpg" && $img != "png" && $img != "jpeg"
            && $img != "gif" ) {
                $_SESSION['mensagem'] = "<script>$.Notify({
                caption: 'Somente imagens',
                content: 'Por favor selecione uma imagem',
                type: 'alert'
                });</script>";
            }else{
                $target = $this->randomize($img);
                  $dados = array(
                                    "nome"      =>   $target,
                                    "dir"       =>   "upload/" . $target,
                                    "data"      =>   date("Y-m-d"),
                                    "id_user"   =>   $_SESSION['id_user'],
                                );
                
                    if(move_uploaded_file($_FILES['file']['tmp_name'], $dados["dir"])){
                        $modelImg->gravar($dados);
                        $_SESSION['mensagem'] = "<script>$.Notify({
                        caption: 'Sucesso',
                        content: 'Upload feito com sucesso',
                        type: 'success'
                        });</script>";
                        $_SESSION['img'] = $dados['dir'];
                        $this->redirect('index');
                    }else{
                        $_SESSION['mensagem'] = "<script>$.Notify({
                        caption: 'Desculpe',
                        content: 'Ocorreu algum erro',
                        type: 'alert'
                        });</script>";
                    }
                }
            $this->redirect('index');
            die;
    }

    public function randomize($img){
            
        
        $ran = rand();
        $ran2 = $ran.".";
        $target = $ran2.$img;
        return $target;
    }
    public function ext($url){
        $url = substr($url,-4);
        if(substr($url,0,1) == "."){
            $url = str_replace(".","",$url);
        }
        return $url;
    }
    public function eraseAction(){
        $dados = $this->_getAllParams();
        
        if($dados['pass'] == $_SESSION['pass']){
      
            $modelUser = new Application_Model_User();
            $dadosDelete = array(
                "nome" => $_SESSION['nome'],
                "user" => $_SESSION['user'],
                "pass" => "",
                "ativa" => "nao",
                );
            
            if($modelUser->update($dadosDelete, 'id_user =' . $_SESSION['id_user'])){
                        session_unset();
                        $_SESSION['mensagem'] = "<script>$.Notify({
                        caption: 'Sucesso',
                        content: 'Usuario Deletado',
                        type: 'success'
                        });</script>";
                        $this->redirect('index');
                    }
        }else{
        session_unset();
        $_SESSION['mensagem'] = "<script>$.Notify({
                        caption: 'Erro',
                        content: 'Senha Errada',
                        type: 'alert'
                        });</script>";
            $this->redirect('index');
            
            
        }


    }

}
