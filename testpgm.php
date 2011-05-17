<?php
include_once './i5Pgm.php';

$parameters = array(
    array('Name' => 'KUJI', 'IO' => I5_OUT, 'Type' => I5_TYPE_CHAR, 'Length' => '6'),
    array('Name' => 'NOW',  'IO' => I5_OUT, 'Type' => I5_TYPE_INT,),
);
try {
    $pgm = new i5Pgm('ZPHPTEST/ZTESTR', $parameters);
    $out = array('KUJI' => 'OMIKUJI',
                 'NOW'  => 'MYNOW');
    $ret = $pgm->call(array(), $out);
} catch (Exception $e) {
    var_dump($e);
    exit;
}
var_dump($ret);