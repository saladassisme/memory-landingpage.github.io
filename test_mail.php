<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

try {
    $mail = new PHPMailer(true);
    
    $mail->isSMTP();
    $mail->SMTPDebug = 2;
    $mail->Host = 'smtp.163.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'deerlight_ai@163.com';
    $mail->Password = 'THYx7cjLgMzbjsFC';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('deerlight_ai@163.com', '小鹿光年');
    $mail->addAddress('deerlight_ai@163.com');

    $mail->isHTML(true);
    $mail->Subject = '测试邮件';
    $mail->Body = '这是一封测试邮件';

    $mail->send();
    echo "邮件发送成功\n";
} catch (Exception $e) {
    echo "邮件发送失败: {$mail->ErrorInfo}\n";
} 