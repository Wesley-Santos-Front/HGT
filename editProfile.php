<!--Header-->
<?php
require_once ("templates/header.php");
require_once ("DAO/usuarioDAO.php");

$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

$usuarioData = $usuarioDao->verifyToken(true);

if($usuarioData->perfil == ""){
  $usuarioData->perfil = "perfil.png";
}
?>

<div class="editar">
<form action="<?= $BASE_URL ?>edit_process" method="POST" enctype="multipart/form-data">
  <div>
  <h1>Atualizar cadastro</h1>
  </div>
  <div id="imagem" style="background-image:url(<?= $BASE_URL ?>img/users/<?= $usuarioData->perfil ?>);">
  </div>
    <input type="file"  name="image" id="input1">
    <input type="text" name="nome2" id="nome2" placeholder="Atualizar Nome..." value="<?= $usuarioData->nome ?>">
    <input type="text" name="sobrenome2" id="sobrenome2" placeholder="Atualizar Sobrenome..." value="<?= $usuarioData->sobrenome ?>">
    <label for="data2">Data de nascimento:</label>
    <input type="date" name="data2" id="data2" value="<?= $usuarioData->dataNasc ?>">
    <input type="email" name="email3" id="email3" value="<?= $usuarioData->email ?>" readonly>
   <div>
    <input type="submit" value="Salvar Alterações">
  </div>
  <br>
   <div class="hiperl1">
      <ul>
        <div>
        <a href="<?= $BASE_URL ?>editPassword">Alterar Senha?</a>
        </div>
      </ul>
    </div>

</form>
</div>
<!--footer-->
<?php
require_once ("templates/footer.php");
?>