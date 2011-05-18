<?php
include_once './i5Command.php';

$cmd = new i5Command();

$in  = array('OBJ' => 'YOURLIB/ZLOGF', 'OBJTYPE' => '*FILE');
$out = array('TEXT' => 'OBJTEXT');

try {
    $ret = $cmd->run('RTVOBJD', $in, $out);
    echo 'TEXT:', $ret['OBJTEXT'];
} catch (Exception $e) {
    echo 'Error:', $e->getMessage();
}

echo '<BR>';

try {
    $ret = $cmd->run('CALL YOURLIB/ZTESTRNPM');
    echo 'Success to call YOURLIB/ZTESTRNPM';
} catch (Exception $e) {
    echo 'Error:', $e->getMessage();
}

echo '<BR>';

try{
    $ret = $cmd->sysval('QCCSID');
    echo '<BR>QCCSID:', $ret;
} catch (Exception $e) {
    echo 'Error:', $e->getMessage();
}