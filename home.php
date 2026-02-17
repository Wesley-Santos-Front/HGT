<!--header-->
<?php
require_once ("templates/header.php");

//impede de se o usuario voltar a pagina logado, vá para a pagina home
if($usuarioData){
  header("Location: homeLogado.php");
  exit;
}
?>

<!--pagina home-->
<section class="container">
<div class="convite">
<h1>Monitore sua saúde</h1>
<p>Acompanhe seus níveis de glicose, e mantenha sua saude em dia</p>
<a href="<?= $BASE_URL ?>login"><button>Começar</button></a>
</div>
<div class="imgTeste">
  <img src="<?= $BASE_URL ?>img/imgTeste.png" alt="teste">
</div>
</section>

<!--footer-->
<?php
require_once ("templates/footer.php");
?>
  