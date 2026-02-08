<?php
class Usuario{
  //propriedades
  public $nome;
  public $sobrenome;
  public $dataNasc;
  public $email;
  public $passw;
  public $perfil;
  public $token;

  //gerando token e senha de segurança
  public function generateToken(){
    return bin2hex(random_bytes(50));
  }
  public function generatePassword($passw){
    return password_hash($passw, PASSWORD_DEFAULT);
  }

  //gerando nome aleatório  para imagem 
  public function imageGenerateName(){
    return bin2hex(random_bytes(60)) . "jpeg";
  }
}

interface usuarioDAOInter{
  public function buildUser($data);
  public function create( Usuario $usuario, $authUser = false);
  public function update(Usuario $usuario, $redirect = true);
  public function findByToken($token);
  public function verifyToken($protected = false);
  public function setTokenToSession($token, $redirect = true);
  public function authenticateUser($email, $passw);
  public function findByEmail($email);
  public function findById($email);
  public function changePassword(Usuario $usuario);
  public function destroyToken();
}