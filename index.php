<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/twitteroauth.php");

// Registre o aplicativo no Twitter em https://dev.twitter.com/apps e informe as chaves necessárias para que o Bot do Uptime conecte-se a sua conta no Twitter.
$consumerKey = 'consumer-key';
$consumerSecret = 'consumer-secret';
$oAuthToken = 'oauth-token';
$oAuthSecret = 'oauth-secret';

$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);

// Nome do servidor e URL que será monitorada a cada execução deste arquivo.
$nome = "Bot do Uptime";
$url = "http://www.meusite.com.br";

// Você deseja que um tweet e/ou DM seja(m) enviado(s) caso o servidor monitorado esteja ativo ou fora do ar? 0 = Não e 1 = Sim
$tweetar = 1;
$dm = 1;

// Caso $dm = 1, preencha o campo abaixo com o usuário que receberá a DM sem o @. Ex.: BotdoUptime
$userdm = "BotdoUptime";

// Aqui é feita a conexão com o servidor que será monitorado. 10 é o tempo de espera da resposta em segundos. Modifique se julgar necessário.
$ch = curl_init($url);  
	  curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
	  $data = curl_exec($ch);  
	  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
	  curl_close($ch);

// Se o CURLINFO_HTTP_CODE retornar um número maior ou igual a 200 e menor que 400, o servidor monitorado está ativo. Caso contrário, retornará 0, e isto significa que o servidor monitorado está fora do ar.
if($httpcode >= 200 && $httpcode < 400){

	// Mensagem que será tweetada e exibida após a execução do arquivo.
	$mensagem = "O servidor $nome est&aacute; operante! &ndash; C&oacute;digo: $httpcode";
	
	if($tweetar = 1) {
		// O tweet é enviado se o servidor monitorado estiver ativo.
		$tweet->post("statuses/update", array("status" => "$mensagem"));
		if(property_exists($tweet, "error")) {
  	  		echo "N&atilde;o foi poss&iacute;vel tweetar a seguinte mensagem:<br />$mensagem<br /><br />";
		} else {
   			echo "A seguinte mensagem foi enviada para o Twitter:<br />$mensagem<br /><br />";
   		}
   	}
   	
    if($dm = 1) {
    	// A DM é enviada se o servidor monitorado estiver ativo.
    	$tweet->post("direct_messages/new", array("text" => "$mensagem", "screen_name" => "$userdm"));
    	if(property_exists($tweet, "error") ) {
  	  		echo "N&atilde;o foi poss&iacute;vel enviar uma DM para $userdm com a seguinte mensagem:<br />$mensagem<br /><br />";
		} else {
    		echo "A seguinte mensagem foi enviada por DM para $userdm:<br />$mensagem";
    	}
    }

} else {

	// Mensagem que será tweetada e exibida após a execução do arquivo.
	$mensagem = "Ops, acho que o servidor $nome est&aacute; fora do ar! &ndash; C&oacute;digo: $httpcode";
	
	if($tweetar = 1) {
		// O tweet é enviado se o servidor monitorado estiver fora do ar.
		$tweet->post("statuses/update", array("status" => "$mensagem"));
		if(property_exists($tweet, "error") ) {
  	  		echo "N&atilde;o foi poss&iacute;vel tweetar a seguinte mensagem:<br />$mensagem<br /><br />";
		} else {
    		echo "A seguinte mensagem foi enviada para o Twitter:<br />$mensagem<br /><br />";
    	}
    }
    
    if($dm = 1) {
    	// A DM é enviada se o servidor monitorado estiver fora do ar.
    	$tweet->post("direct_messages/new", array("text" => "$mensagem", "screen_name" => "$userdm"));
    	if(property_exists($tweet, "error") ) {
  	  		echo "N&atilde;o foi poss&iacute;vel enviar uma DM para $userdm com a seguinte mensagem:<br />$mensagem<br /><br />";
		} else {
    		echo "A seguinte mensagem foi enviada por DM para $userdm:<br />$mensagem";
    	}
    }
}
?>