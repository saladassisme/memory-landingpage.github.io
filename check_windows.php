<?php
echo "==== 系统检查 ====\n";
echo "1. PHP版本: " . PHP_VERSION . "\n";
echo "2. OpenSSL状态: " . (extension_loaded('openssl') ? '已启用' :'未启用') . "\n";
echo "3. PHPMailer状态: ";

if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "已安装\n";
    } else {
        echo "未安装\n";
    }
} else {
    echo "未安装 (vendor/autoload.php 不存在)\n";
}

echo "\n==== PHP配置信息 ====\n";
echo "PHP配置文件位置: " . php_ini_loaded_file() . "\n";
echo "扩展目录: " . ini_get('extension_dir') . "\n";

echo "\n==== 已加载扩展 ====\n";
print_r(get_loaded_extensions());
?> 