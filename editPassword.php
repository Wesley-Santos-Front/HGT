<!--Header-->
<?php
require_once("templates/header.php");
require_once ("DAO/usuarioDAO.php");

$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

$usuarioData = $usuarioDao->verifyToken(true);
?>

<!--pagina editPassword-->
<div class="editar1">
<form action="<?= $BASE_URL ?>editPassword_process.php" method="post">
  <div>
  <h1>Alterar senha</h1>
  </div>
    <input type="password" name="passwAt" id="passwAt" placeholder="Digite a sua nova senha...">
    <input type="password" name="newPassw" id="newPassw" placeholder="Confirme sua nova senha...">
   <div>
    <input type="submit" value="Salvar Alterações">
  </div>
</div>
</form>

<!--Footer-->
<?php
require_once("templates/footer.php");
?>