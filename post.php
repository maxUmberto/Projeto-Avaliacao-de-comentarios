<?php
function requisita_watson($comentario){
  $comentario = json_encode(array('text' => $comentario));

  $uri = 'https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2017-09-21&sentences=true';

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $uri);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_USERPWD, "f2060a8b-62bf-4ed2-97f2-5d56df536515:ChnXi0H5UciY");
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $comentario);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

  $result = curl_exec($ch);
  $result = json_decode($result, true);
  //print_r($result['document_tone']);
  return $result;
}

//inicia a sessão
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="utf-8" />
    <title>ClassificaComments</title>

    <!-- CSS Files -->
    <link href="style/css/bootstrap.min.css" rel="stylesheet" />
    <link href="style/css/style.css" rel="stylesheet" />
    <link href="style/css/icones/css/fontawesome-all.css" rel="stylesheet" />

</head>
<body class="corpo">
<!-- Navbar
<nav class="navbar fixed-top navbar-transparent" color-on-scroll="400">
    <div class="container">
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#sobre">
                        <i class="fa fa-sing-out"></i>
                        <p>Quem Somos</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
End Navbar -->
<?php
//link para deslogar
//echo '<a href="logout.php">Sair</a>';
//var_dump($_SESSION);

//pega seu token de acesso, que tá salvo na sessão
$token = $_SESSION['dados']['access_token'];

//Faz a requisição GET pra pegar o id de mídia da sua foto
//Saporra só funciona com foto sua, enão pega a url de alguma foto sua e
//substitui aqui embaixo a partir de url
$midia = file_get_contents("http://api.instagram.com/oembed?url=https://www.instagram.com/p/BkAXoObFYIL/?taken-by=umbertomax");

//De novo, essa porra chega em JSON, aqui é PHP e não entende sasporra, então
//decodifica o JSON pro PHP(que é burro) entender
$midia = json_decode($midia,true);
//Salva o id da mídia
$mid = $midia['media_id'];

?>
<div class="container">
    <div class="row">
      <div class="col-md-3"></div>
        <div class=" col-md-6 text-center">
            <img class="img-responsive redimg bordaimg" src="<?=$midia['thumbnail_url']?>" alt="Imagem"/>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div class="row esp">
      <div class="col-md-3"></div>
      <div class="col-md-6">
<?php
//De novo, faz a requisição GET, dessa vez dá o id da mídia e o token de acesso do usuário
$comentario = file_get_contents("https://api.instagram.com/v1/media/$mid/comments?access_token=$token");

//Volte a linha 13
$comentario = json_decode($comentario,true);
$_SESSION['comentario'] = $comentario;

//Conta quantos comentário tem no array pra saber quantos loops o for vai dar
$n = count($comentario['data'])."<br>";
?>
<div class="table-responsive">
  <table class="table">
    <th>Comentário</th>
    <th>Classificação</th>

<?php
//Não tenho que te explicar como funciona um for, por favor né
for($i=0; $i<$n; $i++){?>
  <tr><td class="limita"><?php echo '<br>'.$comentario['data'][$i]['from']['username'].": "; echo $comentario['data'][$i]['text'] ?></td>
    <?php
  //Tbm espero não ter que explicar que aqui em baixo essa porra tá acessando o array
  //que foi retornado na linha 26 e printando os comentários
  $sentimento = requisita_watson($comentario['data'][$i]['text']);

  //json_decode($sentimento, true);
  //echo '<pre>'.print_r($sentimento).'</pre>';
  ?>
    <td>
  <?php
  for($j=0; $j < count($sentimento['document_tone']['tones']); $j++){ //'<pre>'.print_r($sentimento['document_tone']['tones'][$j]['tone_name']).'</pre>';

    echo $sentimento['document_tone']['tones'][$j]['tone_name']. ' ' . '-' . ' ' . number_format(100*($sentimento['document_tone']['tones'][$j]['score']),1,",",".")  . '%' . "<br>";

    }
?></td></tr>
<?php



    //print_r($sentimento['document_tone']['tones'][$j]);
  }

//P.S.: Essa merda de api do Instagram só deixa você ver os seus comentários,
//não o comentário dos outros, então pega uma foto que vc comentou. Se não tem,
//comenta qualquer merda aí que aparece
?>
      </div>
      <div class="col-md-3">
      </div>
    </div>
  </div>
</body>
</html>
