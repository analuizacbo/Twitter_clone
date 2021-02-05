<?php
    namespace App\Controllers;

    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;

    class AppController extends Action{
        
        public function timeline(){
    
                $this->validaAutenticacao();

                //Recuperação dos tweets
                $tweet = Container::getModel('Tweet');

                $tweet->__set('id_usuario', $_SESSION['id']);//retorna apenas os tweets da pessoa logada

                $tweets = $tweet->getAll();

                // echo '<pre>';
                // print_r($tweets);
                // echo '</pre>';

                $this->view->tweets = $tweets;

                // echo 'Chegamos até aqui';

                // echo '<pre>';
                // print_r($_SESSION);
                // echo '</pre>';

                $usuario = Container::getModel('Usuario');
                $usuario->__set('id', $_SESSION['id']);
                
                $this->view->info_usuario = $usuario->getInfoUsuario();
                $this->view->total_tweets = $usuario->getTotalTweets();
                $this->view->total_seguindo = $usuario->getTotalSeguindo();
                $this->view->total_seguidores = $usuario->getTotalSeguidores();

                $this->render('timeline');
         
           
        } 

        public function tweet(){

                $this->validaAutenticacao();

                // echo '<pre>';
                // print_r($_POST);
                // echo '</pre>';

                $tweet = Container::getModel('Tweet');

                $tweet->__set('tweet', $_POST['tweet']);
                $tweet->__set('id_usuario', $_SESSION['id']);

                $tweet->salvar();

                header('Location:/timeline');

        }

        public function validaAutenticacao(){

            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['id']) || $_SESSION['nome'] == '') {
                header('Location:/login=erro');
            }
        }

        public function quemSeguir(){
            $this->validaAutenticacao();

            //echo 'estamos aqui';
            // echo '<pre>';
            // print_r($_GET);
            // echo '</pre>';

            $pesquisarPor = isset($_GET['pesquisarPor']) ? ($_GET['pesquisarPor']) : '';//aqui só vai retornar ele mesmo se estiver setado


            // echo '<pre>';
            // print_r($_SESSION);//recupera id do usuário logado
            // echo '</pre>';

            //echo ' Pesquisando por : ' . $pesquisarPor;

            $usuarios = [];//criando o array aqui para não dar problema dentro do if
            
            if($pesquisarPor != ''){

                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $pesquisarPor);
                $usuario->__set('id', $_SESSION['id']);//setando o id de sessao o usuário não poderá se auto seguir
                $usuarios = $usuario->getAll();

                // echo '<pre>';
                // print_r($usuarios);
                // echo '</pre>';
               
            }

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);
            
            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets = $usuario->getTotalTweets();
            $this->view->total_seguindo = $usuario->getTotalSeguindo();
            $this->view->total_seguidores = $usuario->getTotalSeguidores();

            $this->view->usuarios = $usuarios;
     
            $this->render('quemSeguir');
        }

        public function acao(){
            $this->validaAutenticacao();
            //acao
            // echo '<pre>';
            // print_r($_GET);
            // echo '</pre>';

            //acao e usuario que queremos seguir
            $acao = isset($_GET['acao']) ? ($_GET['acao']) : '';

            //id_usuario que sera seguido
            $id_usuario_seguindo = isset($_GET['id_usuario']) ? ($_GET['id_usuario']) : '';

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);

            if($acao == 'seguir'){
                $usuario->seguirUsuario($id_usuario_seguindo); 
            }else if($acao == 'deixar_de_seguir'){
                $usuario->deixarSeguirUsuario($id_usuario_seguindo);
            }

            header('Location: /quem_seguir');
            
        }

        public function removerTweet(){    
            $this->validaAutenticacao();  
            // echo'Chegamos até aqui';

            // echo '<pre>';
            // print_r($_GET);
            // echo '</pre>';
              
            $id = isset($_GET['id']) ? $_GET['id'] : '';   

            $tweet = Container::getModel('Tweet'); 

            $tweet->__set('id',$id);    
            $tweet->remover();    
            header('Location: /timeline');
        }
            
    }

?>