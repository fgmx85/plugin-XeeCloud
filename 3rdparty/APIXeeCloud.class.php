<?php
// 20160218 Compatibilité Jeedom v2
// 20160404 Ajout du signal AirCondSwitchSts
// 20160608 Ajout du signal ComputedEngineState
// 20160723 Ajout du signal CoolantPressure
// 20160725 Ajout du signal OutdoorTemp
// 20160726 Ajout du signal RearWiperSts
// 20170219 Passage Xee API v3
// 20180311 Passage Xee API v4
 
require_once('OAuth2/Client.php');
require_once('OAuth2/GrantType/IGrantType.php');
require_once('OAuth2/GrantType/AuthorizationCode.php');
require_once('OAuth2/GrantType/RefreshToken.php');

/*     * *********************Constantes API   ************************** */

//define('XEE_URL_API'					, 'https://staging.xee.com');
//define('XEE_URL_API'					, 'https://sandbox.xee.com');
define('XEE_URL_API'					, 'https://cloud.xee.com');

/*     * *********************Constantes API v1************************** */

define('XEE_URL_API_V1'					, XEE_URL_API.'/v1');
define('XEE_URL_API_V1_AUTH'			, XEE_URL_API_V1.'/auth/auth');
define('XEE_URL_API_V1_ACCESS_TOKEN'	, XEE_URL_API_V1.'/auth/access_token.json');
define('XEE_URL_API_V1_USER_CURRENT'	, XEE_URL_API_V1.'/user/me.json');
define('XEE_URL_API_V1_USER'			, XEE_URL_API_V1.'/user/#.json');
define('XEE_URL_API_V1_USER_CARS'		, XEE_URL_API_V1.'/user/#/car.json');
define('XEE_URL_API_V1_CAR_EVENT'		, XEE_URL_API_V1.'/car/#/event.json');
define('XEE_URL_API_V1_CAR_STATUS'		, XEE_URL_API_V1.'/car/#/carstatus.json');
define('XEE_URL_API_V1_CAR_LOCATION'	, XEE_URL_API_V1.'/car/#/location.json');

/*     * *********************Constantes API v3************************** */

//define('XEE_URL_API'					, 'https://cloud.xee.com');
define('XEE_URL_API_V3'					, XEE_URL_API.'/v3');
define('XEE_URL_API_V3_AUTH'			, XEE_URL_API_V3.'/auth/auth');
define('XEE_URL_API_V3_ACCESS_TOKEN'	, XEE_URL_API_V3.'/auth/access_token');
define('XEE_URL_API_V3_USER_CURRENT'	, XEE_URL_API_V3.'/users/me');
define('XEE_URL_API_V3_USER'			, XEE_URL_API_V3.'/users/#');
define('XEE_URL_API_V3_USER_CARS'		, XEE_URL_API_V3.'/users/#/cars');
define('XEE_URL_API_V3_CAR'				, XEE_URL_API_V3.'/cars/#');
define('XEE_URL_API_V3_CAR_STATUS'		, XEE_URL_API_V3.'/cars/#/status');
define('XEE_URL_API_V3_CAR_LOCATIONS'	, XEE_URL_API_V3.'/cars/#/locations');
define('XEE_URL_API_V3_CAR_SIGNALS'		, XEE_URL_API_V3.'/cars/#/signals');
define('XEE_URL_API_V3_CAR_TRIPS'		, XEE_URL_API_V3.'/cars/#/trips');


/*     * *********************Constantes API v4************************** */

define('XEE_URL_APIV4'					, 'https://api.xee.com');
define('XEE_URL_API_V4'					, XEE_URL_APIV4.'/v4');
define('XEE_URL_API_V4_AUTH'			, XEE_URL_API_V4.'/oauth/authorize');
define('XEE_URL_API_V4_ACCESS_TOKEN'	, XEE_URL_API_V4.'/oauth/token');
define('XEE_URL_API_V4_USER_CURRENT'	, XEE_URL_API_V4.'/users/me');
define('XEE_URL_API_V4_USER'			, XEE_URL_API_V4.'/users/#');
define('XEE_URL_API_V4_USER_CARS'		, XEE_URL_API_V4.'/users/#/vehicles');
define('XEE_URL_API_V4_CAR'				, XEE_URL_API_V4.'/vehicles/#');
define('XEE_URL_API_V4_CAR_STATUS'		, XEE_URL_API_V4.'/vehicles/#/status');
define('XEE_URL_API_V4_CAR_LOCATIONS'	, XEE_URL_API_V4.'/vehicles/#/locations');
define('XEE_URL_API_V4_CAR_SIGNALS'		, XEE_URL_API_V4.'/vehicles/#/signals');
define('XEE_URL_API_V4_CAR_TRIPS'		, XEE_URL_API_V4.'/vehicles/#/trips');

/*     * ***********************Compatibility**************************** */

define('XEE_URL_API_COMPAT'				, 'https://compat.xee.com');
define('XEE_URL_API_COMPAT_V1'			, XEE_URL_API_COMPAT.'/v1');
define('XEE_URL_API_COMPAT_V1_CARDB'	, XEE_URL_API_COMPAT_V1.'/cardb/#');
define('XEE_URL_API_COMPAT_V1_KTYPE'	, XEE_URL_API_COMPAT_V1.'/ktype/#');

// ********************************************************************************
//
// Class principale XeeCloudAPI
//
// ********************************************************************************
class XeeCloudAPI extends XeeCloudAPIv3
{

}

// ********************************************************************************
//
// Class XeeCloudAPI v4
//
// ********************************************************************************
class XeeCloudAPIv4 {
    /*     * *************************Attributs****************************** */

	public	$XeeTraducBase = array(
				'FR'	=> array(
					'user'					=> 'Utilisateur',
					'car'					=> 'Vehicule',
					'name'					=> 'Nom',
					'firstName'				=> 'Prenom',
					'lastName'				=> 'Nom',
					'gender'				=> 'Genre',
					'isLocationEnabled'		=> 'Localisation active',
					'nickName'				=> 'Surnom',
					'role'					=> 'Role',
					'birthDate'				=> 'Anniversaire',
					'licenseDeliveryDate'	=> 'Date du permis',
					'creationDate'			=> 'Date de creation',
					'lastUpdateDate'		=> 'Date de mise a jour',
					'brand'					=> 'Marque',
					'make'					=> 'Fabricant',
					'model'					=> 'Model',
					'year'					=> 'Annee',
					'plateNumber'			=> 'Immatriculation',
					'numberPlate'			=> 'Immatriculation',
					'timezone'				=> 'Timezone',
					'userId'				=> 'User Id',
					'cardbId'				=> 'cardbId',
					'deviceId'				=> 'XeeConnect Id',
					'accelerometer'			=> 'Accelerometre',
					'date'					=> 'Date',
					'location'				=> 'Localisation',
					'longitude'				=> 'Longitude',
					'latitude'				=> 'Latitude',
					'altitude'				=> 'Altitude',
					'nbSat'					=> 'Nb Sat',
					'heading'				=> 'Orientation',
					'satellites'			=> 'Satellites',
					'signals'				=> 'Signaux',
					'signal'				=> 'Signal',
					'reportDate'			=> 'Date de rapport',
					'value'					=> 'Valeur',
					'status'				=> 'Etat',
					'email'					=> 'EMail',
					'createdAt'				=> 'Date Creation',
					'updatedAt'				=> 'Date Mise a jour',
				),
				'EN'	=> array('user' => 'Utilisateur',
				)
			);

			//Description FR
			//	Ouvert/fermé 
	public	$XeeSignalName = array(
				'FR'	=> array(
					'RearRightDoorSts'			=> 'Porte arriere droite',
					'RearLeftDoorSts' 			=> 'Porte arriere gauche',
					'FrontRightDoorSts' 		=> 'Porte conducteur',
					'FrontLeftDoorSts' 			=> 'Porte passager',
					'TrunkSts' 					=> 'Coffre',
					'FuelCapSts' 				=> 'Bouchon de reservoir',
					'HoodSts' 					=> 'Capot',
					'FrontLeftSeatBeltSts'		=> 'Ceinture de securite conducteur',
					'FrontRightSeatBeltSts' 	=> 'Ceinture de securite passager',
					'PassAirbagSts' 			=> 'Airbag Passager',
			//	Fenêtres
					'FrontLeftWindowPosition' 	=> 'Fenetres avant gauche (Position)',
					'FrontLeftWindowSts' 		=> 'Fenetres avant gauche (Status)',
					'FrontRightWindowPosition'	=> 'Fenetres avant droite (Position)',
					'FrontRightWindowSts' 		=> 'Fenetres avant droite (Status)',
					'RearLeftWindowPosition' 	=> 'Fenetres arriere gauche (Position)',
					'RearLeftWindowSts' 		=> 'Fenetres arriere gauche (Status)',
					'RearRightWindowPosition' 	=> 'Fenetres arriere droite (Position)',
					'RearRightWindowSts' 		=> 'Fenetres arriere droite (Status)',
					'WindowsLockSts' 			=> 'Fenetres bloquees par le conducteur',
			//	Phares
					'RightIndicatorSts' 		=> 'Clignotant droit',
					'LeftIndicatorSts' 			=> 'Clignotant gauche',
					'HazardSts' 				=> 'Warning',
					'LowBeamSts' 				=> 'Feux de croisement',
					'HighBeamSts' 				=> 'Feux de position',
					'HeadLightSts' 				=> 'Feux de route',
//					'HeadLightSts' 				=> 'Feux de route / Plein phares',
					'FrontFogLightSts' 			=> 'Feux de brouillard avant',
					'RearFogLightSts' 			=> 'Feux de brouillard arrière',
			//	Éssuie glaces
					'ManualWiperSts' 			=> 'Essuie glaces avant manuel',
					'IntermittentWiperSts' 		=> 'Essuie glaces avant intermittent',
					'LowSpeedWiperSts' 			=> 'Essuie glaces avant lent',
					'HighSpeedWiperSts' 		=> 'Essuie glaces avant rapide',
					'ManualRearWiperSts' 		=> 'Essuie glaces arriere',
					'AutoRearWiperSts' 			=> 'Essuie glaces automatique',
			//	Pédales 
					'ClutchPedalPosition' 		=> 'Position Pedale Embrayage',
					'ClutchPedalSts' 			=> 'Etat Pedale Embrayage',
					'ThrottlePedalPosition' 	=> 'Pedale d\'accelerateur position',
					'ThrottlePedalSts' 			=> 'Status Pedale d\'accelerateur',
					'BrakePedalPosition'		=> 'Pedale de frein position',
					'BrakePedalSts' 			=> 'Status pedale de frein',
					'HandBrakeSts' 				=> 'Frein à main',
			//	Vitesse Véhicule 
					'VehiculeSpeed' 			=> 'Vitesse instantanee',
					'EngineSpeed' 				=> 'Vitesse moteur',
					'RearRightWheelSpeed' 		=> 'Vitesse roue arrière droite',
					'RearLeftWheelSpeed' 		=> 'Vitesse roue arrière gauche',
					'FrontRightWheelSpeed'		=> 'Vitesse roue avant droite',
					'FrontLeftWheelSpeed' 		=> 'Vitesse roue avant gauche',
			//	Volant 
					'SteeringWheelAngle'		=> 'Angle du volant',
					'SteeringWheelSide' 		=> 'Cote du volant',
			//	Informations 
					'ReverseGearSts' 			=> 'Marche arriere enclenchee',
					'GearPosition' 				=> 'Position levier de vitesse',
					'LockSts' 					=> 'Verrouillee',
//					'LockSts' 					=> 'Voiture Verrouillee/Deverouille',
					'KeySts' 					=> 'Enclenchement cle',
					'IgnitionSts' 				=> 'Apres contact',
					'BatteryVoltage' 			=> 'Tension Batterie',
					'FuelLevel' 				=> 'Niveau d\'essence',
					'Odometer' 					=> 'Kilometrage de la voiture',
					'InteriorLightSts'			=> 'Lumieres interieures',
					'SunRoofSts' 				=> 'Toit Ouvrant',
					'DriveMode' 				=> 'Mode de conduite',
					'RadioSts' 					=> 'Radio',
					'CruiseControlSts' 			=> 'Regulateur de vitesse active',
					'AirCondSts' 				=> 'Climatisation',
					'AirCondSwitchSts' 			=> 'Climatisation',
					'VentilationSts' 			=> 'Ventilation',
					'ComputedEngineState'		=> 'Computed Engine State',
					'CoolantPressure'			=> 'Pression liquide refroidissement',
					'OutdoorTemp'				=> 'Temperature Exterieur',
					'IndoorTemp'				=> 'Temperature Interieur',
					'AutoWiperSts'				=> 'Essuie-glace Automatique',
					'RearWiperSts'				=> 'Essuie-glace arriere',
					'ComputedAccActivity'		=> 'En mouvement'
				),
				'EN'	=> array(
					'RearRightDoorSts' 			=> 'Rear Right Door status',
				)
			);

	public	$XeeVarType = array(
				'user_id'					=> 'string',
				'user_name'					=> 'numeric',
				'user_firstName'			=> 'string',
				'user_lastName'				=> 'string',
				'user_gender'				=> 'string',
				'user_isLocationEnabled'	=> 'boolean',
				'user_nickName'				=> 'string',
				'user_role'					=> 'string',
				'user_birthDate'			=> 'date',
				'user_licenseDeliveryDate'	=> 'date',
				'user_creationDate'			=> 'date',
				'user_lastUpdateDate'		=> 'date',
				'user_email'				=> 'string',
				'user_createdAt'			=> 'date',
				'user_updatedAt'			=> 'date',
				'car_id'					=> 'string',
				'car_name'					=> 'string',
				'car_make'					=> 'string',
				'car_brand'					=> 'string',
				'car_model'					=> 'string',
				'car_year'					=> 'integer',
				'car_plateNumber'			=> 'string',
				'car_numberPlate'			=> 'string',
				'car_timezone'				=> 'string',
				'car_userId'				=> 'string',
				'car_cardbId'				=> 'integer',
				'car_deviceId'				=> 'string',
				'car_creationDate'			=> 'date',
				'car_lastUpdateDate'		=> 'date',
				'car_fleetId'				=> 'string',
				'car_createdAt'				=> 'date',
				'car_updatedAt'				=> 'date',
				'car_kType'					=> 'string',
				'car_licensePlate'			=> 'string',
				'car_status_accelerometer_id'			=> 'integer',
				'car_status_accelerometer_x'			=> 'float',
				'car_status_accelerometer_y'			=> 'float',
				'car_status_accelerometer_z'			=> 'float',
				'car_status_accelerometer_date'			=> 'date',
				'car_status_accelerometer_driverId'		=> 'integer',
				'car_status_location_id'				=> 'integer',
				'car_status_location_date'				=> 'date',
				'car_status_location_longitude'			=> 'float',
				'car_status_location_latitude'			=> 'float',
				'car_status_location_altitude'			=> 'float',
				'car_status_location_nbSat'				=> 'integer',
				'car_status_location_driverId'			=> 'integer',
				'car_status_location_heading'			=> 'float',
				'car_status_location_satellites'		=> 'integer',
				'car_status_signal_AirCondSts_value'				=> 'boolean',
				'car_status_signal_AirCondSwitchSts_value'			=> 'boolean',
				'car_status_signal_LockSts_value'					=> 'boolean',
				'car_status_signal_HeadLightSts_value'				=> 'boolean',
				'car_status_signal_HighBeamSts_value'				=> 'boolean',
				'car_status_signal_VehiculeSpeed_value'				=> 'float',
				'car_status_signal_EngineSpeed_value'				=> 'float',
				'car_status_signal_RearRightDoorSts_value'			=> 'boolean',
				'car_status_signal_RearLeftDoorSts_value'			=> 'boolean',
				'car_status_signal_FrontRightDoorSts_value'			=> 'boolean',
				'car_status_signal_FrontLeftDoorSts_value'			=> 'boolean',
				'car_status_signal_TrunkSts_value'					=> 'boolean',
				'car_status_signal_FuelCapSts_value'				=> 'boolean',
				'car_status_signal_HoodSts_value'					=> 'boolean',
				'car_status_signal_FrontLeftSeatBeltSts_value'		=> 'boolean',
				'car_status_signal_FrontRightSeatBeltSts_value'		=> 'boolean',
				'car_status_signal_PassAirbagSts_value'				=> 'boolean',
				'car_status_signal_FrontLeftWindowPosition_value'	=> 'float',
				'car_status_signal_FrontLeftWindowSts_value'		=> 'boolean',
				'car_status_signal_FrontRightWindowPosition_value'	=> 'float',
				'car_status_signal_FrontRightWindowSts_value'		=> 'boolean',
				'car_status_signal_RearLeftWindowPosition_value'	=> 'float',
				'car_status_signal_RearLeftWindowSts_value'			=> 'boolean',
				'car_status_signal_RearRightWindowPosition_value'	=> 'float',
				'car_status_signal_RearRightWindowSts_value'		=> 'boolean',
				'car_status_signal_WindowsLockSts_value'			=> 'boolean',
				'car_status_signal_RightIndicatorSts_value'			=> 'boolean',
				'car_status_signal_LeftIndicatorSts_value'			=> 'boolean',
				'car_status_signal_HazardSts_value'					=> 'boolean',
				'car_status_signal_LowBeamSts_value'				=> 'boolean',
				'car_status_signal_HighBeamSts_value'				=> 'boolean',
				'car_status_signal_FrontFogLightSts_value'			=> 'boolean',
				'car_status_signal_RearFogLightSts_value'			=> 'boolean',
				'car_status_signal_ManualWiperSts_value'			=> 'boolean',
				'car_status_signal_IntermittentWiperSts_value'		=> 'boolean',
				'car_status_signal_LowSpeedWiperSts_value'			=> 'boolean',
				'car_status_signal_HighSpeedWiperSts_value'			=> 'boolean',
				'car_status_signal_ManualRearWiperSts_value'		=> 'boolean',
				'car_status_signal_AutoRearWiperSts_value'			=> 'boolean',
				'car_status_signal_ClutchPedalPosition_value'		=> 'float',
				'car_status_signal_ClutchPedalSts_value'			=> 'boolean',
				'car_status_signal_ThrottlePedalPosition_value'		=> 'float',
				'car_status_signal_ThrottlePedalSts_value'			=> 'boolean',
				'car_status_signal_BrakePedalPosition_value'		=> 'float',
				'car_status_signal_BrakePedalSts_value'				=> 'boolean',
				'car_status_signal_HandBrakeSts_value'				=> 'boolean',
				'car_status_signal_RearRightWheelSpeed_value'		=> 'float',
				'car_status_signal_RearLeftWheelSpeed_value'		=> 'float',
				'car_status_signal_FrontRightWheelSpeed_value'		=> 'float',
				'car_status_signal_FrontLeftWheelSpeed_value'		=> 'float',
				'car_status_signal_SteeringWheelAngle_value'		=> 'float',
				'car_status_signal_SteeringWheelSide_value'			=> 'float',
				'car_status_signal_ReverseGearSts_value'			=> 'boolean',
				'car_status_signal_GearPosition_value'				=> 'float',
				'car_status_signal_KeySts_value'					=> 'boolean',
				'car_status_signal_IgnitionSts_value'				=> 'boolean',
				'car_status_signal_BatteryVoltage_value'			=> 'float',
				'car_status_signal_FuelLevel_value'					=> 'float',
				'car_status_signal_Odometer_value'					=> 'float',
				'car_status_signal_InteriorLightSts_value'			=> 'boolean',
				'car_status_signal_SunRoofSts_value'				=> 'boolean',
				'car_status_signal_DriveMode_value'					=> 'float',
				'car_status_signal_RadioSts_value'					=> 'boolean',
				'car_status_signal_CruiseControlSts_value'			=> 'boolean',
				'car_status_signal_VentilationSts_value'			=> 'boolean',
				'car_status_signal_ComputedEngineState_value'		=> 'boolean',
				'car_status_signal_CoolantPressure_value'			=> 'float',
				'car_status_signal_OutdoorTemp_value'				=> 'float',
				'car_status_signal_IndoorTemp_value'				=> 'float',
				'car_status_signal_AutoWiperSts_value'				=> 'boolean',
				'car_status_signal_RearWiperSts_value'				=> 'boolean',
				'car_status_signal_ComputedAccActivity_value'		=> 'boolean'
			);

	public	$XeeVarType2Jeedom = array(
				'numeric'	=> 'numeric',
				'integer'	=> 'numeric',
				'float'		=> 'numeric',
				'binary'	=> 'binary',
				'boolean'	=> 'binary',
				'string'	=> 'string',
				'date'		=> 'string',
			);

/*	public	$XeeErrorAuth = array(
				'401'	=> array(
					'Reason'					=> 'Utilisateur',
					'Message'					=> 'Vehicule',
					'Tip'					=> 'Nom',
				),
				'EN'	=> array('user' => 'Utilisateur',
				)
			);
*/			
			
	private $_client = '';
	private $_xeecloudData = array();
	private $_info_user = array();
	
	private $_client_id = '';
	private $_client_secret = '';

	private $_access_token_ok = false;
	private $_refresh_token_ok = false;

	private $_access_token ;
	private $_refresh_token;
	private $_token_expires_in;
	private $_token_last_refresh_UTC;
	private $_redirect_url;

	public $debug = 0;
    /**
     * Construct
     *
     * @param string $client_id Client ID
     * @param string $client_secret Client Secret
     * @param string $access_token 
     * @param string $refresh_token 
     * @param string $redirect_url 
     * @return void
     */
    public function __construct($client_id, $client_secret, $access_token = '', $refresh_token = '', $redirect_url = '', $token_expires_in = '', $token_last_refresh_UTC = '')
    {
        $this->_redirect_url = $redirect_url;
		$this->_client_id = $client_id;
		$this->_client_secret = $client_secret;
		$this->client = new OAuth2\Client($client_id, $client_secret, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
		$this->setAccessToken($access_token);
		//$this->setAccessTokenType(1);
		$this->setRefreshToken($refresh_token);
		$this->setToken_Expires_in($token_expires_in);
		$this->setToken_Last_Refresh_UTC($token_last_refresh_UTC);
    }

    /**
     * Set redirect_url
     *
     * @param string $redirect_url Set the redirect_url
     * @return void
     */
	public function setRedirectURL($redirect_url) {
		return $this->_redirect_url = $redirect_url;
	}

    /**
     * Get the client Id
     *
     * @return string Client ID
     */
	public function getClientId() {
		return $this->client->getClientId();
	}

    /**
     * Get the client Secret
     *
     * @return string Client Secret
     */
	public function getClientSecret() {
		return $this->client->getClientSecret();
	}

    /**
     * Set Access_Token
     *
     * @param string $access_token Set the Access_Token
     * @return void
     */
	public function setAccessToken($access_token) {
		$this->client->setAccessToken($access_token);
		$this->_access_token_ok = ($access_token != '');
		$this->_access_token = $access_token;
	}

    /**
     * Get the Access_Token
     *
     * @return string Access_Token
     */
	public function getAccessToken() {
		return $this->_access_token;
	}

    /**
     * Set Refresh_Token
     *
     * @param string $client_secret Set the Refresh_Token
     * @return void
     */
	public function setRefreshToken($refresh_token) {
		$this->_refresh_token_ok = ($refresh_token != '');
		$this->_refresh_token = $refresh_token;
	}

    /**
     * Get the Refresh_Token
     *
     * @return string Refresh_Token
     */
	public function getRefreshToken() {
		return $this->_refresh_token;
	}

    /**
     * Set Token_Expires_in
     *
     * @param string $token_expires_in Set the Token_Expires_in
     * @return void
     */
	public function setToken_Expires_in($token_expires_in) {
		$this->_token_expires_in = $token_expires_in;
	}

    /**
     * Get the Token_Expires_in
     *
     * @return string Token_Expires_in
     */
	public function getToken_Expires_in() {
		return $this->_token_expires_in;
	}

    /**
     * Set Token_Last_Refresh_UTC
     *
     * @param string $token_expires_in Set the TokenExpires_in
     * @return void
     */
	public function setToken_Last_Refresh_UTC($token_last_refresh_UTC) {
		$this->_token_last_refresh_UTC = $token_last_refresh_UTC;
	}

    /**
     * Get the Token_Last_Refresh_UTC
     *
     * @return string TokenExpires_in
     */
	public function getToken_Last_Refresh_UTC() {
		return $this->_token_last_refresh_UTC;
	}

    /**
     * Get the AccessToken
     *
     * @return array AccessToken
	 
	 if (!isset($_GET['code'])) {
		$code = '';
	 } else {
		$code = $_GET['code'];
	 }
	 if (!isset($_GET['state'])) {
		$state = '';
	 } else {
		$state = $_GET['state'];
	 }
     */
	public function getXeeCloudAccessTokenForm($code = '', $state = '') {
		if ($code == '') {
			if ($state == '') {
				$params = array();
			}
			else {
				$params = array('state' => $state);
			}
			$paramsauth = array_merge(array(
				'scope' => 'account.read vehicles.accelerometers.read vehicles.loans.read vehicles.locations.read vehicles.read vehicles.signals.read vehicles.trips.read'
			), $params);
			print_r($paramsauth);
			$auth_url = $this->client->getAuthenticationUrl(XEE_URL_API_V4_AUTH, $this->_redirect_url, $paramsauth);
			header('Location: ' . $auth_url);
			die('Redirect');
		}
		else {
			$params = array('code' => $code,
							'redirect_uri' => $this->_redirect_url);
			$response = $this->client->getAccessToken(XEE_URL_API_V4_ACCESS_TOKEN, OAuth2\Client::GRANT_TYPE_AUTH_CODE, $params);
			if ($this->getResponseCode($response) == 200) {
				$this->setAccessToken($response['result']['access_token']);
				$this->setRefreshToken($response['result']['refresh_token']);
				$this->setToken_Expires_in($response['result']['expires_in']);
				$this->setToken_Last_Refresh_UTC(gmdate('YmdHis'));
			} else {
				$this->_access_token_ok = false;
				$this->_refresh_token_ok = false;
			}
			return $response;
		}
	}

    /**
     * Get the AccessToken
     *
     * @return array AccessToken
     */
	public function getXeeCloudAccessTokenRefresh($refresh_token = '') {
		if ($this->debug > 0) { echo "in : getXeeCloudAccessTokenRefresh"."<br>\r\n"; }
		if ($refresh_token !== '') {
			$this->setRefreshToken($refresh_token);
		}
		$refresh_token = $this->getRefreshToken();
		$params = array('refresh_token' => $refresh_token);
		$response = $this->client->getAccessToken(XEE_URL_API_V4_ACCESS_TOKEN, OAuth2\Client::GRANT_TYPE_REFRESH_TOKEN, $params);
		switch ($this->getResponseCode($response)) {
			case 200:
				$this->setAccessToken($response['result']['access_token']);
				$this->setRefreshToken($response['result']['refresh_token']);
				$this->setToken_Expires_in($response['result']['expires_in']);
				$this->setToken_Last_Refresh_UTC(gmdate('YmdHis'));
				break;
			case 429:
				$this->_refresh_token_ok = true;
				break;
			default:
				$this->_refresh_token_ok = false;
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; } 
		}
/*		if ($this->getResponseCode($response) == 200) {
			$this->setAccessToken($response['result']['access_token']);
			$this->setRefreshToken($response['result']['refresh_token']);
			$this->setToken_Expires_in($response['result']['expires_in']);
			$this->setToken_Last_Refresh_UTC(gmdate('YmdHis'));
		} else {
			$this->_refresh_token_ok = false;
			if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; } 
		}*/
		if ($this->debug > 0) { echo "out : getXeeCloudAccessTokenRefresh"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos User
     *
     * @return array Infos User
     */
	public function getXeeCloudInfosUser($user_id = -1) {
		if ($this->debug > 0) { echo "-in : getXeeCloudInfosUser"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			$this->client->setAccessTokenType(1);
			if ($user_id == -1) {
				$response = $this->client->fetch(XEE_URL_API_V4_USER_CURRENT);
			} else {
				$response = $this->client->fetch(str_replace('#', $user_id, XEE_URL_API_V4_USER));
			}
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosUser();
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "-out : getXeeCloudInfosUser"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos User Cars
     *
     * @return array Infos User Cars
     */
	public function getXeeCloudInfosUserCars($user_id) {
		if ($this->debug > 0) { echo "in : getXeeCloudInfosUserCars"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			$response = $this->client->fetch(str_replace('#', $user_id, XEE_URL_API_V4_USER_CARS));
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user['cars'] = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosUserCars($user_id);
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfosUserCars"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos Car Status
     *
     * @return array Infos Car Status
     */
	public function getXeeCloudInfosCarStatus($car_id, $key = 0) {
		if ($this->debug > 0) { echo "in : getXeeCloudInfosCarStatus"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			if ($this->debug > 0) { echo "in : getXeeCloudInfosCarStatus ".str_replace('#', $car_id, XEE_URL_API_V4_CAR_STATUS)."<br>\r\n"; }
			$response = $this->client->fetch(str_replace('#', $car_id, XEE_URL_API_V4_CAR_STATUS));
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user['cars'][$key]['status'] = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosCarStatus($car_id, $key);
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfosCarStatus"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos
     *
     * @return array Infos
     */
	public function getXeeCloudInfos() {
		if ($this->debug > 0) { echo "in : getXeeCloudInfos"."<br>\r\n"; }
		//$this->getXeeCloudAccessTokenRefresh();
		if (count($this->_info_user) == 0) {
			if ($this->debug > 0) { echo "appel : getXeeCloudInfosUser"."<br>\r\n"; }
			$response = $this->getXeeCloudInfosUser();
		}
		if ((count($this->_info_user) > 0) && ($this->getResponseCode($response) == 200)) {
			$user_id = 'me';//$this->_info_user['id'];
			if ((!isset($this->_info_user['cars'])) || (count($this->_info_user['cars']) == 0)) {
				$response = $this->getXeeCloudInfosUserCars($user_id);
			}
			$info_cars = $this->_info_user['cars'];
			foreach ($info_cars as $key => $value) {
				$info_car = $value;
				$car_id = $info_car['id'];
				if ($this->debug > 0) { echo "in : getXeeCloudInfos car_id:".$car_id."<br>\r\n"; }
				$response = $this->getXeeCloudInfosCarStatus($car_id, $key);
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfos"."<br>\r\n"; }
		return $this->_info_user;
	}

	public function convertInfosToNomLibelleValeur($car = 0) {
		//global $XeeTraducBase, $XeeSignalName;
		
		if ($this->debug > 0) { echo "in : getXeeCloudInfosNomLibelleValeur"."<br>\r\n"; }
		if ((is_int($car)) && ($car < 10)) {
			$car_num = $car;	// Pour compatibilite avec ancien id
		} else {
			$car_num = 0;
			foreach ($this->_info_user[cars] as $keyCar => $valueCar) {
				$lastaaffich = $valueCar['id'];
				if ($valueCar['id'] === $car) {
					$car_num = $keyCar;
					break;
				}
			}
		}
		$retour = array();
		foreach ($this->_info_user as $keyUser => $valueUser) {
			if (!is_array($valueUser)) {
				$Nom 		= 'user_'.$keyUser;
				$Libelle 	= 'Utilisateur ';
				if (isset($this->XeeTraducBase['FR'][$keyUser])) {
					$Libelle .= $this->XeeTraducBase['FR'][$keyUser];
				} else {
					$Libelle .= $keyUser;
				}
				$Valeur 	= $valueUser;
				$retour[$Nom] = array(); 
				$retour[$Nom]['Nom'] 	= $Nom;
				$retour[$Nom]['Libelle']= $Libelle;
				$retour[$Nom]['Valeur']	= $Valeur;
				$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
				$retour[$Nom]['TypeJeedom']= $this->XeeVarType2Jeedom[$this->XeeVarType[$Nom]];
				if ($retour[$Nom]['Type'] == 'date') 
					$retour[$Nom]['Valeur'] = $this->RFC3339toDate($retour[$Nom]['Valeur']);
			} else {
				if (($keyUser == 'cars') &&(count($valueUser) > 0)) {
					foreach ($valueUser[$car_num] as $keyCar => $valueCar) {
						if (!is_array($valueCar)) {
							$Nom 		= 'car_'.$keyCar;
							$Libelle 	= 'Vehicule ';
							if (isset($this->XeeTraducBase['FR'][$keyCar])) {
								$Libelle .= $this->XeeTraducBase['FR'][$keyCar];
							} else {
								$Libelle .= $keyCar;
							}
							$Valeur 	= $valueCar;
							$retour[$Nom] = array(); 
							$retour[$Nom]['Nom'] 	= $Nom;
							$retour[$Nom]['Libelle']= $Libelle;
							$retour[$Nom]['Valeur']	= $Valeur;
							$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
							$retour[$Nom]['TypeJeedom']= $this->XeeVarType2Jeedom[$this->XeeVarType[$Nom]];
							if ($retour[$Nom]['Type'] == 'date') 
								$retour[$Nom]['Valeur'] = $this->RFC3339toDate($retour[$Nom]['Valeur']);
						} else {
							foreach ($valueCar as $keyStatus => $valueStatus) {
								if ($keyStatus == 'signals') {
									foreach ($valueStatus as $keyStatusDetail => $valueStatusDetail) {
										$Nom 		= 'car_'.$keyCar.'_'.'signal'.'_'.$valueStatusDetail['name'];
										$Libelle 	= '';
										//$Libelle 	.= 'Vehicule ';
										/*if (isset($this->XeeTraducBase['FR'][$keyCar])) {
											$Libelle .= $this->XeeTraducBase['FR'][$keyCar].' ';
										}
										*/
										//$Libelle .= 'Signal'.' ';
										if (isset($this->XeeSignalName['FR'][$valueStatusDetail['name']])) {
											$Libelle .= $this->XeeSignalName['FR'][$valueStatusDetail['name']];
										} else {
											$Libelle .= $valueStatusDetail['name'];
										}
										
										if (isset($this->XeeVarType[$Nom])) {
											$Type .= $this->XeeVarType[$Nom];
										} else {
											$Type = 'float';
											if (substr($Nom, -3) == 'Sts') {
												$Type = 'binary';
											}
										}
										
										$NomValue = $Nom.'_value';
										$retour[$NomValue] = array(); 
										$retour[$NomValue]['Nom'] 		= $NomValue;
										$retour[$NomValue]['Libelle']	= $Libelle;//.' Valeur';
										$retour[$NomValue]['Valeur']	= $valueStatusDetail['value'];
										$retour[$NomValue]['Type']		= $Type;
										$retour[$NomValue]['TypeJeedom']= $this->XeeVarType2Jeedom[$Type];
										if ($retour[$NomValue]['Type'] == 'date') 
											$retour[$NomValue]['Valeur'] = $this->RFC3339toDate($retour[$NomValue]['Valeur']);
										$NomValue = $Nom.'_date';
										$retour[$NomValue] = array(); 
										$retour[$NomValue]['Nom'] 		= $NomValue;
										$retour[$NomValue]['Libelle']	= $Libelle.' Date';
										$retour[$NomValue]['Valeur']	= $valueStatusDetail['date'];
										$retour[$NomValue]['Type']		= 'date';
										$retour[$NomValue]['TypeJeedom']= $this->XeeVarType2Jeedom['date'];//'other';
										if ($retour[$NomValue]['Type'] == 'date') 
											$retour[$NomValue]['Valeur'] = $this->RFC3339toDate($retour[$NomValue]['Valeur']);
									}
								} else {
									if ( is_array($valueStatus) && !empty($valueStatus) ) {
										foreach ($valueStatus as $keyStatusDetail => $valueStatusDetail) {
											$Nom 		= 'car_'.$keyCar.'_'.$keyStatus.'_'.$keyStatusDetail;
											$Libelle 	= '';
											//$Libelle 	.= 'Vehicule ';
											/*if (isset($this->XeeTraducBase['FR'][$keyCar])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyCar].' ';
											}*/
											if (isset($this->XeeTraducBase['FR'][$keyStatus])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyStatus].' ';
											} else {
												$Libelle .= $keyStatus.' ';
											}
											if (isset($this->XeeTraducBase['FR'][$keyStatusDetail])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyStatusDetail];
											} else {
												$Libelle .= $keyStatusDetail;
											}
											$Valeur 	= $valueStatusDetail;
											$retour[$Nom] = array(); 
											$retour[$Nom]['Nom'] 	= $Nom;
											$retour[$Nom]['Libelle']= $Libelle;
											$retour[$Nom]['Valeur']	= $Valeur;
											$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
											$retour[$Nom]['TypeJeedom']= $this->XeeVarType2Jeedom[$this->XeeVarType[$Nom]];
											if ($retour[$Nom]['Type'] == 'date') 
												$retour[$Nom]['Valeur'] = $this->RFC3339toDate($retour[$Nom]['Valeur']);
										}
									}
								}									
							}
						}
					}
					
				}
			}
		}
		
		if ($this->debug > 0) { echo "out : getXeeCloudInfosNomLibelleValeur"."<br>\r\n"; }
		return $retour;
	}

    /**
     * Convert Date RFC3339 
     *
     * @return string Date YYYY-MM-DD HH:MM:SS
     */
	public function RFC3339toDate($RFC3339) {
		// YYYY-MM-DDTHH:MM:SSZ
		// YYYY-MM-DDTHH:MM:SS.xxxZ
		$Retour = $RFC3339;
		$Retour = str_replace("T00:00:00Z", "", $Retour);
		$Retour = substr($Retour, 0, 19);
		$Retour = str_replace("T", " ", $Retour);
		$Retour = str_replace("Z", "", $Retour);
	return $Retour;
	}

    /**
     * Get the Car Compatibility
     *
     * @return array Car Compatibility 
     */
	public function getXeeCarCompatibility($cardbId = 1) {
		if ($this->debug > 0) { echo "in : getXeeCarCompatibility"."<br>\r\n"; }
		$response = false;

		$credentials = $this->_client_id.':'.$this->_client_secret;
		echo $credentials."<br>\r\n";
		/*$context = stream_context_create(array(
			'http' => array(
				'headers' => "Authorization: Basic " . base64_encode($credentials),
			),
		));*/
		
		$url = str_replace('#', $cardbId, XEE_URL_API_COMPAT_V1_CARDB);
		echo $url."<br>\r\n";		
		//$result = file_get_contents($url, false, $context);	
		//$response = $result;
		
		$headers = array(
	        "Authorization: Basic " . base64_encode($credentials)
        );
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$result = curl_exec($ch); 

        if (curl_errno($ch)) {
        	print "Error: " . curl_error($ch);
        } else {
        	// Show me the result
        	//var_dump($data);
        	curl_close($ch);
        }
		$result = json_decode($result, true);
		$response = $result;
		if ($this->debug > 0) { echo "out : getXeeCarCompatibility"."<br>\r\n"; }
		return $response;
	}

	public function getResponseCode($response) {
		$code = 0;
		if (isset($response['code']))  {
			$code = $response['code'];
		}
		if ($this->debug > 0) { echo "code : ".$code."<br>\r\n"; }
		return $code;
	}
}

// ********************************************************************************
//
// Class XeeCloudAPI v3
//
// ********************************************************************************
class XeeCloudAPIv3 {
    /*     * *************************Attributs****************************** */

	public	$XeeTraducBase = array(
				'FR'	=> array(
					'user'					=> 'Utilisateur',
					'car'					=> 'Vehicule',
					'name'					=> 'Nom',
					'firstName'				=> 'Prenom',
					'lastName'				=> 'Nom',
					'gender'				=> 'Genre',
					'isLocationEnabled'		=> 'Localisation active',
					'nickName'				=> 'Surnom',
					'role'					=> 'Role',
					'birthDate'				=> 'Anniversaire',
					'licenseDeliveryDate'	=> 'Date du permis',
					'creationDate'			=> 'Date de creation',
					'lastUpdateDate'		=> 'Date de mise a jour',
					'brand'					=> 'Marque',
					'make'					=> 'Fabricant',
					'model'					=> 'Model',
					'year'					=> 'Annee',
					'plateNumber'			=> 'Immatriculation',
					'numberPlate'			=> 'Immatriculation',
					'timezone'				=> 'Timezone',
					'userId'				=> 'User Id',
					'cardbId'				=> 'cardbId',
					'deviceId'				=> 'XeeConnect Id',
					'accelerometer'			=> 'Accelerometre',
					'date'					=> 'Date',
					'location'				=> 'Localisation',
					'longitude'				=> 'Longitude',
					'latitude'				=> 'Latitude',
					'altitude'				=> 'Altitude',
					'nbSat'					=> 'Nb Sat',
					'heading'				=> 'Orientation',
					'satellites'			=> 'Satellites',
					'signals'				=> 'Signaux',
					'signal'				=> 'Signal',
					'reportDate'			=> 'Date de rapport',
					'value'					=> 'Valeur',
					'status'				=> 'Etat',
				),
				'EN'	=> array('user' => 'Utilisateur',
				)
			);

			//Description FR
			//	Ouvert/fermé 
	public	$XeeSignalName = array(
				'FR'	=> array(
					'RearRightDoorSts'			=> 'Porte arriere droite',
					'RearLeftDoorSts' 			=> 'Porte arriere gauche',
					'FrontRightDoorSts' 		=> 'Porte conducteur',
					'FrontLeftDoorSts' 			=> 'Porte passager',
					'TrunkSts' 					=> 'Coffre',
					'FuelCapSts' 				=> 'Bouchon de reservoir',
					'HoodSts' 					=> 'Capot',
					'FrontLeftSeatBeltSts'		=> 'Ceinture de securite conducteur',
					'FrontRightSeatBeltSts' 	=> 'Ceinture de securite passager',
					'PassAirbagSts' 			=> 'Airbag Passager',
			//	Fenêtres
					'FrontLeftWindowPosition' 	=> 'Fenetres avant gauche (Position)',
					'FrontLeftWindowSts' 		=> 'Fenetres avant gauche (Status)',
					'FrontRightWindowPosition'	=> 'Fenetres avant droite (Position)',
					'FrontRightWindowSts' 		=> 'Fenetres avant droite (Status)',
					'RearLeftWindowPosition' 	=> 'Fenetres arriere gauche (Position)',
					'RearLeftWindowSts' 		=> 'Fenetres arriere gauche (Status)',
					'RearRightWindowPosition' 	=> 'Fenetres arriere droite (Position)',
					'RearRightWindowSts' 		=> 'Fenetres arriere droite (Status)',
					'WindowsLockSts' 			=> 'Fenetres bloquees par le conducteur',
			//	Phares
					'RightIndicatorSts' 		=> 'Clignotant droit',
					'LeftIndicatorSts' 			=> 'Clignotant gauche',
					'HazardSts' 				=> 'Warning',
					'LowBeamSts' 				=> 'Feux de croisement',
					'HighBeamSts' 				=> 'Feux de position',
					'HeadLightSts' 				=> 'Feux de route',
//					'HeadLightSts' 				=> 'Feux de route / Plein phares',
					'FrontFogLightSts' 			=> 'Feux de brouillard avant',
					'RearFogLightSts' 			=> 'Feux de brouillard arrière',
			//	Éssuie glaces
					'ManualWiperSts' 			=> 'Essuie glaces avant manuel',
					'IntermittentWiperSts' 		=> 'Essuie glaces avant intermittent',
					'LowSpeedWiperSts' 			=> 'Essuie glaces avant lent',
					'HighSpeedWiperSts' 		=> 'Essuie glaces avant rapide',
					'ManualRearWiperSts' 		=> 'Essuie glaces arriere',
					'AutoRearWiperSts' 			=> 'Essuie glaces automatique',
			//	Pédales 
					'ClutchPedalPosition' 		=> 'Position Pedale Embrayage',
					'ClutchPedalSts' 			=> 'Etat Pedale Embrayage',
					'ThrottlePedalPosition' 	=> 'Pedale d\'accelerateur position',
					'ThrottlePedalSts' 			=> 'Status Pedale d\'accelerateur',
					'BrakePedalPosition'		=> 'Pedale de frein position',
					'BrakePedalSts' 			=> 'Status pedale de frein',
					'HandBrakeSts' 				=> 'Frein à main',
			//	Vitesse Véhicule 
					'VehiculeSpeed' 			=> 'Vitesse instantanee',
					'EngineSpeed' 				=> 'Vitesse moteur',
					'RearRightWheelSpeed' 		=> 'Vitesse roue arrière droite',
					'RearLeftWheelSpeed' 		=> 'Vitesse roue arrière gauche',
					'FrontRightWheelSpeed'		=> 'Vitesse roue avant droite',
					'FrontLeftWheelSpeed' 		=> 'Vitesse roue avant gauche',
			//	Volant 
					'SteeringWheelAngle'		=> 'Angle du volant',
					'SteeringWheelSide' 		=> 'Cote du volant',
			//	Informations 
					'ReverseGearSts' 			=> 'Marche arriere enclenchee',
					'GearPosition' 				=> 'Position levier de vitesse',
					'LockSts' 					=> 'Verrouillee',
//					'LockSts' 					=> 'Voiture Verrouillee/Deverouille',
					'KeySts' 					=> 'Enclenchement cle',
					'IgnitionSts' 				=> 'Apres contact',
					'BatteryVoltage' 			=> 'Tension Batterie',
					'FuelLevel' 				=> 'Niveau d\'essence',
					'Odometer' 					=> 'Kilometrage de la voiture',
					'InteriorLightSts'			=> 'Lumieres interieures',
					'SunRoofSts' 				=> 'Toit Ouvrant',
					'DriveMode' 				=> 'Mode de conduite',
					'RadioSts' 					=> 'Radio',
					'CruiseControlSts' 			=> 'Regulateur de vitesse active',
					'AirCondSts' 				=> 'Climatisation',
					'AirCondSwitchSts' 			=> 'Climatisation',
					'VentilationSts' 			=> 'Ventilation',
					'ComputedEngineState'		=> 'Computed Engine State',
					'CoolantPressure'			=> 'Pression liquide refroidissement',
					'OutdoorTemp'				=> 'Temperature Exterieur',
					'IndoorTemp'				=> 'Temperature Interieur',
					'AutoWiperSts'				=> 'Essuie-glace Automatique',
					'RearWiperSts'				=> 'Essuie-glace arriere'
				),
				'EN'	=> array(
					'RearRightDoorSts' 			=> 'Rear Right Door status',
				)
			);

	public	$XeeVarType = array(
				'user_id'					=> 'integer',
				'user_name'					=> 'numeric',
				'user_firstName'			=> 'string',
				'user_lastName'				=> 'string',
				'user_gender'				=> 'string',
				'user_isLocationEnabled'	=> 'boolean',
				'user_nickName'				=> 'string',
				'user_role'					=> 'string',
				'user_birthDate'			=> 'date',
				'user_licenseDeliveryDate'	=> 'date',
				'user_creationDate'			=> 'date',
				'user_lastUpdateDate'		=> 'date',
				'user_email'				=> 'string',
				'car_id'					=> 'string',
				'car_name'					=> 'string',
				'car_make'					=> 'string',
				'car_brand'					=> 'string',
				'car_model'					=> 'string',
				'car_year'					=> 'integer',
				'car_plateNumber'			=> 'string',
				'car_numberPlate'			=> 'string',
				'car_timezone'				=> 'string',
				'car_userId'				=> 'integer',
				'car_cardbId'				=> 'integer',
				'car_deviceId'				=> 'string',
				'car_creationDate'			=> 'date',
				'car_lastUpdateDate'		=> 'date',
				'car_status_accelerometer_id'			=> 'integer',
				'car_status_accelerometer_x'			=> 'float',
				'car_status_accelerometer_y'			=> 'float',
				'car_status_accelerometer_z'			=> 'float',
				'car_status_accelerometer_date'			=> 'date',
				'car_status_accelerometer_driverId'		=> 'integer',
				'car_status_location_id'				=> 'integer',
				'car_status_location_date'				=> 'date',
				'car_status_location_longitude'			=> 'float',
				'car_status_location_latitude'			=> 'float',
				'car_status_location_altitude'			=> 'float',
				'car_status_location_nbSat'				=> 'integer',
				'car_status_location_driverId'			=> 'integer',
				'car_status_location_heading'			=> 'float',
				'car_status_location_satellites'		=> 'integer',
				'car_status_signal_AirCondSts_value'				=> 'boolean',
				'car_status_signal_AirCondSwitchSts_value'			=> 'boolean',
				'car_status_signal_LockSts_value'					=> 'boolean',
				'car_status_signal_HeadLightSts_value'				=> 'boolean',
				'car_status_signal_HighBeamSts_value'				=> 'boolean',
				'car_status_signal_VehiculeSpeed_value'				=> 'float',
				'car_status_signal_EngineSpeed_value'				=> 'float',
				'car_status_signal_RearRightDoorSts_value'			=> 'boolean',
				'car_status_signal_RearLeftDoorSts_value'			=> 'boolean',
				'car_status_signal_FrontRightDoorSts_value'			=> 'boolean',
				'car_status_signal_FrontLeftDoorSts_value'			=> 'boolean',
				'car_status_signal_TrunkSts_value'					=> 'boolean',
				'car_status_signal_FuelCapSts_value'				=> 'boolean',
				'car_status_signal_HoodSts_value'					=> 'boolean',
				'car_status_signal_FrontLeftSeatBeltSts_value'		=> 'boolean',
				'car_status_signal_FrontRightSeatBeltSts_value'		=> 'boolean',
				'car_status_signal_PassAirbagSts_value'				=> 'boolean',
				'car_status_signal_FrontLeftWindowPosition_value'	=> 'float',
				'car_status_signal_FrontLeftWindowSts_value'		=> 'boolean',
				'car_status_signal_FrontRightWindowPosition_value'	=> 'float',
				'car_status_signal_FrontRightWindowSts_value'		=> 'boolean',
				'car_status_signal_RearLeftWindowPosition_value'	=> 'float',
				'car_status_signal_RearLeftWindowSts_value'			=> 'boolean',
				'car_status_signal_RearRightWindowPosition_value'	=> 'float',
				'car_status_signal_RearRightWindowSts_value'		=> 'boolean',
				'car_status_signal_WindowsLockSts_value'			=> 'boolean',
				'car_status_signal_RightIndicatorSts_value'			=> 'boolean',
				'car_status_signal_LeftIndicatorSts_value'			=> 'boolean',
				'car_status_signal_HazardSts_value'					=> 'boolean',
				'car_status_signal_LowBeamSts_value'				=> 'boolean',
				'car_status_signal_HighBeamSts_value'				=> 'boolean',
				'car_status_signal_FrontFogLightSts_value'			=> 'boolean',
				'car_status_signal_RearFogLightSts_value'			=> 'boolean',
				'car_status_signal_ManualWiperSts_value'			=> 'boolean',
				'car_status_signal_IntermittentWiperSts_value'		=> 'boolean',
				'car_status_signal_LowSpeedWiperSts_value'			=> 'boolean',
				'car_status_signal_HighSpeedWiperSts_value'			=> 'boolean',
				'car_status_signal_ManualRearWiperSts_value'		=> 'boolean',
				'car_status_signal_AutoRearWiperSts_value'			=> 'boolean',
				'car_status_signal_ClutchPedalPosition_value'		=> 'float',
				'car_status_signal_ClutchPedalSts_value'			=> 'boolean',
				'car_status_signal_ThrottlePedalPosition_value'		=> 'float',
				'car_status_signal_ThrottlePedalSts_value'			=> 'boolean',
				'car_status_signal_BrakePedalPosition_value'		=> 'float',
				'car_status_signal_BrakePedalSts_value'				=> 'boolean',
				'car_status_signal_HandBrakeSts_value'				=> 'boolean',
				'car_status_signal_RearRightWheelSpeed_value'		=> 'float',
				'car_status_signal_RearLeftWheelSpeed_value'		=> 'float',
				'car_status_signal_FrontRightWheelSpeed_value'		=> 'float',
				'car_status_signal_FrontLeftWheelSpeed_value'		=> 'float',
				'car_status_signal_SteeringWheelAngle_value'		=> 'float',
				'car_status_signal_SteeringWheelSide_value'			=> 'float',
				'car_status_signal_ReverseGearSts_value'			=> 'boolean',
				'car_status_signal_GearPosition_value'				=> 'float',
				'car_status_signal_KeySts_value'					=> 'boolean',
				'car_status_signal_IgnitionSts_value'				=> 'boolean',
				'car_status_signal_BatteryVoltage_value'			=> 'float',
				'car_status_signal_FuelLevel_value'					=> 'float',
				'car_status_signal_Odometer_value'					=> 'float',
				'car_status_signal_InteriorLightSts_value'			=> 'boolean',
				'car_status_signal_SunRoofSts_value'				=> 'boolean',
				'car_status_signal_DriveMode_value'					=> 'float',
				'car_status_signal_RadioSts_value'					=> 'boolean',
				'car_status_signal_CruiseControlSts_value'			=> 'boolean',
				'car_status_signal_VentilationSts_value'			=> 'boolean',
				'car_status_signal_ComputedEngineState_value'		=> 'boolean',
				'car_status_signal_CoolantPressure_value'			=> 'float',
				'car_status_signal_OutdoorTemp_value'				=> 'float',
				'car_status_signal_IndoorTemp_value'				=> 'float',
				'car_status_signal_AutoWiperSts_value'				=> 'boolean',
				'car_status_signal_RearWiperSts_value'				=> 'boolean'
			);

	public	$XeeVarType2Jeedom = array(
				'numeric'	=> 'numeric',
				'integer'	=> 'numeric',
				'float'		=> 'numeric',
				'binary'	=> 'binary',
				'boolean'	=> 'binary',
				'string'	=> 'string',
				'date'		=> 'string',
			);

/*	public	$XeeErrorAuth = array(
				'401'	=> array(
					'Reason'					=> 'Utilisateur',
					'Message'					=> 'Vehicule',
					'Tip'					=> 'Nom',
				),
				'EN'	=> array('user' => 'Utilisateur',
				)
			);
*/			
			
	private $_client = '';
	private $_xeecloudData = array();
	private $_info_user = array();
	
	private $_client_id = '';
	private $_client_secret = '';

	private $_access_token_ok = false;
	private $_refresh_token_ok = false;

	private $_access_token ;
	private $_refresh_token;
	private $_redirect_url;

	public $debug = 0;
    /**
     * Construct
     *
     * @param string $client_id Client ID
     * @param string $client_secret Client Secret
     * @param string $access_token 
     * @param string $refresh_token 
     * @param string $redirect_url 
     * @return void
     */
    public function __construct($client_id, $client_secret, $access_token = '', $refresh_token = '', $redirect_url = '')
    {
        $this->_redirect_url = $redirect_url;
		$this->_client_id = $client_id;
		$this->_client_secret = $client_secret;
		$this->client = new OAuth2\Client($client_id, $client_secret, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
		$this->setAccessToken($access_token);
		//$this->setAccessTokenType(1);
		$this->setRefreshToken($refresh_token);
    }

    /**
     * Set redirect_url
     *
     * @param string $redirect_url Set the redirect_url
     * @return void
     */
	public function setRedirectURL($redirect_url) {
		return $this->_redirect_url = $redirect_url;
	}

    /**
     * Get the client Id
     *
     * @return string Client ID
     */
	public function getClientId() {
		return $this->client->getClientId();
	}

    /**
     * Get the client Secret
     *
     * @return string Client Secret
     */
	public function getClientSecret() {
		return $this->client->getClientSecret();
	}

    /**
     * Set Access_Token
     *
     * @param string $access_token Set the Access_Token
     * @return void
     */
	public function setAccessToken($access_token) {
		$this->client->setAccessToken($access_token);
		$this->_access_token_ok = ($access_token != '');
		$this->_access_token = $access_token;
	}

    /**
     * Get the Access_Token
     *
     * @return string Access_Token
     */
	public function getAccessToken() {
		return $this->_access_token;
	}

    /**
     * Set Refresh_Token
     *
     * @param string $client_secret Set the Refresh_Token
     * @return void
     */
	public function setRefreshToken($refresh_token) {
		$this->_refresh_token_ok = ($refresh_token != '');
		$this->_refresh_token = $refresh_token;
	}

    /**
     * Get the Refresh_Token
     *
     * @return string Refresh_Token
     */
	public function getRefreshToken() {
		return $this->_refresh_token;
	}

    /**
     * Get the AccessToken
     *
     * @return array AccessToken
	 
	 if (!isset($_GET['code'])) {
		$code = '';
	 } else {
		$code = $_GET['code'];
	 }
	 if (!isset($_GET['state'])) {
		$state = '';
	 } else {
		$state = $_GET['state'];
	 }
     */
	public function getXeeCloudAccessTokenForm($code = '', $state = '') {
		if ($code == '') {
			if ($state == '') {
				$params = array();
			}
			else {
				$params = array('state' => $state);
			}
			$auth_url = $this->client->getAuthenticationUrl(XEE_URL_API_V3_AUTH, $this->_redirect_url, $params);
			header('Location: ' . $auth_url);
			die('Redirect');
		}
		else {
			$params = array('code' => $code);
			$response = $this->client->getAccessToken(XEE_URL_API_V3_ACCESS_TOKEN, OAuth2\Client::GRANT_TYPE_AUTH_CODE, $params);
			if ($this->getResponseCode($response) == 200) {
				$this->setAccessToken($response['result']['access_token']);
				$this->setRefreshToken($response['result']['refresh_token']);
			} else {
				$this->_access_token_ok = false;
				$this->_refresh_token_ok = false;
			}
			return $response;
		}
	}

    /**
     * Get the AccessToken
     *
     * @return array AccessToken
     */
	public function getXeeCloudAccessTokenRefresh($refresh_token = '') {
		if ($this->debug > 0) { echo "in : getXeeCloudAccessTokenRefresh"."<br>\r\n"; }
		if ($refresh_token !== '') {
			$this->setRefreshToken($refresh_token);
		}
		$refresh_token = $this->getRefreshToken();
		$params = array('refresh_token' => $refresh_token);
		$response = $this->client->getAccessToken(XEE_URL_API_V3_ACCESS_TOKEN, OAuth2\Client::GRANT_TYPE_REFRESH_TOKEN, $params);
		if ($this->getResponseCode($response) == 200) {
			$this->setAccessToken($response['result']['access_token']);
			$this->setRefreshToken($response['result']['refresh_token']);
		} else {
			$this->_refresh_token_ok = false;
			if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; } 
		}
		if ($this->debug > 0) { echo "out : getXeeCloudAccessTokenRefresh"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos User
     *
     * @return array Infos User
     */
	public function getXeeCloudInfosUser($user_id = -1) {
		if ($this->debug > 0) { echo "-in : getXeeCloudInfosUser"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			$this->client->setAccessTokenType(1);
			if ($user_id == -1) {
				$response = $this->client->fetch(XEE_URL_API_V3_USER_CURRENT);
			} else {
				$response = $this->client->fetch(str_replace('#', $user_id, XEE_URL_API_V3_USER));
			}
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosUser();
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "-out : getXeeCloudInfosUser"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos User Cars
     *
     * @return array Infos User Cars
     */
	public function getXeeCloudInfosUserCars($user_id) {
		if ($this->debug > 0) { echo "in : getXeeCloudInfosUserCars"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			$response = $this->client->fetch(str_replace('#', $user_id, XEE_URL_API_V3_USER_CARS));
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user['cars'] = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosUserCars($user_id);
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfosUserCars"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos Car Status
     *
     * @return array Infos Car Status
     */
	public function getXeeCloudInfosCarStatus($car_id, $key = 0) {
		if ($this->debug > 0) { echo "in : getXeeCloudInfosCarStatus"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			if ($this->debug > 0) { echo "in : getXeeCloudInfosCarStatus ".str_replace('#', $car_id, XEE_URL_API_V3_CAR_STATUS)."<br>\r\n"; }
			$response = $this->client->fetch(str_replace('#', $car_id, XEE_URL_API_V3_CAR_STATUS));
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user['cars'][$key]['status'] = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosCarStatus($car_id, $key);
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfosCarStatus"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos
     *
     * @return array Infos
     */
	public function getXeeCloudInfos() {
		if ($this->debug > 0) { echo "in : getXeeCloudInfos"."<br>\r\n"; }
		//$this->getXeeCloudAccessTokenRefresh();
		if (count($this->_info_user) == 0) {
			if ($this->debug > 0) { echo "appel : getXeeCloudInfosUser"."<br>\r\n"; }
			$response = $this->getXeeCloudInfosUser();
		}
		if ((count($this->_info_user) > 0) && ($this->getResponseCode($response) == 200)) {
			$user_id = $this->_info_user['id'];
			if ((!isset($this->_info_user['cars'])) || (count($this->_info_user['cars']) == 0)) {
				$response = $this->getXeeCloudInfosUserCars($user_id);
			}
			$info_cars = $this->_info_user['cars'];
			foreach ($info_cars as $key => $value) {
				$info_car = $value;
				$car_id = $info_car['id'];
				if ($this->debug > 0) { echo "in : getXeeCloudInfos car_id:".$car_id."<br>\r\n"; }
				$response = $this->getXeeCloudInfosCarStatus($car_id, $key);
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfos"."<br>\r\n"; }
		return $this->_info_user;
	}

	public function convertInfosToNomLibelleValeur($car = 0) {
		//global $XeeTraducBase, $XeeSignalName;
		
		if ($this->debug > 0) { echo "in : getXeeCloudInfosNomLibelleValeur"."<br>\r\n"; }
		$retour = array();
		foreach ($this->_info_user as $keyUser => $valueUser) {
			if (!is_array($valueUser)) {
				$Nom 		= 'user_'.$keyUser;
				$Libelle 	= 'Utilisateur ';
				if (isset($this->XeeTraducBase['FR'][$keyUser])) {
					$Libelle .= $this->XeeTraducBase['FR'][$keyUser];
				} else {
					$Libelle .= $keyUser;
				}
				$Valeur 	= $valueUser;
				$retour[$Nom] = array(); 
				$retour[$Nom]['Nom'] 	= $Nom;
				$retour[$Nom]['Libelle']= $Libelle;
				$retour[$Nom]['Valeur']	= $Valeur;
				$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
				$retour[$Nom]['TypeJeedom']= $this->XeeVarType2Jeedom[$this->XeeVarType[$Nom]];
				if ($retour[$Nom]['Type'] == 'date') 
					$retour[$Nom]['Valeur'] = $this->RFC3339toDate($retour[$Nom]['Valeur']);
			} else {
				if (($keyUser == 'cars') &&(count($valueUser) > 0)) {
					foreach ($valueUser[$car] as $keyCar => $valueCar) {
						if (!is_array($valueCar)) {
							$Nom 		= 'car_'.$keyCar;
							$Libelle 	= 'Vehicule ';
							if (isset($this->XeeTraducBase['FR'][$keyCar])) {
								$Libelle .= $this->XeeTraducBase['FR'][$keyCar];
							} else {
								$Libelle .= $keyCar;
							}
							$Valeur 	= $valueCar;
							$retour[$Nom] = array(); 
							$retour[$Nom]['Nom'] 	= $Nom;
							$retour[$Nom]['Libelle']= $Libelle;
							$retour[$Nom]['Valeur']	= $Valeur;
							$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
							$retour[$Nom]['TypeJeedom']= $this->XeeVarType2Jeedom[$this->XeeVarType[$Nom]];
							if ($retour[$Nom]['Type'] == 'date') 
								$retour[$Nom]['Valeur'] = $this->RFC3339toDate($retour[$Nom]['Valeur']);
						} else {
							foreach ($valueCar as $keyStatus => $valueStatus) {
								if ($keyStatus == 'signals') {
									foreach ($valueStatus as $keyStatusDetail => $valueStatusDetail) {
										$Nom 		= 'car_'.$keyCar.'_'.'signal'.'_'.$valueStatusDetail['name'];
										$Libelle 	= '';
										//$Libelle 	.= 'Vehicule ';
										/*if (isset($this->XeeTraducBase['FR'][$keyCar])) {
											$Libelle .= $this->XeeTraducBase['FR'][$keyCar].' ';
										}
										*/
										//$Libelle .= 'Signal'.' ';
										if (isset($this->XeeSignalName['FR'][$valueStatusDetail['name']])) {
											$Libelle .= $this->XeeSignalName['FR'][$valueStatusDetail['name']];
										} else {
											$Libelle .= $valueStatusDetail['name'];
										}
										
										if (isset($this->XeeVarType[$Nom])) {
											$Type .= $this->XeeVarType[$Nom];
										} else {
											$Type = 'float';
											if (substr($Nom, -3) == 'Sts') {
												$Type = 'binary';
											}
										}
										
										$NomValue = $Nom.'_value';
										$retour[$NomValue] = array(); 
										$retour[$NomValue]['Nom'] 		= $NomValue;
										$retour[$NomValue]['Libelle']	= $Libelle;//.' Valeur';
										$retour[$NomValue]['Valeur']	= $valueStatusDetail['value'];
										$retour[$NomValue]['Type']		= $Type;
										$retour[$NomValue]['TypeJeedom']= $this->XeeVarType2Jeedom[$Type];
										if ($retour[$NomValue]['Type'] == 'date') 
											$retour[$NomValue]['Valeur'] = $this->RFC3339toDate($retour[$NomValue]['Valeur']);
										$NomValue = $Nom.'_date';
										$retour[$NomValue] = array(); 
										$retour[$NomValue]['Nom'] 		= $NomValue;
										$retour[$NomValue]['Libelle']	= $Libelle.' Date';
										$retour[$NomValue]['Valeur']	= $valueStatusDetail['date'];
										$retour[$NomValue]['Type']		= 'date';
										$retour[$NomValue]['TypeJeedom']= $this->XeeVarType2Jeedom['date'];//'other';
										if ($retour[$NomValue]['Type'] == 'date') 
											$retour[$NomValue]['Valeur'] = $this->RFC3339toDate($retour[$NomValue]['Valeur']);
									}
								} else {
									if ( is_array($valueStatus) && !empty($valueStatus) ) {
										foreach ($valueStatus as $keyStatusDetail => $valueStatusDetail) {
											$Nom 		= 'car_'.$keyCar.'_'.$keyStatus.'_'.$keyStatusDetail;
											$Libelle 	= '';
											//$Libelle 	.= 'Vehicule ';
											/*if (isset($this->XeeTraducBase['FR'][$keyCar])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyCar].' ';
											}*/
											if (isset($this->XeeTraducBase['FR'][$keyStatus])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyStatus].' ';
											} else {
												$Libelle .= $keyStatus.' ';
											}
											if (isset($this->XeeTraducBase['FR'][$keyStatusDetail])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyStatusDetail];
											} else {
												$Libelle .= $keyStatusDetail;
											}
											$Valeur 	= $valueStatusDetail;
											$retour[$Nom] = array(); 
											$retour[$Nom]['Nom'] 	= $Nom;
											$retour[$Nom]['Libelle']= $Libelle;
											$retour[$Nom]['Valeur']	= $Valeur;
											$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
											$retour[$Nom]['TypeJeedom']= $this->XeeVarType2Jeedom[$this->XeeVarType[$Nom]];
											if ($retour[$Nom]['Type'] == 'date') 
												$retour[$Nom]['Valeur'] = $this->RFC3339toDate($retour[$Nom]['Valeur']);
										}
									}
								}									
							}
						}
					}
					
				}
			}
		}
		
		if ($this->debug > 0) { echo "out : getXeeCloudInfosNomLibelleValeur"."<br>\r\n"; }
		return $retour;
	}

    /**
     * Convert Date RFC3339 
     *
     * @return string Date YYYY-MM-DD HH:MM:SS
     */
	public function RFC3339toDate($RFC3339) {
		// YYYY-MM-DDTHH:MM:SSZ
		// YYYY-MM-DDTHH:MM:SS.xxxZ
		$Retour = $RFC3339;
		$Retour = str_replace("T00:00:00Z", "", $Retour);
		$Retour = substr($Retour, 0, 19);
		$Retour = str_replace("T", " ", $Retour);
		$Retour = str_replace("Z", "", $Retour);
	return $Retour;
	}

    /**
     * Get the Car Compatibility
     *
     * @return array Car Compatibility 
     */
	public function getXeeCarCompatibility($cardbId = 1) {
		if ($this->debug > 0) { echo "in : getXeeCarCompatibility"."<br>\r\n"; }
		$response = false;

		$credentials = $this->_client_id.':'.$this->_client_secret;
		echo $credentials."<br>\r\n";
		/*$context = stream_context_create(array(
			'http' => array(
				'headers' => "Authorization: Basic " . base64_encode($credentials),
			),
		));*/
		
		$url = str_replace('#', $cardbId, XEE_URL_API_COMPAT_V1_CARDB);
		echo $url."<br>\r\n";		
		//$result = file_get_contents($url, false, $context);	
		//$response = $result;
		
		$headers = array(
	        "Authorization: Basic " . base64_encode($credentials)
        );
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$result = curl_exec($ch); 

        if (curl_errno($ch)) {
        	print "Error: " . curl_error($ch);
        } else {
        	// Show me the result
        	//var_dump($data);
        	curl_close($ch);
        }
		$result = json_decode($result, true);
		$response = $result;
		if ($this->debug > 0) { echo "out : getXeeCarCompatibility"."<br>\r\n"; }
		return $response;
	}

	public function getResponseCode($response) {
		$code = 0;
		if (isset($response['code']))  {
			$code = $response['code'];
		}
		if ($this->debug > 0) { echo "code : ".$code."<br>\r\n"; }
		return $code;
	}
}

// ********************************************************************************
//
// Class XeeCloudAPI v1
//
// ********************************************************************************

class XeeCloudAPIv1 {
    /*     * *************************Attributs****************************** */

	public	$XeeTraducBase = array(
				'FR'	=> array(
					'user'					=> 'Utilisateur',
					'car'					=> 'Vehicule',
					'name'					=> 'Nom',
					'firstName'				=> 'Prenom',
					'gender'				=> 'Genre',
					'nickName'				=> 'Surnom',
					'role'					=> 'Role',
					'birthDate'				=> 'Anniversaire',
					'licenseDeliveryDate'	=> 'Date du permis',
					'brand'					=> 'Marque',
					'model'					=> 'Model',
					'year'					=> 'Annee',
					'plateNumber'			=> 'Immatriculation',
					'cardbId'				=> 'cardbId',
					'accelerometer'			=> 'Accelerometre',
					'date'					=> 'Date',
					'location'				=> 'Localisation',
					'longitude'				=> 'Longitude',
					'latitude'				=> 'Latitude',
					'altitude'				=> 'Altitude',
					'nbSat'					=> 'Nb Sat',
					'heading'				=> 'Orientation',
					'signals'				=> 'Signaux',
					'signal'				=> 'Signal',
					'reportDate'			=> 'Date de rapport',
					'value'					=> 'Valeur',
					'status'				=> 'Etat',
				),
				'EN'	=> array('user' => 'Utilisateur',
				)
			);

			//Description FR
			//	Ouvert/fermé 
	public	$XeeSignalName = array(
				'FR'	=> array(
					'RearRightDoorSts'			=> 'Porte arriere droite',
					'RearLeftDoorSts' 			=> 'Porte arriere gauche',
					'FrontRightDoorSts' 		=> 'Porte conducteur',
					'FrontLeftDoorSts' 			=> 'Porte passager',
					'TrunkSts' 					=> 'Coffre',
					'FuelCapSts' 				=> 'Bouchon de reservoir',
					'HoodSts' 					=> 'Capot',
					'FrontLeftSeatBeltSts'		=> 'Ceinture de securite conducteur',
					'FrontRightSeatBeltSts' 	=> 'Ceinture de securite passager',
					'PassAirbagSts' 			=> 'Airbag Passager',
			//	Fenêtres
					'FrontLeftWindowPosition' 	=> 'Fenetres avant gauche (Position)',
					'FrontLeftWindowSts' 		=> 'Fenetres avant gauche (Status)',
					'FrontRightWindowPosition'	=> 'Fenetres avant droite (Position)',
					'FrontRightWindowSts' 		=> 'Fenetres avant droite (Status)',
					'RearLeftWindowPosition' 	=> 'Fenetres arriere gauche (Position)',
					'RearLeftWindowSts' 		=> 'Fenetres arriere gauche (Status)',
					'RearRightWindowPosition' 	=> 'Fenetres arriere droite (Position)',
					'RearRightWindowSts' 		=> 'Fenetres arriere droite (Status)',
					'WindowsLockSts' 			=> 'Fenetres bloquees par le conducteur',
			//	Phares
					'RightIndicatorSts' 		=> 'Clignotant droit',
					'LeftIndicatorSts' 			=> 'Clignotant gauche',
					'HazardSts' 				=> 'Warning',
					'LowBeamSts' 				=> 'Feux de croisement',
					'HighBeamSts' 				=> 'Feux de position',
					'HeadLightSts' 				=> 'Feux de route',
//					'HeadLightSts' 				=> 'Feux de route / Plein phares',
					'FrontFogLightSts' 			=> 'Feux de brouillard avant',
					'RearFogLightSts' 			=> 'Feux de brouillard arrière',
			//	Éssuie glaces
					'ManualWiperSts' 			=> 'Essuie glaces avant manuel',
					'IntermittentWiperSts' 		=> 'Essuie glaces avant intermittent',
					'LowSpeedWiperSts' 			=> 'Essuie glaces avant lent',
					'HighSpeedWiperSts' 		=> 'Essuie glaces avant rapide',
					'ManualRearWiperSts' 		=> 'Essuie glaces arriere',
					'AutoRearWiperSts' 			=> 'Essuie glaces automatique',
			//	Pédales 
					'ClutchPedalPosition' 		=> 'Position Pedale Embrayage',
					'ClutchPedalSts' 			=> 'Etat Pedale Embrayage',
					'ThrottlePedalPosition' 	=> 'Pedale d\'accelerateur position',
					'ThrottlePedalSts' 			=> 'Status Pedale d\'accelerateur',
					'BrakePedalPosition'		=> 'Pedale de frein position',
					'BrakePedalSts' 			=> 'Status pedale de frein',
					'HandBrakeSts' 				=> 'Frein à main',
			//	Vitesse Véhicule 
					'VehiculeSpeed' 			=> 'Vitesse instantanee',
					'EngineSpeed' 				=> 'Vitesse moteur',
					'RearRightWheelSpeed' 		=> 'Vitesse roue arrière droite',
					'RearLeftWheelSpeed' 		=> 'Vitesse roue arrière gauche',
					'FrontRightWheelSpeed'		=> 'Vitesse roue avant droite',
					'FrontLeftWheelSpeed' 		=> 'Vitesse roue avant gauche',
			//	Volant 
					'SteeringWheelAngle'		=> 'Angle du volant',
					'SteeringWheelSide' 		=> 'Cote du volant',
			//	Informations 
					'ReverseGearSts' 			=> 'Marche arriere enclenchee',
					'GearPosition' 				=> 'Position levier de vitesse',
					'LockSts' 					=> 'Verrouillee',
//					'LockSts' 					=> 'Voiture Verrouillee/Deverouille',
					'KeySts' 					=> 'Enclenchement cle',
					'IgnitionSts' 				=> 'Apres contact',
					'BatteryVoltage' 			=> 'Tension Batterie',
					'FuelLevel' 				=> 'Niveau d\'essence',
					'Odometer' 					=> 'Kilometrage de la voiture',
					'InteriorLightSts'			=> 'Lumieres interieures',
					'SunRoofSts' 				=> 'Toit Ouvrant',
					'DriveMode' 				=> 'Mode de conduite',
					'RadioSts' 					=> 'Radio',
					'CruiseControlSts' 			=> 'Regulateur de vitesse active',
					'AirCondSts' 				=> 'Climatisation',
					'AirCondSwitchSts' 			=> 'Climatisation',
					'VentilationSts' 			=> 'Ventilation',
					'ComputedEngineState'		=> 'Computed Engine State',
					'CoolantPressure'			=> 'Pression liquide refroidissement',
					'OutdoorTemp'				=> 'Temperature Exterieur',
					'IndoorTemp'				=> 'Temperature Interieur',
					'AutoWiperSts'				=> 'Essuie-glace Automatique',
					'RearWiperSts'				=> 'Essuie-glace arriere'
				),
				'EN'	=> array(
					'RearRightDoorSts' 			=> 'Rear Right Door status',
				)
			);

	public	$XeeVarType = array(
				'user_id'					=> 'numeric',
				'user_name'					=> 'numeric',
				'user_firstName'			=> 'string',
				'user_gender'				=> 'numeric',
				'user_nickName'				=> 'string',
				'user_role'					=> 'string',
				'user_birthDate'			=> 'string',
				'user_licenseDeliveryDate'	=> 'string',
				'car_id'					=> 'numeric',
				'car_name'					=> 'string',
				'car_brand'					=> 'string',
				'car_model'					=> 'string',
				'car_year'					=> 'string',
				'car_plateNumber'			=> 'string',
				'car_cardbId'				=> 'string',
				'car_status_accelerometer_id'			=> 'numeric',
				'car_status_accelerometer_x'			=> 'numeric',
				'car_status_accelerometer_y'			=> 'numeric',
				'car_status_accelerometer_z'			=> 'numeric',
				'car_status_accelerometer_date'			=> 'string',
				'car_status_accelerometer_driverId'		=> 'numeric',
				'car_status_location_id'				=> 'numeric',
				'car_status_location_date'				=> 'string',
				'car_status_location_longitude'			=> 'string',
				'car_status_location_latitude'			=> 'string',
				'car_status_location_altitude'			=> 'numeric',
				'car_status_location_nbSat'				=> 'numeric',
				'car_status_location_driverId'			=> 'numeric',
				'car_status_location_heading'			=> 'numeric',
				'car_status_signal_AirCondSts_value'				=> 'binary',
				'car_status_signal_AirCondSwitchSts_value'			=> 'binary',
				'car_status_signal_LockSts_value'					=> 'binary',
				'car_status_signal_HeadLightSts_value'				=> 'binary',
				'car_status_signal_HighBeamSts_value'				=> 'binary',
				'car_status_signal_VehiculeSpeed_value'				=> 'numeric',
				'car_status_signal_EngineSpeed_value'				=> 'numeric',
				'car_status_signal_RearRightDoorSts_value'			=> 'binary',
				'car_status_signal_RearLeftDoorSts_value'			=> 'binary',
				'car_status_signal_FrontRightDoorSts_value'			=> 'binary',
				'car_status_signal_FrontLeftDoorSts_value'			=> 'binary',
				'car_status_signal_TrunkSts_value'					=> 'binary',
				'car_status_signal_FuelCapSts_value'				=> 'binary',
				'car_status_signal_HoodSts_value'					=> 'binary',
				'car_status_signal_FrontLeftSeatBeltSts_value'		=> 'binary',
				'car_status_signal_FrontRightSeatBeltSts_value'		=> 'numeric',
				'car_status_signal_PassAirbagSts_value'				=> 'numeric',
				'car_status_signal_FrontLeftWindowPosition_value'	=> 'numeric',
				'car_status_signal_FrontLeftWindowSts_value'		=> 'numeric',
				'car_status_signal_FrontRightWindowPosition_value'	=> 'numeric',
				'car_status_signal_FrontRightWindowSts_value'		=> 'numeric',
				'car_status_signal_RearLeftWindowPosition_value'	=> 'numeric',
				'car_status_signal_RearLeftWindowSts_value'			=> 'numeric',
				'car_status_signal_RearRightWindowPosition_value'	=> 'numeric',
				'car_status_signal_RearRightWindowSts_value'		=> 'numeric',
				'car_status_signal_WindowsLockSts_value'			=> 'numeric',
				'car_status_signal_RightIndicatorSts_value'			=> 'numeric',
				'car_status_signal_LeftIndicatorSts_value'			=> 'numeric',
				'car_status_signal_HazardSts_value'					=> 'numeric',
				'car_status_signal_LowBeamSts_value'				=> 'numeric',
				'car_status_signal_HighBeamSts_value'				=> 'numeric',
				'car_status_signal_FrontFogLightSts_value'			=> 'numeric',
				'car_status_signal_RearFogLightSts_value'			=> 'numeric',
				'car_status_signal_ManualWiperSts_value'			=> 'numeric',
				'car_status_signal_IntermittentWiperSts_value'		=> 'numeric',
				'car_status_signal_LowSpeedWiperSts_value'			=> 'numeric',
				'car_status_signal_HighSpeedWiperSts_value'			=> 'numeric',
				'car_status_signal_ManualRearWiperSts_value'		=> 'numeric',
				'car_status_signal_AutoRearWiperSts_value'			=> 'numeric',
				'car_status_signal_ClutchPedalPosition_value'		=> 'numeric',
				'car_status_signal_ClutchPedalSts_value'			=> 'numeric',
				'car_status_signal_ThrottlePedalPosition_value'		=> 'numeric',
				'car_status_signal_ThrottlePedalSts_value'			=> 'numeric',
				'car_status_signal_BrakePedalPosition_value'		=> 'numeric',
				'car_status_signal_BrakePedalSts_value'				=> 'numeric',
				'car_status_signal_HandBrakeSts_value'				=> 'numeric',
				'car_status_signal_RearRightWheelSpeed_value'		=> 'numeric',
				'car_status_signal_RearLeftWheelSpeed_value'		=> 'numeric',
				'car_status_signal_FrontRightWheelSpeed_value'		=> 'numeric',
				'car_status_signal_FrontLeftWheelSpeed_value'		=> 'numeric',
				'car_status_signal_SteeringWheelAngle_value'		=> 'numeric',
				'car_status_signal_SteeringWheelSide_value'			=> 'numeric',
				'car_status_signal_ReverseGearSts_value'			=> 'numeric',
				'car_status_signal_GearPosition_value'				=> 'numeric',
				'car_status_signal_KeySts_value'					=> 'numeric',
				'car_status_signal_IgnitionSts_value'				=> 'numeric',
				'car_status_signal_BatteryVoltage_value'			=> 'numeric',
				'car_status_signal_FuelLevel_value'					=> 'numeric',
				'car_status_signal_Odometer_value'					=> 'numeric',
				'car_status_signal_InteriorLightSts_value'			=> 'numeric',
				'car_status_signal_SunRoofSts_value'				=> 'numeric',
				'car_status_signal_DriveMode_value'					=> 'numeric',
				'car_status_signal_RadioSts_value'					=> 'numeric',
				'car_status_signal_CruiseControlSts_value'			=> 'numeric',
				'car_status_signal_VentilationSts_value'			=> 'numeric',
				'car_status_signal_ComputedEngineState_value'		=> 'numeric',
				'car_status_signal_CoolantPressure_value'			=> 'numeric',
				'car_status_signal_OutdoorTemp_value'				=> 'numeric',
				'car_status_signal_IndoorTemp_value'				=> 'numeric',
				'car_status_signal_AutoWiperSts_value'				=> 'numeric',
				'car_status_signal_RearWiperSts_value'				=> 'numeric'
			);

/*	public	$XeeErrorAuth = array(
				'401'	=> array(
					'Reason'					=> 'Utilisateur',
					'Message'					=> 'Vehicule',
					'Tip'					=> 'Nom',
				),
				'EN'	=> array('user' => 'Utilisateur',
				)
			);
*/			
			
	private $_client = '';
	private $_xeecloudData = array();
	private $_info_user = array();
	
	private $_client_id = '';
	private $_client_secret = '';

	private $_access_token_ok = false;
	private $_refresh_token_ok = false;

	private $_access_token ;
	private $_refresh_token;
	private $_redirect_url;

	public $debug = 0;
    /**
     * Construct
     *
     * @param string $client_id Client ID
     * @param string $client_secret Client Secret
     * @param string $access_token 
     * @param string $refresh_token 
     * @param string $redirect_url 
     * @return void
     */
    public function __construct($client_id, $client_secret, $access_token = '', $refresh_token = '', $redirect_url = '')
    {
        $this->_redirect_url = $redirect_url;
		$this->_client_id = $client_id;
		$this->_client_secret = $client_secret;
		$this->client = new OAuth2\Client($client_id, $client_secret, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
		$this->setAccessToken($access_token);
		$this->setRefreshToken($refresh_token);
    }

    /**
     * Set redirect_url
     *
     * @param string $redirect_url Set the redirect_url
     * @return void
     */
	public function setRedirectURL($redirect_url) {
		return $this->_redirect_url = $redirect_url;
	}

    /**
     * Get the client Id
     *
     * @return string Client ID
     */
	public function getClientId() {
		return $this->client->getClientId();
	}

    /**
     * Get the client Secret
     *
     * @return string Client Secret
     */
	public function getClientSecret() {
		return $this->client->getClientSecret();
	}

    /**
     * Set Access_Token
     *
     * @param string $access_token Set the Access_Token
     * @return void
     */
	public function setAccessToken($access_token) {
		$this->client->setAccessToken($access_token);
		$this->_access_token_ok = ($access_token != '');
		$this->_access_token = $access_token;
	}

    /**
     * Get the Access_Token
     *
     * @return string Access_Token
     */
	public function getAccessToken() {
		return $this->_access_token;
	}

    /**
     * Set Refresh_Token
     *
     * @param string $client_secret Set the Refresh_Token
     * @return void
     */
	public function setRefreshToken($refresh_token) {
		$this->_refresh_token_ok = ($refresh_token != '');
		$this->_refresh_token = $refresh_token;
	}

    /**
     * Get the Refresh_Token
     *
     * @return string Refresh_Token
     */
	public function getRefreshToken() {
		return $this->_refresh_token;
	}

    /**
     * Get the AccessToken
     *
     * @return array AccessToken
	 
	 if (!isset($_GET['code'])) {
		$code = '';
	 } else {
		$code = $_GET['code'];
	 }
	 if (!isset($_GET['state'])) {
		$state = '';
	 } else {
		$state = $_GET['state'];
	 }
     */
	public function getXeeCloudAccessTokenForm($code = '', $state = '') {
		if ($code == '') {
			if ($state == '') {
				$params = array();
			}
			else {
				$params = array('state' => $state);
			}
			$auth_url = $this->client->getAuthenticationUrl(XEE_URL_API_V1_AUTH, $this->_redirect_url, $params);
			header('Location: ' . $auth_url);
			die('Redirect');
		}
		else {
			$params = array('code' => $code);
			$response = $this->client->getAccessToken(XEE_URL_API_V1_ACCESS_TOKEN, OAuth2\Client::GRANT_TYPE_AUTH_CODE, $params);
			if ($this->getResponseCode($response) == 200) {
				$this->setAccessToken($response['result']['access_token']);
				$this->setRefreshToken($response['result']['refresh_token']);
			} else {
				$this->_access_token_ok = false;
				$this->_refresh_token_ok = false;
			}
			return $response;
		}
	}

    /**
     * Get the AccessToken
     *
     * @return array AccessToken
     */
	public function getXeeCloudAccessTokenRefresh($refresh_token = '') {
		if ($this->debug > 0) { echo "in : getXeeCloudAccessTokenRefresh"."<br>\r\n"; }
		if ($refresh_token !== '') {
			$this->setRefreshToken($refresh_token);
		}
		$refresh_token = $this->getRefreshToken();
		$params = array('refresh_token' => $refresh_token);
		$response = $this->client->getAccessToken(XEE_URL_API_V1_ACCESS_TOKEN, OAuth2\Client::GRANT_TYPE_REFRESH_TOKEN, $params);
		if ($this->getResponseCode($response) == 200) {
			$this->setAccessToken($response['result']['access_token']);
			$this->setRefreshToken($response['result']['refresh_token']);
		} else {
			$this->_refresh_token_ok = false;
			if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; } 
		}
		if ($this->debug > 0) { echo "out : getXeeCloudAccessTokenRefresh"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos User
     *
     * @return array Infos User
     */
	public function getXeeCloudInfosUser($user_id = -1) {
		if ($this->debug > 0) { echo "in : getXeeCloudInfosUser"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			if ($user_id == -1) {
				$response = $this->client->fetch(XEE_URL_API_V1_USER_CURRENT);
			} else {
				$response = $this->client->fetch(str_replace('#', $user_id, XEE_URL_API_V1_USER));
			}
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosUser();
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfosUser"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos User Cars
     *
     * @return array Infos User Cars
     */
	public function getXeeCloudInfosUserCars($user_id) {
		if ($this->debug > 0) { echo "in : getXeeCloudInfosUserCars"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			$response = $this->client->fetch(str_replace('#', $user_id, XEE_URL_API_V1_USER_CARS));
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user['cars'] = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosUserCars($user_id);
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfosUserCars"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos Car Status
     *
     * @return array Infos Car Status
     */
	public function getXeeCloudInfosCarStatus($car_id, $key = 0) {
		if ($this->debug > 0) { echo "in : getXeeCloudInfosCarStatus"."<br>\r\n"; }
		$response = false;
		if (($this->_refresh_token_ok == true) && ($this->_access_token_ok == false))  {
			$this->getXeeCloudAccessTokenRefresh();
		}
		if ($this->_access_token_ok == true) {
			$response = $this->client->fetch(str_replace('#', $car_id, XEE_URL_API_V1_CAR_STATUS));
			if ($this->getResponseCode($response) == 200) {
				$this->_info_user['cars'][$key]['status'] = $response['result'];
			} else { 
				$this->_access_token_ok = false;
				if ($this->_refresh_token_ok == true) {
					$this->getXeeCloudInfosCarStatus($car_id, $key);
				}
				if ($this->debug > 0) { var_dump($response); echo "<br>\r\n"; }
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfosCarStatus"."<br>\r\n"; }
		return $response;
	}

    /**
     * Get the Infos
     *
     * @return array Infos
     */
	public function getXeeCloudInfos() {
		if ($this->debug > 0) { echo "in : getXeeCloudInfos"."<br>\r\n"; }
		//$this->getXeeCloudAccessTokenRefresh();
		if (count($this->_info_user) == 0) {
			$response = $this->getXeeCloudInfosUser();
		}
		if ((count($this->_info_user) > 0) && ($this->getResponseCode($response) == 200)) {
			$user_id = $this->_info_user['id'];
			if ((!isset($this->_info_user['cars'])) || (count($this->_info_user['cars']) == 0)) {
				$response = $this->getXeeCloudInfosUserCars($user_id);
			}
			$info_cars = $this->_info_user['cars'];
			foreach ($info_cars as $key => $value) {
				$info_car = $value;
				$car_id = $info_car['id'];
				$response = $this->getXeeCloudInfosCarStatus($car_id, $key);
			}
		}
		if ($this->debug > 0) { echo "out : getXeeCloudInfos"."<br>\r\n"; }
		return $this->_info_user;
	}

	public function convertInfosToNomLibelleValeur($car = 0) {
		//global $XeeTraducBase, $XeeSignalName;
		
		if ($this->debug > 0) { echo "in : getXeeCloudInfosNomLibelleValeur"."<br>\r\n"; }
		$retour = array();
		foreach ($this->_info_user as $keyUser => $valueUser) {
			if (!is_array($valueUser)) {
				$Nom 		= 'user_'.$keyUser;
				$Libelle 	= 'Utilisateur ';
				if (isset($this->XeeTraducBase['FR'][$keyUser])) {
					$Libelle .= $this->XeeTraducBase['FR'][$keyUser];
				} else {
					$Libelle .= $keyUser;
				}
				$Valeur 	= $valueUser;
				$retour[$Nom] = array(); 
				$retour[$Nom]['Nom'] 	= $Nom;
				$retour[$Nom]['Libelle']= $Libelle;
				$retour[$Nom]['Valeur']	= $Valeur;
				$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
			} else {
				if (($keyUser == 'cars') &&(count($valueUser) > 0)) {
					foreach ($valueUser[$car] as $keyCar => $valueCar) {
						if (!is_array($valueCar)) {
							$Nom 		= 'car_'.$keyCar;
							$Libelle 	= 'Vehicule ';
							if (isset($this->XeeTraducBase['FR'][$keyCar])) {
								$Libelle .= $this->XeeTraducBase['FR'][$keyCar];
							} else {
								$Libelle .= $keyCar;
							}
							$Valeur 	= $valueCar;
							$retour[$Nom] = array(); 
							$retour[$Nom]['Nom'] 	= $Nom;
							$retour[$Nom]['Libelle']= $Libelle;
							$retour[$Nom]['Valeur']	= $Valeur;
							$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
						} else {
							foreach ($valueCar as $keyStatus => $valueStatus) {
								if ($keyStatus == 'signals') {
									foreach ($valueStatus as $keyStatusDetail => $valueStatusDetail) {
										$Nom 		= 'car_'.$keyCar.'_'.'signal'.'_'.$valueStatusDetail['name'];
										$Libelle 	= '';
										//$Libelle 	.= 'Vehicule ';
										/*if (isset($this->XeeTraducBase['FR'][$keyCar])) {
											$Libelle .= $this->XeeTraducBase['FR'][$keyCar].' ';
										}
										*/
										//$Libelle .= 'Signal'.' ';
										if (isset($this->XeeSignalName['FR'][$valueStatusDetail['name']])) {
											$Libelle .= $this->XeeSignalName['FR'][$valueStatusDetail['name']];
										} else {
											$Libelle .= $valueStatusDetail['name'];
										}
										$Type = 'numeric';
										if (substr($Nom, -3) == 'Sts') {
											$Type = 'binary';
										}
										$NomValue = $Nom.'_value';
										$retour[$NomValue] = array(); 
										$retour[$NomValue]['Nom'] 		= $NomValue;
										$retour[$NomValue]['Libelle']	= $Libelle;//.' Valeur';
										$retour[$NomValue]['Valeur']	= $valueStatusDetail['value'];
										$retour[$NomValue]['Type']		= $Type;
										//$retour[$NomValue]['Type']	= $this->XeeVarType[$NomValue];
										$NomValue = $Nom.'_date';
										$retour[$NomValue] = array(); 
										$retour[$NomValue]['Nom'] 		= $NomValue;
										$retour[$NomValue]['Libelle']	= $Libelle.' Date';
										$retour[$NomValue]['Valeur']	= $valueStatusDetail['reportDate'];
										$retour[$NomValue]['Type']		= 'other';
									}
								} else {
									if ( is_array($valueStatus) && !empty($valueStatus) ) {
										foreach ($valueStatus as $keyStatusDetail => $valueStatusDetail) {
											$Nom 		= 'car_'.$keyCar.'_'.$keyStatus.'_'.$keyStatusDetail;
											$Libelle 	= '';
											//$Libelle 	.= 'Vehicule ';
											/*if (isset($this->XeeTraducBase['FR'][$keyCar])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyCar].' ';
											}*/
											if (isset($this->XeeTraducBase['FR'][$keyStatus])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyStatus].' ';
											} else {
												$Libelle .= $keyStatus.' ';
											}
											if (isset($this->XeeTraducBase['FR'][$keyStatusDetail])) {
												$Libelle .= $this->XeeTraducBase['FR'][$keyStatusDetail];
											} else {
												$Libelle .= $keyStatusDetail;
											}
											$Valeur 	= $valueStatusDetail;
											$retour[$Nom] = array(); 
											$retour[$Nom]['Nom'] 	= $Nom;
											$retour[$Nom]['Libelle']= $Libelle;
											$retour[$Nom]['Valeur']	= $Valeur;
											$retour[$Nom]['Type']	= $this->XeeVarType[$Nom];
										}
									}
								}									
							}
						}
					}
					
				}
			}
		}
		
		if ($this->debug > 0) { echo "out : getXeeCloudInfosNomLibelleValeur"."<br>\r\n"; }
		return $retour;
	}

    /**
     * Get the Car Compatibility
     *
     * @return array Car Compatibility 
     */
	public function getXeeCarCompatibility($cardbId = 1) {
		if ($this->debug > 0) { echo "in : getXeeCarCompatibility"."<br>\r\n"; }
		$response = false;

		$credentials = $this->_client_id.':'.$this->_client_secret;
		echo $credentials."<br>\r\n";
		/*$context = stream_context_create(array(
			'http' => array(
				'headers' => "Authorization: Basic " . base64_encode($credentials),
			),
		));*/
		
		$url = str_replace('#', $cardbId, XEE_URL_API_COMPAT_V1_CARDB);
		echo $url."<br>\r\n";		
		//$result = file_get_contents($url, false, $context);	
		//$response = $result;
		
		$headers = array(
	        "Authorization: Basic " . base64_encode($credentials)
        );
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$result = curl_exec($ch); 

        if (curl_errno($ch)) {
        	print "Error: " . curl_error($ch);
        } else {
        	// Show me the result
        	//var_dump($data);
        	curl_close($ch);
        }
		$result = json_decode($result, true);
		$response = $result;
		if ($this->debug > 0) { echo "out : getXeeCarCompatibility"."<br>\r\n"; }
		return $response;
	}

	public function getResponseCode($response) {
		$code = 0;
		if (isset($response['code']))  {
			$code = $response['code'];
		}
		if ($this->debug > 0) { echo "code : ".$code."<br>\r\n"; }
		return $code;
	}
}

?>