<?php
/*
 * Update synapse on the fly - get files from lukves.6f.sk site
 *
 */
private function update() {
$urls=array(
$this->master.'/index.php',
$this->master.'/init.php',
$this->master.'/control.php',
$this->master.'/formular.php',
$this->master.'/Crypt.php',
$this->master.'/zones_cl.php',
$this->master.'/error.php',
$this->master.'/error.png',
$this->master.'/License');
 
$save_to=$this->current_dir;
 
$mh = curl_multi_init();
foreach ($urls as $i => $url) {
    $g=$save_to.basename($url);
    if(!is_file($g)){
        $conn[$i]=curl_init($url);
        $fp[$i]=fopen ($g, "w");
        curl_setopt ($conn[$i], CURLOPT_FILE, $fp[$i]);
        curl_setopt ($conn[$i], CURLOPT_HEADER ,0);
        curl_setopt($conn[$i],CURLOPT_CONNECTTIMEOUT,60);
        curl_multi_add_handle ($mh,$conn[$i]);
    }
}
do {
    $n=curl_multi_exec($mh,$active);
}
while ($active);
foreach ($urls as $i => $url) {
    curl_multi_remove_handle($mh,$conn[$i]);
    curl_close($conn[$i]);
    fclose ($fp[$i]);
}
curl_multi_close($mh);
}
?>