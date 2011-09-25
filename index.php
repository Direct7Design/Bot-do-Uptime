<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/twitteroauth.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/oAuth.php');

// Registre o aplicativo em https://dev.twitter.com/apps/new e insira as chaves correspondentes nos campos abaixo.
$consumerKey = 'consumer-key';
$consumerSecret = 'consumer-secret';
$oAuthToken = 'oauth-token';
$oAuthSecret = 'oauth-secret';

$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);

// Nome do servidor e URL que será monitorada a cada execução deste arquivo.
$nome = "Bot do Uptime";
$url = "http://www.meusite.com.br";

// 10 é o tempo de espera de resposta. Mude-o se julgar necessário.
$ch = curl_init($url);  
	  curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
	  $data = curl_exec($ch);  
	  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
	  curl_close($ch);

// Se o CURLINFO_HTTP_CODE retornar um número maior ou igual a 200 e menor que 400, a URL está ativa. Caso contrário, retornará 0, e isto significa que a URL está fora do ar.
if($httpcode >= 200 && $httpcode < 400){

	// Mensagem que será tweetada e exibida após a execução do arquivo.
	$mensagem = "O servidor $nome est&aacute; operante! &ndash; C&oacute;digo: $httpcode";
	
	// O tweet é enviado.
	$tweet->post('statuses/update', array('status' => "$mensagem"));
	
	// A mensagem é exibida.
	echo $mensagem;

} else {

	// Mensagem que será tweetada e exibida após a execução do arquivo.
	$mensagem = "Ops, acho que o servidor $nome est&aacute; fora do ar! &ndash; C&oacute;digo: $httpcode";
	
	// O tweet é enviado.
	$tweet->post('statuses/update', array('status' => "$mensagem"));
	
	// A mensagem é exibida.
	echo $mensagem;
}
?>