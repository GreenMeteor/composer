<?php

$requirements = [];

if (PHP_VERSION_ID < 80100) {
    $requirements[] = 'Please upgrade to PHP Version 8.1 or later!';
}

if (!extension_loaded('mbstring')) {
    $requirements[] = 'You need to enable MBString extension in your PHP configuration on the server.';
}

if (!extension_loaded('json')) {
    $requirements[] = 'You need to enable JSON extension in your PHP configuration on the server.';
}

if (!extension_loaded('curl')) {
    $requirements[] = 'You need to enable cURL extension in your PHP configuration on the server.';
}

// Check directory writability
$directory = 'protected/vendor';
$fullPath = Yii::getAlias('@app') . '/' . $directory;
if (!is_writable($fullPath)) {
    $requirements[] = "The directory '$directory' is not writable by the PHP process.";
}

return $requirements;
