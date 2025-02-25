<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclui o autoload do Composer
require 'vendor/autoload.php';

function sendResetPasswordEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.seuservidor.com'; // Substitua pelo seu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'seuemail@dominio.com'; // Substitua pelo seu e-mail
        $mail->Password = 'suasenha'; // Substitua pela sua senha
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS para segurança
        $mail->Port = 587; // Porta padrão para TLS

        // Remetente e destinatário
        $mail->setFrom('seuemail@dominio.com', 'Academia'); // E-mail e nome do remetente
        $mail->addAddress($email); // E-mail do destinatário

        // Conteúdo do e-mail
        $mail->isHTML(true); // Define o formato do e-mail como HTML
        $mail->Subject = 'Redefinição de Senha - Academia';
        $mail->Body    = "Clique no link abaixo para redefinir sua senha:<br><br>
                          <a href='http://seusite.com/redefinir-senha.php?token=$token'>Redefinir Senha</a>";
        $mail->AltBody = "Clique no link para redefinir sua senha: http://seusite.com/redefinir-senha.php?token=$token";

        // Envia o e-mail
        $mail->send();
        return true; // Retorna verdadeiro se o e-mail foi enviado com sucesso
    } catch (Exception $e) {
        // Em caso de erro, exibe a mensagem de erro
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        return false; // Retorna falso em caso de falha
    }
}