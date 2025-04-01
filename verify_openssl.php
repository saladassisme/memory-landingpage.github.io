<?php
// 检查SSL相关函数是否可用
if (function_exists('openssl_encrypt')) {
    echo "OpenSSL 加密函数可用\n";
    
    // 测试SSL功能
    $data = "测试数据";
    $password = "测试密码";
    
    $encrypted = openssl_encrypt($data, "AES-128-ECB", $password);
    if ($encrypted !== false) {
        echo "加密测试成功\n";
        
        $decrypted = openssl_decrypt($encrypted, "AES-128-ECB", $password);
        if ($decrypted === $data) {
            echo "解密测试成功\n";
        }
    }
} else {
    echo "OpenSSL 加密函数不可用\n";
}

// 检查 SMTP 相关的 SSL 支持
$smtp_test = fsockopen('ssl://smtp.163.com', 465, $errno, $errstr, 30);
if ($smtp_test) {
    echo "SMTP SSL 连接测试成功\n";
    fclose($smtp_test);
} else {
    echo "SMTP SSL 连接测试失败: $errstr ($errno)\n";
}
?> 