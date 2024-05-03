<?php

$requirements = [];

if (PHP_VERSION_ID < 80100) {
    $requirements[] = 'Please upgrade to PHP Version 8.1 or later!';
}

// Function to check if an extension is enabled in php.ini
function is_extension_enabled($extension)
{
    return extension_loaded($extension);
}

// Function to check if a function is enabled in php.ini
function is_function_enabled($function)
{
    return function_exists($function);
}

// Check if MBString extension is enabled
if (!is_extension_enabled('mbstring')) {
    $requirements[] = 'MBString extension is enabled on your PHP configuration.';
}

// Check if JSON extension is enabled
if (!is_extension_enabled('json')) {
    $requirements[] = 'JSON extension is enabled on your PHP configuration.';
}

// Check if cURL extension is enabled
if (!is_extension_enabled('curl')) {
    $requirements[] = 'cURL extension is enabled on your PHP configuration.';
}

// Check if exec() function is enabled
if (!is_function_enabled('exec')) {
    $requirements[] = 'You need to enable exec() function in your PHP configuration.';
}

// Check if unlink() function is enabled
if (!is_function_enabled('unlink')) {
    $requirements[] = 'You need to enable unlink() function in your PHP configuration.';
}

// Check if getenv() function is enabled
if (!is_function_enabled('getenv')) {
    $requirements[] = 'You need to enable getenv() function in your PHP configuration.';
}

// Check if putenv() function is enabled
if (!is_function_enabled('putenv')) {
    $requirements[] = 'You need to enable putenv() function in your PHP configuration.';
}

// Check if require_once() function is enabled
if (is_function_enabled('require_once')) {
    $requirements[] = 'You need to enable require_once() function in your PHP configuration.';
}

// Check if mkdir() function is enabled
if (!is_function_enabled('mkdir')) {
    $requirements[] = 'You need to enable mkdir() function in your PHP configuration.';
}

// Check directory writability
$fullPath = Yii::getAlias('@app') . '/vendor';
if (!is_writable($fullPath)) {
    $requirements[] = "The directory '$fullPath' is not writable by the PHP process.";
}

// Check if composer.json exists in the specified directory
$composerJsonPath = Yii::getAlias('@webroot') . '/composer.json';
if (!file_exists($composerJsonPath)) {
    $requirements[] = "composer.json file doesn't exist in the specified directory.";
}

return $requirements;
