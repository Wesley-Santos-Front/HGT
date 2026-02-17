<?php
require_once ("db.php");
require_once ("globals.php");
require_once ("models/testes.php");
require_once ("DAO/testesDAO.php");
require_once ("DAO/usuarioDAO.php");
require_once ("models/message.php");

//instanciando classes
$testes = new Testes();
$message = new Message($BASE_URL);
$testesDao = new TestesDAO($conn, $BASE_URL);
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);
$usuarioData = $usuarioDao->verifyToken();

//resgatando dados de post editPassword
$dataTeste = filter_input(INPUT_POST, "data_teste");
$momento = filter_input(INPUT_POST, "momento", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$valorTeste = filter_input(INPUT_POST, "valorTeste");

if($dataTeste && $momento && $valorTeste) {

    // 1. Preparamos o objeto com os dados básicos
    $testes->email_usuario = $usuarioData->email;
    $testes->data_teste = $dataTeste;

    // Lista de colunas permitidas
    $colunasPermitidas = ["antesCafe", "depoisCafe", "antesAlmoco", "depoisAlmoco", "antesJantar", "depoisJantar", "madrugada"];

    if(in_array($momento, $colunasPermitidas)) {
        
        // 2. ATRIBUÍMOS o valor ao momento escolhido (Isso faltava no seu update!)
        $testes->$momento = $valorTeste;

        // 3. Verificamos se já existe registro para esta data
        if(!$testesDao->findByEmailAndDate($usuarioData->email, $dataTeste)) {
            
            // Caso NÃO exista: CREATE
            $testesDao->create($testes);
            $message->setMessage("Teste cadastrado com sucesso!", "success", "homeLogado");

        } else {

            // Caso JÁ exista: UPDATE
            // Agora o objeto $testes já tem o email, a data e o valor no momento certo
            $testesDao->update($testes);
            $message->setMessage("Teste cadastrado com sucesso!", "success", "homeLogado");
        }
    } else {
        $message->setMessage("Erro!", "error", "back");
    }

} else {
    $message->setMessage("É necessário preencher todos os campos!", "error", "back");
}
?>