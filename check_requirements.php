<?php
echo "PHP版本: " . PHP_VERSION . "\n";
echo "OpenSSL扩展: " . (extension_loaded('openssl') ? '已启用' : '未启用') . "\n";
echo "已加载的扩展:\n";
print_r(get_loaded_extensions());

// 检查PHPMailer
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "PHPMailer 已安装\n";
    } else {
        echo "PHPMailer 未安装\n";
    }
} else {
    echo "vendor/autoload.php 文件不存在，请运行 composer install\n";
}
?> 