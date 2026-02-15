<?php

require_once ("globals.php");
require_once ("db.php");
require_once ("models/message.php");
require_once ("DAO/usuarioDAO.php");
require_once ("DAO/testesDAO.php");

//elimina os warnings
ini_set('display_errors', 0);
error_reporting(0);



//instanciando classe usuarioDAO
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$testesDao = new TestesDAO($conn, $BASE_URL);

//instanciando classe message
$message = new Message($BASE_URL);
$flashMessage = $message -> getMessage();

if(!empty ($flashMessage["msg"])){
  //limpar a mensagem
  $message -> clearMessage();
}

$usuarioData = $usuarioDao->verifyToken(false);

$registro = $testesDao->verifyNullTestes($usuarioData->email);
// Se $registro for false, transformamos em um array vazio
$registroSeguro = ($registro !== false) ? $registro : [];

//todos os testes
$test = $testesDao->findAll($usuarioData->email);
//se test for false, transformamos um array em vazio
$testSeguro = ($test !== false) ? $test : [];

//todos os testes com data inclusa
$testesDat = $testesDao->findAllTests($usuarioData->email);

//array testes lançados
$testesLan = ['madrugada', 'antesCafe', 'depoisCafe', 'antesAlmoco', 'depoisAlmoco', 'antesJantar', 'depoisJantar'];

//array testes lançados com data
$testesLan1 = ['data_teste','madrugada', 'antesCafe', 'depoisCafe', 'antesAlmoco', 'depoisAlmoco', 'antesJantar', 'depoisJantar'];

//array de horarios
$horariosDisp = ['madrugada', 'antesCafe', 'depoisCafe', 'antesAlmoco', 'depoisAlmoco', 'antesJantar', 'depoisJantar'];

//data atual
$data = date("d/m/Y");

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Icon do site-->
  <link rel="shortcut icon" href="<?= $BASE_URL ?>img/teste-removebg-preview.png"/>

  <!--Fontes Inter, Permanent Marker, Aboreto, Montserrat-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Aboreto&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Permanent+Marker&display=swap" rel="stylesheet">

  <!--CSS-->
  <link rel="stylesheet" href="./css/style.css">
  <title>Teste HGT</title>
</head>
<body>

 <!--modal-->
  <form action="<?= $BASE_URL ?>testes_process.php" method="post">
  <div class="modalHead" id="modal">
  <div class="modal1">
    <div class="cabecModal">
    <h2>Registrar  Novo  Teste</h2>
    <button id="closeModal"><ion-icon name="close-outline"></ion-icon></button>
    </div>
    <div class="regDat">
      <input type="date" name="data_teste" id="data_teste" readonly>
     <select name="momento" id="momento" required>
    <option value="">...</option>
    
    <?php foreach($horariosDisp as $horarios): ?>
        <?php 
            // Verificamos se NÃO há registro ou se o valor para este horário específico é menor ou igual a zero/vazio
            $jaExiste = (!empty($registro[$horarios]) && $registro[$horarios] > 0);
        ?>

        <?php if(!$jaExiste): ?>
            <?php $label = ucfirst(preg_replace('/(?<!^)[A-Z]/', ' $0', $horarios)); ?>
            <option value="<?= $horarios ?>"><?= $label ?></option>
        <?php endif; ?>

    <?php endforeach; ?>
</select>
    </div>
    <div class="valor">
      <input type="number" name="valorTeste" id="valorTeste" placeholder="Valor do teste HGT" required/>
    </div>
    <div class="butTeste">
      <input type="submit" value="Salvar Registro">
    </div>
  </div>
</div>
</form>

  <header class="head">
  <?php if($usuarioData): ?>
    <li class="logo1">
      <a href="<?= $BASE_URL ?>homeLogado.php"><img src="<?= $BASE_URL ?>img/teste-removebg-preview.png" alt="logo"/> <span>TESTE HGT</span></a>
    </li>
    <?php else: ?>
    <li class="logo1">
      <a href="<?= $BASE_URL ?>index.php"><img src="<?= $BASE_URL ?>img/teste-removebg-preview.png" alt="logo"/> <span>TESTE HGT</span></a>
    </li>
      <?php endif; ?>

  <!--verifica se o usuario esta logado para mudar o status do header-->
  <?php if($usuarioData): ?>
    <?php 
    if($usuarioData->perfil == ""){
  $usuarioData->perfil = "perfil.png";
}
?>
    <li class="loginAuth">
      <span><?= ucwords(strtolower($usuarioData->nome)) ?></span>
      <a href="<?= $BASE_URL ?>perfil.php"><div id="imagem1" style="background-image:url(<?= $BASE_URL ?>img/users/<?= $usuarioData->perfil ?>);">
  </div></a>
    </li>
    <?php else: ?>
       <li class="login">
      <a href="<?= $BASE_URL ?>login.php">login</a>
      <span>/</span>
      <a href="<?= $BASE_URL ?>cadastro.php">cadastro</a>
    </li>
 <?php endif; ?>

  </header>

  <?php if(!empty($flashMessage["msg"])): ?>
    <div id="mensagemDiv" class="<?= $flashMessage["type"] ?>">
      <p><?= $flashMessage["msg"] ?></p>
    </div>
    <?php endif; ?>
    
    <script src="<?= $BASE_URL ?>JS/message.js"></script>