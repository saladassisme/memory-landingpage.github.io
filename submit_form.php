<?php
header('Content-Type: application/json');

// 启用错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 简单的日志函数
function logError($message, $context = array()) {
    $logFile = __DIR__ . '/error.log';
if (!file_exists($logFile)) {
    touch($logFile);
    chmod($logFile, 0666);
}
    $timestamp = date('Y-m-d H:i:s');
    $contextStr = !empty($context) ? ' Context: ' . json_encode($context) : '';
    error_log("[$timestamp] $message$contextStr\n", 3, $logFile);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 验证必填字段
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
            throw new Exception('所有字段都是必填的');
        }

        // 验证邮箱格式
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('邮箱格式不正确');
        }

        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        // 发送确认邮件
        $to = $email;
        $subject = "感谢您联系小鹿光年";
        $email_message = "亲爱的 $name：\n\n感谢您联系小鹿光年！\n\n我们已收到您的留言，我们的团队会尽快与您联系。\n\n您的留言内容：\n$message\n\n如有任何疑问，请随时与我们联系。\n\n祝您愉快！\n小鹿光年团队";
        
        $headers = "From: 17721056642@163.com\r\n";
        $headers .= "Reply-To: 17721056642@163.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // 记录邮件发送尝试
        logError('尝试发送邮件', array(
            'to' => $email,
            'subject' => $subject
        ));

        $success = @mail($to, $subject, $email_message, $headers);
        
        if ($success) {
            logError('邮件发送成功', array('to' => $email));
            http_response_code(200);
            echo json_encode(array(
                'status' => 'success',
                'message' => "感谢您的提交，$name！我们会尽快与您联系。"
            ));
        } else {
            $error = error_get_last();
            throw new Exception('邮件发送失败: ' . ($error ? $error['message'] : '未知错误'));
        }

    } catch (Exception $e) {
        logError($e->getMessage(), array(
            'error_type' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ));
        http_response_code(400);
        echo json_encode(array(
            'status' => 'error',
            'message' => $e->getMessage()
        ));
    }
} else {
    http_response_code(405);
    echo json_encode(array(
        'status' => 'error',
        'message' => '不支持的请求方法'
    ));
}
?>