<?php
session_start();

$comentarios = array();
$n = count($_SESSION['comentario']['data'])."<br";

for($i=0; $i<$n; $i++){
  $comentarios[$i]['text'] = "I don't like it because it's weird";
}

$comentarios = json_encode($comentarios[0]);
print_r($comentarios);

//$comentarios = json_encode($comentarios);

//$uri = '"f2060a8b-62bf-4ed2-97f2-5d56df536515:ChnXi0H5UciY" "https://gateway.watsonplatform.net/tone-analyzer/api/post"';
$uri = 'https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2017-09-21&sentences=true';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $uri);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_USERPWD, "f2060a8b-62bf-4ed2-97f2-5d56df536515:ChnXi0H5UciY");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $comentarios);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($ch);

print_r($result);
//print_r($status_code);
?>
