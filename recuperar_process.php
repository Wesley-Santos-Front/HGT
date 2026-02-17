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

        // Ajuste do link com a URL Base
        $link = $BASE_URL . "nova_senha?token=" . $token;

        $mail = new PHPMailer(true);

        try {
            // Configurações do Servidor
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            
            // AQUI: Deve ser o SEU e-mail do Gmail, não o do formulário!
            $mail->Username   = 'wesleytksantos321@gmail.com'; 
            $mail->Password   = 'amfgtbnfakhtkiwu'; 
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
            $mail->Port       = 587;                                    

            // Destinatários
            $mail->setFrom('wesleytksantos321@gmail.com', 'Sistema HGT');
            $mail->addAddress($email4);     

            // Conteúdo do E-mail
            $mail->isHTML(true);                                  
            $mail->Subject = 'Recuperacao de Senha';
            $mail->Body    = "Ola! Clique no link para resetar sua senha: <br><a href='$link'>$link</a>";

            $mail->send();
            $message->setMessage("Verifique seu e-mail!", "success", "back");

        } catch (Exception $e) {
            echo "<div style='background:#fff3cd; padding:20px; border:1px solid #ffeeba; font-family:sans-serif;'>";
            echo "<h3>Aviso: Envio de e-mail bloqueado pela hospedagem</h3>";
            echo "<strong>Link de simulação:</strong><br><br>";
            echo "<a href='$link' style='background:#28a745; color:white; padding:10px; text-decoration:none;'>RESETAR SENHA AGORA</a>";
            echo "<br><br>Erro tecnico: {$mail->ErrorInfo}";
            echo "</div>";
            exit;
        }

    } else {
        $message->setMessage("E-mail nao encontrado!", "error", "back");
    }
} else {
    $message->setMessage("Preencha o e-mail!", "error", "back");
}
