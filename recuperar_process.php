<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once ("vendor/autoload.php");
require_once ("DAO/usuarioDAO.php");
require_once ("models/message.php");
require_once ("models/usuario.php");
require_once ("db.php");
require_once ("globals.php");

$message = new Message($BASE_URL);
$email4 = filter_input(INPUT_POST, "email4");
$usuarioDao = new UsuarioDAO($conn, $BASE_URL);

if($email4){
    $usuario = $usuarioDao->findByEmail($email4);

    if($usuario){
        $token = $usuario->generateToken();
        $usuario->token = $token;
        $usuarioDao->update($usuario, false);

        // Ajuste o link para apontar para o seu localhost
        $link = "http://localhost/autenticacao/nova_senha.php?token=" . $token;

        // 1. PRIMEIRO: Instancia a classe
        $mail = new PHPMailer(true);

        try {
            // ... (suas configurações de $mail permanecem iguais)

            $mail->send();
            $message->setMessage("Verifique seu e-mail para concluir a troca!", "success", "back");

        } catch (Exception $e) {
            // PLANO B: Se o e-mail falhar, mostra o link na tela para você testar
            echo "<div style='background:#fff3cd; color:#856404; padding:20px; border:1px solid #ffeeba; border-radius:5px; font-family:sans-serif; margin: 20px;'>";
            echo "<h3>Aviso: O envio de e-mail falhou</h3>";
            echo "Provavelmente um bloqueio de rede ou firewall local.<br><br>";
            echo "<strong>Clique abaixo para simular o recebimento do e-mail:</strong><br><br>";
            echo "<a href='$link' style='background:#28a745; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>RESETAR MINHA SENHA AGORA</a>";
            echo "<br><br><small>Erro técnico retornado: {$mail->ErrorInfo}</small>";
            echo "</div>";
            exit; // Para a execução aqui para você clicar no link
        }

    } else {
        $message->setMessage("E-mail não encontrado!", "error", "back");
    }
} else {
    $message->setMessage("É necessário preencher o campo E-mail!", "error", "back");
}
?>
