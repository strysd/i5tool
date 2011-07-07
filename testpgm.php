<?php
include_once './i5Pgm.php';

$parameters = array(
    array('Name' => 'KUJI', 'IO' => I5_OUT, 'Type' => I5_TYPE_CHAR, 'Length' => '6'),
    array('Name' => 'NOW',  'IO' => I5_OUT, 'Type' => I5_TYPE_INT,),
);

try {
    $pgm = new i5Pgm('YOURLIB/ZTESTR', $parameters);
} catch (Exception $e) {
    echo 'Error:', $e->getMessage();
    exit;
}

$out = array('KUJI' => 'OMIKUJI',
             'NOW'  => 'MYNOW');

try {
    $ret = $pgm->call(array(), $out);
    echo 'KUJI:', $ret['OMIKUJI'], '<BR>';
    echo 'NOW:',  $ret['MYNOW'];
} catch (Exception $e) {
    echo 'Error:', $e->getMessage();
}