<?php
//Aqui a treta começa

//Inicia a sessão
session_start();

//Vejo se existe o código
if(isset($_GET['code'])) {
  $code = $_GET['code'];//Pego o código da url

  //Cria um array com os dados do app e do usuário
  $data = array(
      'client_id' => '0811dd1397124ac8a73ccc77e419a082', //dado do app
      'client_secret' => '6948ae26f1e24454b0a17f378d21f3ce', //dado do app
      'grant_type' => 'authorization_code', //dado do app
      'redirect_uri' => 'http://localhost/TrabalhoWeb/get_code.php', //url de redirecionamento do app
      'code' => "$code" //código que acabamos de pegar
  );

  //Url que vamos fazer a requisição
  $uri = 'https://api.instagram.com/oauth/access_token';

  //Inicia o curl (procura aí oq é)
  $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $uri); // Passa a url que vamos fazer a requisição
	curl_setopt($ch, CURLOPT_POST, true); // Diz que a requisição será por post
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // envia os doados da requisição
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // diz para a requisição retornar algo
	curl_setopt($ch, CURLOPT_HEADER, 0); // diz para requisição não retornar nenhum tipo de cabeçalho
	curl_setopt($ch, CURLOPT_NOBODY, 0); // NO RETURN BODY false / we need the body to return
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // VERIFY SSL HOST false
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // VERIFY SSL PEER false

  //executa a requisição, pega o resultado (que chega em JSON), decodifica ele e salva na variável
	$result = json_decode(curl_exec($ch), true);

  //Fecha a requisição
  curl_close($ch);

  //Salva os dados na seção
  $_SESSION['dados'] = $result;

  //redireciona pra post
  header('Location: post.php');
}
?>
