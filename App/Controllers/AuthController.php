<?php
    namespace App\Controllers;

    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;

    class AuthController extends Action{

         public function autenticar(){
               //  echo 'chegamos até aqui';

               // echo '<pre>';
               // print_r($_POST);
               // echo '</pre>';
               $usuario = Container::getModel('Usuario');

               $usuario->__set('email', $_POST['email']);
               $usuario->__set('senha', md5($_POST['senha']));
               

               // echo '<pre>';
               // print_r($usuario);
               // echo '</pre>';

               $retorno = $usuario->autenticar();

               if($usuario->__get('id') != '' && $usuario->__get('nome') != '') {
               
                    session_start();
                    $_SESSION['id'] = $usuario->__get('id');
                    $_SESSION['nome'] = $usuario->__get('nome');
                    
                    header('Location: /timeline');
                    // echo 'Usuário autenticado!';
               } else {
                    //echo 'Erro na Autenticação';
                    header('Location:/?login=erro');
               }

               // echo '<pre>';
               // print_r($retorno);
               // echo '</pre>';
               
               // echo '<pre>';
               // print_r($usuario);
               // echo '</pre>';
          }

          public function sair(){
               session_start();
               session_destroy();
               header('Location: /');
          }    
 
    }
?>