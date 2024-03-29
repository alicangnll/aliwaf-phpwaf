<?php
ob_start();
include("class.engelle.php");

$waf = new AliWAF_Block();
$waf->Baglanti();
$waf->memlimit("1", "G");

if ($waf->prepareDB_DebugDurum() == true){
$waf->Debug();
}

if($waf->prepareDB_WAFDurum() == true) {
header("X-AliWAF: ACTIVE");
if ($waf->prepareDB_KontrolAyar() == true){
$waf->queryDB_IPKontrol($waf->reel_ip());
$waf->queryDB_KontrolKurali(file_get_contents('php://stdin'));

if($_POST) {
$waf->queryDB_KontrolKurali(file_get_contents('php://input'));
} else {
$waf->queryDB_KontrolKurali($_SERVER['QUERY_STRING']);
}

$waf->queryDB_MethodKontrol(strip_tags($_SERVER['REQUEST_METHOD']));
} else {
die("AliWAF Error : Configs are not active");
header("X-AliWAF: DEACTIVE");
}

} else {
header("X-AliWAF: DEACTIVE");
}
$waf->closeConnection();
?>
