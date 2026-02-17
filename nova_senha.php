<?php
require_once ("templates/header.php");
require_once("db.php");
require_once("DAO/usuarioDAO.php");
require_once("globals.php");

$token = filter_input(INPUT_GET, "token");
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

// O método findByToken que você já tem no DAO
$usuario = $usuarioDao->findByToken($token);

if(!$usuario) {
    $message->setMessage("Link inválido ou expirado.", "error", "recuperar_senha.php");
}
?>


<div class="editar1">
<h2>Definir Nova Senha</h2>
<form action="novaSenha_process" method="POST">
    <input type="hidden" name="token" value="<?= $token ?>">
    <input type="password" name="senha" placeholder="Digite sua nova senha..." required>
    <input type="password" name="senhaConf" placeholder="Digite sua nova senha novamente..." required> 
    <div>
    <input type="submit" value="Alterar senha">
    </div>
</form>
</div>

<?php
require_once ("templates/footer.php");
?>