<?php

$requirements = [];

if (PHP_VERSION_ID < 80100) {
    $requirements[] = 'Please upgrade to PHP Version 8.1 or later!';
}

if (!extension_loaded('mbstring')) {
    $requirements[] = 'MBString extension is enabled on your PHP configuration.';
}

if (!extension_loaded('json')) {
    $requirements[] = 'JSON extension is enabled on your PHP configuration.';
}

if (!extension_loaded('curl')) {
    $requirements[] = 'cURL extension is enabled on your PHP configuration.';
}

// Check directory writability
$fullPath = Yii::getAlias('@app') . '/protected/vendor';
if (is_writable($fullPath)) {
    $requirements[] = "The directory '$fullPath' is writable by the PHP process.";
}

return $requirements;
