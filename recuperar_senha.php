<!--Header-->
<?php
require_once ("templates/header.php");
?>

<!--Pagina recuperar senha-->
<div class="editar">
<form action="<?= $BASE_URL ?>recuperar_process.php" method="POST">
  <input type="email" name="email4" placeholder="Digite seu email cadastrado...">
  <input type="submit" value="Recuperar">
</form>
</div>

<!--Footer-->
<?php
require_once ("templates/footer.php");
?>