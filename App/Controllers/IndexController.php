<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		//if ternário, caso o login exista nós vamos receber o que é dele, se não receberemos um valor vazio
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}

	public function inscreverse() {

		$this->view->usuario = [
			'nome' => '',
			'email' => '',
			'senha' => '',
		];
		 
		$this->view->erroCadastro = false;

		$this->render('inscreverse');
	}

	public function registrar(){
		// echo '<pre>';
		// print_r($_POST);
		// echo '<pre/>';
		//receber dados do formulario

		//importando a classe container que já vem com a conexao com o bd
		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', ($_POST['nome'])); 
		$usuario->__set('email', ($_POST['email'])); 
		$usuario->__set('senha', md5(($_POST['senha']))); 

		// echo '<pre>';
		// print_r($usuario);
		// echo '</pre>';
		if($usuario->validaCadastro() && count($usuario->usuarioExistente()) == 0){
			// sucesso
			// echo '<pre>';
			// print_r($usuario->usuarioExistente());
			// echo '</pre>';

				$usuario->salvar();
				$this->render('cadastro');

		}else {
			//erro
			$this->view->usuario = [
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha'],
			];
			$this->view->erroCadastro = true;
			$this->render('inscreverse');
		}

	}

}


?>