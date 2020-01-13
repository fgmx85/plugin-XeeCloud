<?php
require_once('APIXeeCloud.class.php');

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Recuperation de client_id et client_secret dans la config
$CLIENT_ID     	= config::byKey('XeeCloudClientID','XeeCloud');
$CLIENT_SECRET 	= config::byKey('XeeCloudClientIDSecret','XeeCloud');
//$REDIRECT_URI 	= config::byKey('XeeCloudRedirectURL','XeeCloud');;

$CLIENTv4_ID     = config::byKey('XeeCloudAPIv4ClientID','XeeCloud');
$CLIENTv4_SECRET = config::byKey('XeeCloudAPIv4ClientIDSecret','XeeCloud');
$CLIENTv4_KEY 	 = config::byKey('XeeCloudAPIv4ClientIDKey','XeeCloud');

$REDIRECT_URI 	= network::getNetworkAccess('external') . "/plugins/XeeCloud/3rdparty/oauth.php";

$access_token = '';
$refresh_token = '';

$code = '';
if (isset($_GET['code'])) {
	$code = $_GET['code'];
}
$state = '';
if (isset($_GET['state'])) {
	$state = $_GET['state'];
}

echo '<html>'."\r\n";
echo '<body>'."\r\n";

$eqLogic = eqLogic::byId($_GET['state']);
if ($eqLogic->getEqType_name() == 'XeeCloud') {
	if ($eqLogic->getConfiguration('apiversion') == 4) {
		// API v4
		$XeeCloud = new XeeCloudAPIv4($CLIENTv4_KEY, $CLIENTv4_SECRET, $access_token, $refresh_token, $REDIRECT_URI);

		$response = $XeeCloud->getXeeCloudAccessTokenForm($code, $state);
		$code_response = $XeeCloud->getResponseCode($response);

		if ($code_response == 200) {
			if (!isset($_GET['state'])) {
				echo "access_token : " . $info['access_token'];
				echo "<br>\r\n";
				echo "refresh_token : " . $info['refresh_token'];
			} else	{
				$eqLogic->setConfiguration('access_token', $response['result']['access_token']);
				$eqLogic->setConfiguration('refresh_token', $response['result']['refresh_token']);
				$eqLogic->setConfiguration('token_expires_in', $XeeCloud->getToken_Expires_in());
				$eqLogic->setConfiguration('token_last_refresh_UTC', gmdate('YmdHis'));
				$eqLogic->save();
				$eqLogic->refresh();
				log::add('XeeCloud', 'info', 'Authentification OK', 'config');
				echo "<center>Authentification OK</center><br><br>\r\n";
				echo '<center><form><input type="button" onclick="window.close();" value="Fermer la fenetre"></form></center>'."\r\n";
				echo '<script type="text/javascript">setTimeout(function() {window.close();}, 3000);</script>'."\r\n";
			}
		} else {
			echo "! Authentification annulee ! (".$code_response." : ".$response['result']['messages'].")\r\n";
		}
	} else {
		// API v3
		$XeeCloud = new XeeCloudAPI($CLIENT_ID, $CLIENT_SECRET, $access_token, $refresh_token, $REDIRECT_URI);

		$response = $XeeCloud->getXeeCloudAccessTokenForm($code, $state);
		$code_response = $XeeCloud->getResponseCode($response);

		if ($code_response == 200) {
			if (!isset($_GET['state'])) {
				echo "access_token : " . $info['access_token'];
				echo "<br>\r\n";
				echo "refresh_token : " . $info['refresh_token'];
			} else	{
				$eqLogic->setConfiguration('access_token', $response['result']['access_token']);
				$eqLogic->setConfiguration('refresh_token', $response['result']['refresh_token']);
				$eqLogic->save();
				$eqLogic->refresh();
				log::add('XeeCloud', 'info', 'Authentification OK', 'config');
				echo "Authentification OK - Vous pouvez fermer cette fenetre.\r\n";
				echo '<form><input type="button" onclick="window.close();" value="Fermer la fenetre"></form>';
			}
		} else {
			echo "! Authentification annulee ! (".$code_response." : ".$response['result']['messages'].")\r\n";
		}
	}
	
} else {
	echo "! eqLogic non reconnu !\r\n";
}

/*
$XeeCloud = new XeeCloudAPI($CLIENT_ID, $CLIENT_SECRET, $access_token, $refresh_token, $REDIRECT_URI);

$response = $XeeCloud->getXeeCloudAccessTokenForm($code, $state);
$code_response = $XeeCloud->getResponseCode($response);
//echo '<html>'."\r\n";
//echo '<body>'."\r\n";
if ($code_response == 200) {
	if (!isset($_GET['state'])) {
		echo "access_token : " . $info['access_token'];
		echo "<br>\r\n";
		echo "refresh_token : " . $info['refresh_token'];
	} else	{
		$eqLogic = eqLogic::byId($_GET['state']);
		if ($eqLogic->getEqType_name() == 'XeeCloud') {
			$eqLogic->setConfiguration('access_token', $response['result']['access_token']);
			$eqLogic->setConfiguration('refresh_token', $response['result']['refresh_token']);
			$eqLogic->save();
			$eqLogic->refresh();
			log::add('XeeCloud', 'info', 'Authentification OK', 'config');
			echo "Authentification OK - Vous pouvez fermer cette fenetre.\r\n";
		} else {
			echo "! eqLogic non reconnu !\r\n";
		}
	}
} else {
	echo "! Authentification annulee ! (".$code_response." : ".$response['result']['messages'].")\r\n";
}
*/
echo '</body>'."\r\n";
echo '</html>'."\r\n";
?>