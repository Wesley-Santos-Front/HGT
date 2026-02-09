<?php
require_once ("db.php");
require_once ("globals.php");
require_once ("models/usuario.php");
require_once ("DAO/usuarioDAO.php");
require_once ("models/message.php");

$message = new Message($BASE_URL);
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

// 1. Resgatar o TOKEN (que deve vir escondido no formulário) e as senhas
$token = filter_input(INPUT_POST, "token");
$editPassw = filter_input(INPUT_POST, "senha");
$confPassw = filter_input(INPUT_POST, "senhaConf");

if($token && $editPassw && $confPassw){

    if($editPassw === $confPassw){
        
        // 2. Buscar o usuário pelo TOKEN do link, não pela sessão
        $usuario = $usuarioDao->findByToken($token);

        if($usuario) {
            // 3. Gerar a nova senha usando o seu método do Model
            $finalPassword = $usuario->generatePassword($editPassw);
            $usuario->passw = $finalPassword;

            // 4. IMPORTANTE: Gerar um novo token para invalidar o link do e-mail (Segurança)
            $usuario->token = $usuario->generateToken();

            // 5. Salvar no banco
            $usuarioDao->changePassword($usuario);
            
            $message->setMessage("Senha alterada com sucesso!", "success", "login.php");
        } else {
            $message->setMessage("Usuário inválido ou link expirado.", "error", "index.php");
        }

    } else {
        $message->setMessage("As senhas não coincidem!", "error", "back");
    }
} else {
    $message->setMessage("É necessário preencher todos os campos!", "error", "back");
}
