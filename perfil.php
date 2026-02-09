<!--header-->
<?php
require_once ("templates/header.php");
require_once ("DAO/usuarioDAO.php");

$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

$usuarioData = $usuarioDao->verifyToken(true);

if($usuarioData->perfil == ""){
  $usuarioData->perfil = "perfil.png";
}
?>

<!--pagina perfil-->
<div class="bloc">
  <div class="editar3">
  <div class="imagem2" id="imagem" style="background-image:url(<?= $BASE_URL ?>img/users/<?= $usuarioData->perfil ?>);"></div>
  <div>
  <h2><?= $usuarioData->nome ?>  <?= $usuarioData->sobrenome ?></h2>
  </div>  
</div>
  <div class="editar4">
    <h2>Informação pessoal:</h2>
    <br>
    <div>
    <div>
      <label for="nome">Nome: </label>
      <input type="text" name="nome" value="<?= $usuarioData->nome ?>" readonly>
    </div>
     <div>
    <label for="data2">Data de nascimento:</label>
    <input id="data2" type="date" name="data2" id="data2" value="<?= $usuarioData->dataNasc ?>"readonly>
    </div>
    </div>
    <div>
    <div>
      <label for="sobrenome">Sobrenome: </label>
      <input type="text" name="sobrenome" value="<?= $usuarioData->sobrenome ?>" readonly>
    </div>
    <div>
    <div>
      <label for="email3">E-mail:</label>
    <input id="email3" type="email" name="email3" id="email3" value="<?= $usuarioData->email ?>" readonly>
    </div>
    </div>
    </div>
    <div class="hiperl4">
    <ul>
      <div>
      <a href="<?= $BASE_URL ?>editProfile.php">Editar perfil</a>
      </div>
       <div>
      <a href="<?= $BASE_URL ?>logout.php"><- Sair da conta</a>
      </div>
    </ul>
    </div>


<!--footer-->
<?php
require_once ("templates/footer.php");
?>