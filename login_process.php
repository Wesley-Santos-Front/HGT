<?php
require_once ("db.php");
require_once ("globals.php");
require_once ("models/usuario.php");
require_once ("DAO/usuarioDAO.php");
require_once ("models/message.php");

$message = new Message($BASE_URL);

$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

//declarando variaveis de input
$email1= trim(filter_input(INPUT_POST, "email1"));
$passw1 = trim(filter_input(INPUT_POST, "senha1"));

//Verifica se os campos estão preenchidos
if($email1 && $passw1){

  if($usuarioDao->authenticateUser($email1, $passw1)){ 
    $message->setMessage("Seja bem vindo", "success", "homeLogado.php");

  }else {
    $message->setMessage("Usuário e/ou senha incorreto", "error", "login.php");

  }

}else{
  $message->setMessage("É necessário preencher todos os campos!", "error", "back");
}
?>