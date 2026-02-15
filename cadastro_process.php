<?php
require_once ("db.php");
require_once ("globals.php");
require_once ("models/usuario.php");
require_once ("DAO/usuarioDAO.php");
require_once ("models/message.php");

$message = new Message($BASE_URL);
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

// 1. Recebe os dados brutos e já aplica o trim para limpar espaços
$nomeBruto = filter_input(INPUT_POST, "nome");
$sobrenomeBruto = filter_input(INPUT_POST, "sobrenome");
$dataNasc = filter_input(INPUT_POST, "dataNasc");
$emailBruto = filter_input(INPUT_POST, "email2");
$passw2 = filter_input(INPUT_POST, "senha2");
$passw3 = filter_input(INPUT_POST, "senha3");

// 2. Formata os dados (Verifica se não são nulos antes de formatar para evitar erro)
$nome = "";
$sobrenome = "";
$email = "";

if($nomeBruto) {
    $nome = mb_convert_case(mb_strtolower(trim($nomeBruto)), MB_CASE_TITLE, "UTF-8");
}
if($sobrenomeBruto) {
    $sobrenome = mb_convert_case(mb_strtolower(trim($sobrenomeBruto)), MB_CASE_TITLE, "UTF-8");
}
if($emailBruto) {
    $email = mb_strtolower(trim($emailBruto));
}

// 3. Verifica se os campos obrigatórios estão preenchidos
if($nome && $sobrenome && $dataNasc && $email && $passw2 && $passw3) {

    // Verifica se as senhas coincidem
    if($passw2 === $passw3) {

        // Verificar se o e-mail já está cadastrado
        if($usuarioDao->findByEmail($email) === false) {
            
            $usuario = new Usuario();

            // Criação de token e senha
            $usuarioToken = $usuario->generateToken();
            $finalPassword = $usuario->generatePassword($passw2);

            // Preenche o objeto com os dados formatados
            $usuario->nome = $nome;
            $usuario->sobrenome = $sobrenome;
            $usuario->dataNasc = $dataNasc;
            $usuario->email = $email;
            $usuario->passw = $finalPassword;
            $usuario->token = $usuarioToken;

            // Salva no banco e autentica
            $auth = true;
            $usuarioDao->create($usuario, $auth);

        } else {
            $message->setMessage("E-mail já cadastrado, tente outro!", "error", "back");
        }

    } else {
        $message->setMessage("Senhas não coincidem!", "error", "back");
    }
    
} else {
    $message->setMessage("É necessário preencher todos os campos!", "error", "back");
}