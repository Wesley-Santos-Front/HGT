<?php
require_once("globals.php");
require_once("db.php");
require_once("models/message.php");
// ... outros DAOs ...

$url = filter_input(INPUT_GET, 'url');
$rota = $url ? explode('/', $url)[0] : 'home';

// 1. Lista de arquivos que são apenas PROCESSAMENTO (sem HTML)
$acoes = ['login_process', 'cadastro_process', 'logout', 'edit_process', 'editPassword_process', 'allTests', 'novaSenha_process', 'recuperar_process', 'testes_process', 'gerar_pdf'];

if (in_array($rota, $acoes)) {
    // Se for uma ação, carrega o arquivo direto e PARA a execução
    require_once($rota . ".php");
    exit; 
}

// 2. Se não for ação, é uma PÁGINA (Leva Header e Footer)
$paginas_permitidas = ['home', 'perfil', 'cadastro', 'login', 'homeLogado', 'allTests', 'editProfile', 'nova_senha', 'editPassword', 'recuperar_senha'];

require_once("templates/header.php");

if (in_array($rota, $paginas_permitidas)) {
    require_once($rota . ".php");
} else {
    require_once("404.php");
}

require_once("templates/footer.php");

  
