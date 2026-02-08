<?php
require_once ('models/testes.php');
require_once ('models/message.php');

class TestesDAO implements TesteDAOInterface{
   private $conn;
   private $url;
   private $message;

   public function __construct(PDO $conn, $url){
    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
   }

   public function buildTestes($data){
    $testes = new Testes();

    //dando valores aos objetos criados
    $testes->id = $data["usuario_data"];
    $testes->email_usuario = $data["usuario_email"];
    $testes->madrugada = $data["madrugada"];
    $testes->data_teste = $data["data_teste"];
    $testes->antesCafe = $data["antesCafe"];
    $testes->depoisCafe = $data["depoisCafe"];
    $testes->antesAlmoco = $data["antesAlmoco"];
    $testes->depoisAlmoco = $data["depoisAlmoco"];
    $testes->antesJantar = $data["antesJantar"];
    $testes->depoisJantar = $data["depoisJantar"];

    return $testes;
   }


  public function findAll($email_usuario){
    //Data local
    date_default_timezone_set('America/Sao_Paulo');

    //data atual
    $dataAt = date("Y-m-d");

    $stmt = $this->conn->prepare("SELECT madrugada, antesCafe, depoisCafe, antesAlmoco, depoisAlmoco, antesJantar, depoisJantar FROM autentic.teste WHERE usuario_email = :usuario_email 
    AND DATE(data_teste) = :data_teste ");
    $stmt->bindParam(":data_teste", $dataAt);
    $stmt->bindParam(":usuario_email", $email_usuario);
    $stmt->execute();
    $test = $stmt->fetch(PDO::FETCH_ASSOC);

    return $test;
  }

  public function findAllTests($email_usuario){
    //Data local
    date_default_timezone_set('America/Sao_Paulo');

    //calculamos a data de 20 dias atrás
    $dataInic = date("Y-m-d", strtotime("-20 days"));
    $dataFinali = date("Y-m-d");

    $stmt = $this->conn->prepare("SELECT madrugada, data_teste, antesCafe, depoisCafe, antesAlmoco, depoisAlmoco, antesJantar, depoisJantar FROM autentic.teste 
    WHERE usuario_email = :usuario_email 
    AND data_teste >= :dataInic 
    AND data_teste <= :dataFinali 
    ORDER BY data_teste DESC");

    $stmt->bindParam(":usuario_email", $email_usuario);
    $stmt->bindParam(":dataInic", $dataInic);
    $stmt->bindParam(":dataFinali", $dataFinali);
    $stmt->execute();
    $todosTeste = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $todosTeste;


  }


  public function verifyNullTestes($email_usuario){
    //Data local
    date_default_timezone_set('America/Sao_Paulo');

    //data atual
    $dataAtual = date("Y-m-d");

    $stmt = $this->conn->prepare("SELECT madrugada, antesCafe, depoisCafe, antesAlmoco, depoisAlmoco, antesJantar, depoisJantar FROM autentic.teste WHERE usuario_email = :usuario_email 
    AND DATE(data_teste) = :data_teste ");
    $stmt->bindParam(":data_teste", $dataAtual);
    $stmt->bindParam(":usuario_email", $email_usuario);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se não encontrar nada, retorna um array com zeros em vez de FALSE
    if (!$registro) {
        return [
            "madrugada" => 0, "antesCafe" => 0, "depoisCafe" => 0, 
            "antesAlmoco" => 0, "depoisAlmoco" => 0, "antesJantar" => 0, "depoisJantar" => 0
        ];
    }

    return $registro;
  }


  public function countTodayTest($email_usuario){
    $dataAtual1 = date("Y-m-d");
    $colunas = ["madrugada", "antesCafe", "depoisCafe", "antesAlmoco", "depoisAlmoco", "antesJantar", "depoisJantar"];
    $total = 0;

    $stmt = $this->conn->prepare("SELECT * FROM autentic.teste
    WHERE usuario_email = :usuario_email 
    AND DATE(data_teste) = :data_teste");

    $stmt->bindParam(":usuario_email", $email_usuario);
    $stmt->bindParam(":data_teste", $dataAtual1);
    $stmt->execute();

    $registro1 = $stmt->fetch(PDO::FETCH_ASSOC);

    if($registro1){
      foreach($colunas as $coluna){
        //Se a coluna existir no BD e o valor ser maior que 0
        if(!empty($registro1[$coluna]) && $registro1[$coluna] > 0){
          $total++;
        }
      }
    }
    return $total;
  }


  public function getMesTest($email_usuario){
    //Data local
    date_default_timezone_set('America/Sao_Paulo');

    //calculamos a data de 30 dias atrás
    $dataIni = date("Y-m-d", strtotime("-30 days"));
    $dataFinal = date("Y-m-d");

    $colunas3 = ["madrugada", "antesCafe", "depoisCafe", "antesAlmoco", "depoisAlmoco", "antesJantar", "depoisJantar"];

    $somaValor = 0;
    $totalTeste = 0;

    $stmt = $this->conn->prepare("SELECT * FROM autentic.teste
    WHERE usuario_email = :usuario_email
    AND DATE(data_teste) BETWEEN :dataIni AND :dataFinal");

    $stmt->bindParam(":usuario_email", $email_usuario);
    $stmt->bindParam(":dataIni", $dataIni);
    $stmt->bindParam(":dataFinal", $dataFinal);
    $stmt->execute();

    $registro3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($registro3 as $linha1){
      foreach($colunas3 as $coluna3){
        $valor1 = intval($linha1[$coluna3]);
        if($valor1 > 0){
          $somaValor += $valor1;
          $totalTeste++;
        }
      }
    }
    //evita divisão por 0 caso não hava testes na semana
    if($totalTeste > 0){
       return round($somaValor / $totalTeste, 1); // retorna a média com uma casa decimal
    }
    return 0;

  }


  public function getWeeklyAverage($email_usuario){
    //Data local
    date_default_timezone_set('America/Sao_Paulo');

    //calculamos a data de 7 dias atrás
    $dataInicio = date("Y-m-d", strtotime("-7 days"));
    $dataFim = date("Y-m-d");

    $colunas2 = ["madrugada", "antesCafe", "depoisCafe", "antesAlmoco", "depoisAlmoco", "antesJantar", "depoisJantar"];

    $somaValores = 0;
    $totalTestes = 0;

    $stmt = $this->conn->prepare("SELECT * FROM autentic.teste
    WHERE usuario_email = :usuario_email
    AND DATE(data_teste) BETWEEN :data_inicio AND :data_final");

    $stmt->bindParam(":usuario_email", $email_usuario);
    $stmt->bindParam(":data_inicio", $dataInicio);
    $stmt->bindParam(":data_final", $dataFim);
    $stmt->execute();

    $registro2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($registro2 as $linha){
      foreach ($colunas2 as $coluna2){
        $valor = intval($linha[$coluna2]);
        if($valor > 0){
          $somaValores += $valor;
          $totalTestes++;
        }
      }
    }

    //evita divisão por 0 caso não hava testes na semana
    if($totalTestes > 0){
      return round($somaValores / $totalTestes, 1); // retorna a média com uma casa decimal
    }
    return 0;
  }


  public function findByTeste($data_teste){
    //Data local
    date_default_timezone_set('America/Sao_Paulo');

    if($data_teste != "") {
      $stmt = $this->conn->prepare("SELECT * FROM autentic.teste WHERE data_teste = :data_teste");
      $stmt->bindParam(":data_teste", $data_teste);
      $stmt->execute();
       if($stmt->rowCount() > 0){
        $data = $stmt->fetch();
        $testes = $this->buildTestes($data);

        return $testes;


       }else{
        return false;
       }
    }else{
      return false;
    }
  }

  public function findByEmailAndDate($email_usuario, $data_teste){
     if($email_usuario && $data_teste != "") {
      $stmt = $this->conn->prepare("SELECT * FROM autentic.teste WHERE usuario_email = :usuario_email 
      AND data_teste = :data_teste");
      $stmt->bindParam(":usuario_email",$email_usuario);
      $stmt->bindParam(":data_teste",$data_teste);

      $stmt->execute();
       if($stmt->rowCount() > 0){
        return true;


       }else{
        return false;
       }
    }else{
      return false;
    }
  }


  public function create(Testes $testes){
    $stmt = $this->conn->prepare("INSERT INTO autentic.teste (usuario_email, data_teste, madrugada, antesCafe, depoisCafe, antesAlmoco, depoisAlmoco, antesJantar, depoisJantar) 
    VALUES (:usuario_email, :data_teste, :madrugada, :antesCafe, :depoisCafe, :antesAlmoco, :depoisAlmoco, :antesJantar, :depoisJantar)");

    $stmt->bindParam(":usuario_email", $testes->email_usuario);
    $stmt->bindParam(":data_teste", $testes->data_teste);
    $stmt->bindParam(":madrugada", $testes->madrugada);
    $stmt->bindParam(":antesCafe", $testes->antesCafe);
    $stmt->bindParam(":depoisCafe", $testes->depoisCafe);
    $stmt->bindParam(":antesAlmoco", $testes->antesAlmoco);
    $stmt->bindParam(":depoisAlmoco", $testes->depoisAlmoco);
    $stmt->bindParam(":antesJantar", $testes->antesJantar);
    $stmt->bindParam(":depoisJantar", $testes->depoisJantar);
    $stmt->execute();
  }


  public function update(Testes $testes){
     $stmt = $this->conn->prepare("UPDATE autentic.teste SET
     madrugada = COALESCE(madrugada, :madrugada),
     antesCafe = COALESCE(antesCafe, :antesCafe),
     depoisCafe = COALESCE(depoisCafe, :depoisCafe),
     antesAlmoco = COALESCE(antesAlmoco, :antesAlmoco),
     depoisAlmoco = COALESCE(depoisAlmoco, :depoisAlmoco),
     antesJantar = COALESCE(antesJantar, :antesJantar),
     depoisJantar = COALESCE(depoisJantar, :depoisJantar)
     WHERE data_teste = :data_teste and usuario_email = :usuario_email");

    $stmt->bindParam(":usuario_email", $testes->email_usuario);
    $stmt->bindParam(":data_teste", $testes->data_teste);
    $stmt->bindParam(":madrugada", $testes->madrugada);
    $stmt->bindParam(":antesCafe", $testes->antesCafe);
    $stmt->bindParam(":depoisCafe", $testes->depoisCafe);
    $stmt->bindParam(":antesAlmoco", $testes->antesAlmoco);
    $stmt->bindParam(":depoisAlmoco", $testes->depoisAlmoco);
    $stmt->bindParam(":antesJantar", $testes->antesJantar);
    $stmt->bindParam(":depoisJantar", $testes->depoisJantar);
    $stmt->execute();
  }

  public function getLatestTest($email_usuario){
    // Ordenamos pela data de forma decrescente e limitamos a 1 resultado
    $stmt = $this->conn->prepare("SELECT * FROM autentic.teste
    WHERE usuario_email = :usuario_email
    ORDER BY id DESC LIMIT 1");

    $stmt->bindParam(":usuario_email", $email_usuario);
    $stmt->execute();

    if($stmt->rowCount() > 0){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  }


  public function destroy($email_usuario){
  }


}