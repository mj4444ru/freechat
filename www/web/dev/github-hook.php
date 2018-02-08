<?php
$logfile = __DIR__ . '/autodeploy.log';
$logstr = "Auto-deploy at " . date("r") . " from " . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '(no ip)') . "\n";
$logstr .= "Commit info: " . (isset($_POST['payload']) ? $_POST['payload'] : '(no payload)') . "\n\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);

$path = realpath(__DIR__ . '/../../../');
$logstr = `cd {$path}; git pull` . "\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);
$logstr = `cd {$path}/www; composer install` . "\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);
$logstr = `cd {$path}/www; {$path}/www/yii migrate --interactive=0` . "\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);
$logstr = `cd {$path}/www; {$path}/www/yii cache/flush` . "\n";
file_put_contents($logfile, $logstr, FILE_APPEND | LOCK_EX);
$logstr = `cd {$path}/www; {$path}/www/yii cache/flush-all` . "\n";
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
