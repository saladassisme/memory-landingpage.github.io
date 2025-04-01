<?php
// 显示所有错误
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 允许跨域请求
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// 记录错误日志的函数
function logError($message, $context = []) {
    $logMessage = date('[Y-m-d H:i:s] ') . $message . "\n";
    if (!empty($context)) {
        $logMessage .= json_encode($context, JSON_UNESCAPED_UNICODE) . "\n";
    }
    file_put_contents('error.log', $logMessage, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 获取表单数据
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';

        // 验证数据
        if (empty($name) || empty($phone) || empty($email) || empty($message)) {
            throw new Exception('请填写所有必填字段');
        }

        // 验证手机号
        if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
            throw new Exception('请输入有效的手机号码');
        }

        // 验证邮箱
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('请输入有效的邮箱地址');
        }

        $mail = new PHPMailer(true);

        try {
            // 服务器设置
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;
            $mail->CharSet = 'UTF-8';

            // 收发件人设置
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($email, $name);
            $mail->addBCC(MAIL_FROM); // 给管理员发送一份副本

            // 邮件内容
            $mail->isHTML(true);
            $mail->Subject = "感谢您联系小鹿光年";
            $mail->Body = "
                <h2>亲爱的 {$name}：</h2>
                <p>感谢您联系小鹿光年！</p>
                <p>我们已收到您的留言，我们的团队会尽快与您联系。</p>
                <p>您的联系信息：</p>
                <ul>
                    <li>姓名：{$name}</li>
                    <li>手机：{$phone}</li>
                    <li>邮箱：{$email}</li>
                </ul>
                <p>您的留言内容：</p>
                <blockquote>{$message}</blockquote>
                <p>如有任何疑问，请随时与我们联系。</p>
                <p>祝您愉快！<br>小鹿光年团队</p>
            ";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => '邮件发送成功']);
            
        } catch (Exception $e) {
            logError("邮件发送失败", [
                'error' => $mail->ErrorInfo,
                'name' => $name,
                'email' => $email
            ]);
            echo json_encode(['status' => 'error', 'message' => '邮件发送失败，请稍后重试']);
        }

    } catch (Exception $e) {
        logError($e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '无效的请求方法']);
}
?>