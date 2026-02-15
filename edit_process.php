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

//resgatando dados de post editProfile
$nome2 = filter_input(INPUT_POST, "nome2");
$sobrenome2 = filter_input(INPUT_POST, "sobrenome2");
$dataNasc2 = filter_input(INPUT_POST, "data2");
$email2= filter_input(INPUT_POST, "email3");

  //criar um novo objeto de usuario
$usuario = new Usuario();

// 2. Formata os dados (Verifica se não são nulos antes de formatar para evitar erro)
$nome5 = "";
$sobrenome5 = "";

if($nome2) {
    $nome5 = mb_convert_case(mb_strtolower(trim($nome2)), MB_CASE_TITLE, "UTF-8");
}
if($sobrenome2) {
    $sobrenome5 = mb_convert_case(mb_strtolower(trim($sobrenome2)), MB_CASE_TITLE, "UTF-8");
}


//preencher os dados do usuario
$usuarioData->nome = $nome5;
$usuarioData->sobrenome = $sobrenome5;
$usuarioData->dataNasc = $dataNasc2;
$usuarioData->email = $email2;

//upload de imagem perfil
if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

  $image = $_FILES["image"];
  $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
  $jpegArray = ["image/jpeg", "image/jpg"];

  //checagem de tipo de imagem
  if(in_array($image["type"], $imageTypes)){

    //checar se jpeg
    if(in_array($image["type"], $jpegArray)){

      $imageFile = imagecreatefromjpeg($image["tmp_name"]);

      //imagem é png
    }else{
      $imageFile = imagecreatefrompng($image["tmp_name"]);
    }

    $imageName = $usuario->imageGenerateName();
    imagejpeg($imageFile, "./img/users/" . $imageName, 80);
    imagedestroy($imageFile); // Libera a memória RAM do servidor imediatamente

    $usuarioData->perfil = $imageName;


  }else{
    $message->setMessage("Tipo invalido de imagem, insira png ou jpeg!", "error", "back");
  }
}

$usuarioDao->update($usuarioData);
?>