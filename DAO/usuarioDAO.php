<?php
require_once("models/usuario.php");
require_once("models/message.php");
class UsuarioDAO implements usuarioDAOInter{

  private $conn;
  private $url;
  private $message;

  public function __construct(PDO $conn, $url){
    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
  }

  public function buildUser($data){
    $usuario = new Usuario();

    //dando valores aos objetos criados
    $usuario->nome = $data["nome"];
    $usuario->sobrenome = $data["sobrenome"];
    $usuario->dataNasc = $data["dataNasc"];
    $usuario->email = $data["email"];
    $usuario->passw = $data["senha"];
    $usuario->perfil = $data["perfil"];
    $usuario->token = $data["token"];

    return $usuario;
  }


  public function create(Usuario $usuario, $authUser = false){

    $stmt = $this->conn->prepare("INSERT INTO autentic.usuarios(nome, sobrenome, dataNasc, email, senha, token) 
    VALUES (:nome, :sobrenome, :dataNasc, :email, :senha, :token)");

    $stmt->bindParam(":nome", $usuario->nome);
    $stmt->bindParam(":sobrenome", $usuario->sobrenome);
    $stmt->bindParam(":dataNasc", $usuario->dataNasc);
    $stmt->bindParam(":email", $usuario->email);
    $stmt->bindParam(":senha", $usuario->passw);
    $stmt->bindParam(":token", $usuario->token);

    $stmt->execute();

    //autentica o usuario caso o auth for true
    if($authUser){
      $this->setTokenToSession($usuario->token);
    }



  }


  public function update(Usuario $usuario, $redirect = true){
    $stmt=$this->conn->prepare("UPDATE autentic.usuarios SET 
    nome = :nome,
    sobrenome = :sobrenome,
    dataNasc = :dataNasc,
    email = :email,
    senha = :senha,
    perfil = :perfil,
    token = :token
    WHERE email = :idEmail
    ");

    $stmt->bindParam(":nome", $usuario->nome);
    $stmt->bindParam(":sobrenome", $usuario->sobrenome);
    $stmt->bindParam(":dataNasc", $usuario->dataNasc);
    $stmt->bindParam(":email", $usuario->email);
    $stmt->bindParam(":senha", $usuario->passw);
    $stmt->bindParam(":perfil", $usuario->perfil);
    $stmt->bindParam(":token", $usuario->token);
    $stmt->bindParam(":idEmail", $usuario->email);
    $stmt->execute();

    if($redirect){

      //redireciona para o perfil de usuario
      $this->message->setMessage("Dados atualizados com sucesso", "success", "homeLogado.php");
    }

  }


  public function findByToken($token){
     if($token != "") {
      $stmt = $this->conn->prepare("SELECT * FROM autentic.usuarios WHERE token = :token");
      $stmt->bindParam(":token", $token);
      $stmt->execute();

       if($stmt->rowCount() > 0){
        $data = $stmt->fetch();
        $usuario = $this->buildUser($data);

        return $usuario;

       }else{

        return false;

       }
      }else{

      return false;

    }

  }


  public function verifyToken($protected = false){
    if(!empty($_SESSION["token"])){
      $token = $_SESSION["token"];
      $usuario = $this->findByToken($token);

      if($usuario){
        return $usuario;
      }else if($protected){
        //redirecionando o usuario não autenticado
        $this->message->setMessage("Usuario não autenticado no momento", "error", "login.php");

      }
    }else if($protected){
      $this->message->setMessage("Usuario não autenticado no momento", "error", "login.php");
    }

  }


  public function setTokenToSession($token, $redirect = true){

    //salvar o token na sessão
    $_SESSION["token"] = $token;

    if($redirect){
      //redireciona para a pagina de login
      $this->message->setMessage("Seja em vindo", "success", "homeLogado.php");
    }

  }


  public function authenticateUser($email, $passw){

    $usuario = $this->findByEmail($email);

    if($usuario){

      //checar se as senhas batem
      if(password_verify($passw, $usuario->passw)){

        //gerar um token e inserir na session
        $token = $usuario->generateToken();

        $this->setTokenToSession($token, false);

        //atualizar token no usuario
        $usuario->token = $token;

        $this->update($usuario, false);

        return true;

      }else{
        return false;
      }


    }else{

      return false;

    }

  }


  public function findByEmail($email){
    if($email != "") {
      $stmt = $this->conn->prepare("SELECT * FROM autentic.usuarios WHERE email = :email");
      $stmt->bindParam(":email", $email);
      $stmt->execute();
       if($stmt->rowCount() > 0){
        $data = $stmt->fetch();
        $usuario = $this->buildUser($data);

        return $usuario;


       }else{
        return false;
       }
    }else{
      return false;
    }

  }


  public function findById($email){

  }


  public function changePassword(Usuario $usuario){
    $stmt = $this->conn->prepare("UPDATE autentic.usuarios 
    SET senha = :senha
    WHERE email = :email");

    $stmt->bindParam(":senha", $usuario->passw);
    $stmt->bindParam(":email", $usuario->email);
    $stmt->execute();

    //redireciona e apresenta a mensagem de sucesso
    $this->message->setMessage("Atualização de senha realizado com sucesso!", "success", "homeLogado.php");

  }


  public function destroyToken(){
    //remova o token de session
    $_SESSION["token"] = "";

    //redireciona e apresenta a mensagem
    $this->message->setMessage("LogOut realizado com sucesso", "success", "login.php");

  }
}