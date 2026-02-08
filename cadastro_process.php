<?php
require_once ("db.php");
require_once ("globals.php");
require_once ("models/usuario.php");
require_once ("DAO/usuarioDAO.php");
require_once ("models/message.php");

$message = new Message($BASE_URL);

$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

//declarando variaveis de input
$nome = filter_input(INPUT_POST, "nome");
$sobrenome = filter_input(INPUT_POST, "sobrenome");
$dataNasc = filter_input(INPUT_POST, "dataNasc");
$email= filter_input(INPUT_POST, "email2");
$passw2 = filter_input(INPUT_POST, "senha2");
$passw3= filter_input(INPUT_POST, "senha3");

//verifica se os campos estão vazios
if($nome && $sobrenome && $dataNasc && $email && $passw2 && $passw3){

  //verifica se as senhas coecidem
  if($passw2 === $passw3){

    //verificar se o e-mail já esta cadastrado
    if($usuarioDao->findByEmail($email) === false){
      //instanciando a classe
      $usuario = new Usuario();

      //criação de token e senha
      $usuarioToken = $usuario->generateToken();
      $finalPassword = $usuario->generatePassword($passw2);

      //instanciando objetos 
      $usuario->nome = $nome;
      $usuario->sobrenome = $sobrenome;
      $usuario->dataNasc = $dataNasc;
      $usuario->email = $email;
      $usuario->passw = $finalPassword;
      $usuario->token = $usuarioToken;

      //autenticar usuario após criação de conta
      $auth = true;
      $usuarioDao->create($usuario, $auth);

    }else{
      $message->setMessage("E-mail já cadastrado, tente outro!", "error", "back");
    }

  }else{
    $message->setMessage("Senhas não coecidem!", "error", "back");
  }
  
}else{
  $message->setMessage("É necessario preencher todos os campos!", "error", "back");
}
?>