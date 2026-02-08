<?php

class Message {
  private $BASE_URL;

  public function __construct($BASE_URL){
    $this->BASE_URL = $BASE_URL;
  }

  //função para capturar mensagem 
  public function getMessage(){
    if(!empty($_SESSION['msg'])){
      return [
        "msg" => $_SESSION['msg'],
        "type" => $_SESSION["type"]
      ];
    }else{
      return false;
    }
  }

  //função para setar mansagem
  public function setMessage($msg, $type, $redirect = "index.php"){
    $_SESSION["msg"] = $msg;
    $_SESSION["type"] = $type;

    if($redirect != "back"){
      header("Location: $this->BASE_URL" . $redirect);
    }else{
      header("Location:" . $_SERVER["HTTP_REFERER"]);
    }
  }

  //função para apagar mensagem
  public function clearMessage(){
    $_SESSION["msg"] = "";
    $_SESSION["type"] = "";
  }
}