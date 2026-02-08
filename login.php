<!--header-->
<?php
require_once ("templates/header.php");

//impede de se o usuario voltar a pagina logado, vá para a pagina login
if($usuarioData){
  header("Location: homeLogado.php");
  exit;
}
?>

<!--pagina login-->
<section class="container">
<div class="convite3">
<h1>Bem-vindo de volta</h1>
<p>Acompanhe seus níveis de glicose, de forma simples e segura</p>
</div>
<div class="formula">
  <form action="<?= $BASE_URL ?>login_process.php" method="post" autocomplete="off">
    <div class="inputs1">
    <input type="email" name="email1" id="email1" autocomplete="new-email" placeholder="Digite seu E-mail...">
    <input type="password" name="senha1" id="senha1" autocomplete="new-password" placeholder="Digite sua senha...">
    <div>
    <input type="submit" value="entrar">
    </div>
    <div class="hiperl1">
      <ul>
        <div>
        <a href="<?= $BASE_URL ?>cadastro.php">Cadastrar conta</a>
        </div>
        <div>
        <a href="">Esqueceu a senha?</a>
        </div>
      </ul>
    </div>
    </div>
  </form>
</div>
</section>

<!--footer-->
<?php
require_once("templates/footer.php");
?>
