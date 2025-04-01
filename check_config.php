<?php
// 检查PHP版本和扩展
echo "PHP版本: " . phpversion() . "\n";

// 检查OpenSSL
if (extension_loaded('openssl')) {
    echo "OpenSSL状态: 已安装 (" . OPENSSL_VERSION_TEXT . ")\n";
} else {
    echo "OpenSSL状态: 未安装\n";
}

// 检查邮件功能
if (function_exists('mail')) {
    echo "PHP mail函数: 可用\n";
} else {
    echo "PHP mail函数: 不可用\n";
}

// 检查目录权限
$logFile = __DIR__ . '/error.log';
if (is_writable(__DIR__)) {
    echo "目录写入权限: 正常\n";
} else {
    echo "目录写入权限: 异常\n";
}

// 测试日志写入
try {
    error_log("测试日志写入\n", 3, $logFile);
    echo "日志写入测试: 成功\n";
} catch (Exception $e) {
    echo "日志写入测试: 失败 - " . $e->getMessage() . "\n";
}

// 显示PHP邮件配置
echo "\nPHP邮件配置:\n";
echo "SMTP = " . ini_get('SMTP') . "\n";
echo "smtp_port = " . ini_get('smtp_port') . "\n";
echo "sendmail_path = " . ini_get('sendmail_path') . "\n";
?> 