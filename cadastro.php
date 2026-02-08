<!--header-->
<?php
require_once ("templates/header.php");

//impede de se o usuario voltar a pagina logado, vá para a pagina home
if($usuarioData){
  header("Location: homeLogado.php");
  exit;
}
?>

<!--pagina cadastro-->
<section class="container">
<div class="convite3">
<h1>Crie sua conta conosco!</h1>
<p>Acompanhe seus níveis de glicose, de forma simples e segura</p>
</div>
<div class="formula1">
  <form action="<?= $BASE_URL ?>cadastro_process.php" method="post" autocomplete="off">
    <div class="inputs2">
    <input type="text" name="nome" id="nome" autocomplete="new-text" placeholder="Digite seu nome..."/>
    <input type="text" name="sobrenome" id="sobrenome" autocomplete="new-text" placeholder="Digite seu sobrenome..."/>
    <label for="data">Data de nascimento:</label>
    <input type="date" name="dataNasc" id="data"/>
    <input type="email" name="email2" id="email2" autocomplete="new-email" placeholder="E-mail..."/>
    <input type="password" name="senha2" id="senha2" autocomplete="new-password" placeholder="Senha..."/>
    <input type="password" name="senha3" id="senha3" autocomplete="new-password" placeholder="Digite sua senha novamente..."/>
    <div>
    <input type="submit" value="Cadastrar">
    </div>
    <div class="hiperl2">
      <ul>
        <div>
        <a href="<?= $BASE_URL ?>login.php">Login</a>
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
