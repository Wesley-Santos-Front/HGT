<?php
require_once ("db.php");
require_once ("globals.php");
require_once ("models/usuario.php");
require_once ("DAO/usuarioDAO.php");
require_once ("models/message.php");

//instanciando classes
$message = new Message($BASE_URL);
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$usuarioData = $usuarioDao->verifyToken();

//resgatando dados de post editPassword
$editPassw = filter_input(INPUT_POST, "passwAt");
$confPassw = filter_input(INPUT_POST, "newPassw");

//id de segurança
$email = $usuarioData->email;

//verificação de campos de textos
if($editPassw && $confPassw){

  //verificação de senhas 
  if($editPassw === $confPassw){

    //criar um novo objeto de usuario
    $usuario = new Usuario();

    $finalPassword = $usuario->generatePassword($editPassw);

    $usuario->passw = $finalPassword;
    $usuario->email = $email;

    $usuarioDao->changePassword($usuario);

  }else{
    $message->setMessage("Senhas não coecidem!", "error", "back");
  }

}else{
  $message->setMessage("É necessario preencher todos os campos!", "error", "back");
}
?>