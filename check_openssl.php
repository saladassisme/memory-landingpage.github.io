<?php
// 方法1：检查扩展是否加载
if (extension_loaded('openssl')) {
    echo "OpenSSL 扩展已安装\n";
    echo "OpenSSL 版本: " . OPENSSL_VERSION_TEXT . "\n";
} else {
    echo "OpenSSL 扩展未安装\n";
}

// 方法2：通过phpinfo()查看
phpinfo();
?> 