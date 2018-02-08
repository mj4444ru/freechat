<?php

require('github-hook-config.php');

$logfile = __DIR__ . '/autodeploy.log';
$path = realpath(__DIR__ . '/../../..');

$logstr = "Auto-deploy at " . date("r") . " from " . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '(no ip)') . "\n";
$logstr .= "Commit info: " . (isset($_POST['payload']) ? $_POST['payload'] : '(no payload)') . "\n\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);

$logstr = `{$cmdPrefix}cd {$path}; {$gitPath} reset --hard HEAD >&1; {$gitPath} chackout master >&1; {$gitPath} pull 2>&1` . "\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);

$logstr = `{$cmdPrefix}cd {$path}/www; {$composerPath} install 2>&1` . "\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);

$logstr = `{$cmdPrefix}cd {$path}/www; {$phpPath} {$path}/www/yii migrate --interactive=0 2>&1` . "\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);

$logstr = `{$cmdPrefix}cd {$path}/www; {$phpPath} {$path}/www/yii cache/flush-all 2>&1` . "\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);

$logstr = "\nEnd auto-deploy at " . date("r") . "\n\n================================================================================\n\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);

if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
    apc_clear_cache('user');
}
if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
}
