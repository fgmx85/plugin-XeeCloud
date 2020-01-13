<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/******************************* Includes *******************************/ 
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../3rdparty/APIXeeCloud.class.php';

class XeeCloud extends eqLogic {
    /******************************* Attributs *******************************/ 
    /* Ajouter ici toutes vos variables propre à votre classe */

    private $_XeeCloudDataAll = array();
    private $_XeeCloudData = array();
	
    /***************************** Methode static ****************************/ 

    public static function pull($_options) {
		log::add('XeeCloud', 'debug', 'Pull Cron Debut');
		foreach (eqLogic::byType('XeeCloud') as $XeeCloud) {
		  if ($XeeCloud->getIsEnable() == 1) {
			if (null !== ($XeeCloud->getConfiguration('refresh_token', ''))) {
				log::add('XeeCloud', 'debug', 'Pull Cron Actualisation Id : '.$XeeCloud->getId().' Name : '.$XeeCloud->getName());
				$XeeCloud->getInformations($XeeCloud->getId());
			} else {
			  log::add('XeeCloud', 'error', 'refresh_token non saisi');
			}
		  }
		}
		log::add('XeeCloud', 'debug', 'Pull Cron Fin');
    }

    /*
    // Fonction exécutée automatiquement toutes les minutes par Jeedom
    public static function cron() {

    }
    */

    /*
    // Fonction exécutée automatiquement toutes les heures par Jeedom
    public static function cronHourly() {

    }
    */

    /*
    // Fonction exécutée automatiquement tous les jours par Jeedom
    public static function cronDayly() {

    }
    */
 
    /*************************** Methode d'instance **************************/ 
 

    /************************** Pile de mise à jour **************************/ 
    
    /* fonction permettant d'initialiser la pile 
     * plugin: le nom de votre plugin
     * action: l'action qui sera utilisé dans le fichier ajax du pulgin 
     * callback: fonction appelé coté client(JS) pour mettre à jour l'affichage 
     */ 
    public function initStackData() {
        nodejs::pushUpdate('XeeCloud::initStackDataEqLogic', array('plugin' => 'XeeCloud', 'action' => 'saveStack', 'callback' => 'displayEqLogic'));
    }
    
    /* fonnction permettant d'envoyer un nouvel équipement pour sauvegarde et affichage, 
     * les données sont envoyé au client(JS) pour être traité de manière asynchrone
     * Entrée: 
     *      - $params: variable contenant les paramètres eqLogic
     */
    public function stackData($params) {
        if(is_object($params)) {
            $paramsArray = utils::o2a($params);
        }
        nodejs::pushUpdate('XeeCloud::stackDataEqLogic', $paramsArray);
    }
    
    /* fonction appelé pour la sauvegarde asynchrone
     * Entrée: 
     *      - $params: variable contenant les paramètres eqLogic
     */
    public function saveStack($params) {
        // inserer ici le traitement pour sauvegarde de vos données en asynchrone
        
    }

    /* fonction appelé avant le début de la séquence de sauvegarde */
    public function preSave() {
        
    }

    /* fonction appelé pendant la séquence de sauvegarde avant l'insertion 
     * dans la base de données pour une mise à jour d'une entrée */
    public function preUpdate() {
        
    }

    /* fonction appelé pendant la séquence de sauvegarde après l'insertion 
     * dans la base de données pour une mise à jour d'une entrée */
    public function postUpdate() {
		//echo $this->getId()."-";
		$order = 0;
		$order = count($this->getCmd());
		//echo $this->getId()."-";
		//echo count($this->_XeeCloudData)."-";
		
		// adaptation pour migration API v1->v3
		$XeeCloudCmd = $this->getCmd(null, 'user_name');
		if (is_object($XeeCloudCmd)) {
			$XeeCloudCmd->setName(__('Utilisateur Nom APIv1', __FILE__));
			$XeeCloudCmd->save();
			//$XeeCloudCmd->remove();
		}
		$XeeCloudCmd = $this->getCmd(null, 'car_plateNumber');
		if (is_object($XeeCloudCmd)) {
			$XeeCloudCmd->setName(__('Vehicule Immatriculation APIv1', __FILE__));
			$XeeCloudCmd->save();
			//$XeeCloudCmd->remove();
		}

		
		// Ajout des nouvelles valeurs
		$XeeCloudCmd = $this->getCmd(null, 'connected');
		if (!is_object($XeeCloudCmd)) {
			//echo "conn-";
			$XeeCloudCmd = new XeeCloudCmd();
			$XeeCloudCmd->setName(__('Connecté au Cloud', __FILE__));
			$XeeCloudCmd->setEqLogic_id($this->id);
			$XeeCloudCmd->setLogicalId('connected');
			$XeeCloudCmd->setConfiguration('data', 'connected');
			$XeeCloudCmd->setType('info');
			$XeeCloudCmd->setSubType('binary');
			$XeeCloudCmd->setUnite('');
			$XeeCloudCmd->setEventOnly(1);
			$XeeCloudCmd->setIsHistorized(0);
			$XeeCloudCmd->setOrder($order);
			$order = $order + 1;
			$XeeCloudCmd->save();
		}

		$XeeCloudCmd = $this->getCmd(null, 'geolocalisation');
		if (!is_object($XeeCloudCmd)) {
			$XeeCloudCmd = new XeeCloudCmd();
			$XeeCloudCmd->setName(__('Geolocalisation', __FILE__));
			$XeeCloudCmd->setEqLogic_id($this->id);
			$XeeCloudCmd->setLogicalId('geolocalisation');
			$XeeCloudCmd->setConfiguration('data', 'geolocalisation');
			$XeeCloudCmd->setType('info');
			$XeeCloudCmd->setSubType('string');
			$XeeCloudCmd->setEventOnly(1);
//			  	$XeeCloudCmd->setIsHistorized(0);
			$XeeCloudCmd->setOrder($order);
			$order = $order + 1;
			$XeeCloudCmd->save();
		}

		$this->getInformationsXeeCloud();
		foreach ($this->_XeeCloudData as $key => $value) {
			//echo "<td>".$key."</td><td>".$value['Libelle']."</td><td>".$value['Valeur']."</td>\r\n";
			$cmdname = $key;
			$cmdlib = $value['Libelle'];
			$cmdtype = 'string';
			if ($value['TypeJeedom'] != '') {
				$cmdtype = $value['TypeJeedom'];
			}
			$XeeCloudCmd = $this->getCmd(null, $cmdname);
			if (!is_object($XeeCloudCmd)) {
				$XeeCloudCmd = new XeeCloudCmd();
				$XeeCloudCmd->setName(__($cmdlib, __FILE__));
				$XeeCloudCmd->setEqLogic_id($this->id);
				$XeeCloudCmd->setLogicalId($cmdname);
				$XeeCloudCmd->setConfiguration('data', $cmdname);
				$XeeCloudCmd->setType('info');
				$XeeCloudCmd->setSubType($cmdtype);
				$XeeCloudCmd->setUnite('');
				$XeeCloudCmd->setEventOnly(1);
				$XeeCloudCmd->setIsHistorized(0);
				$XeeCloudCmd->setIsVisible(0);
				$XeeCloudCmd->setOrder($order);
				$order = $order + 1;
				$XeeCloudCmd->save();
			} else {
				if ($XeeCloudCmd->getSubType() != $cmdtype) {
					$XeeCloudCmd->setSubType($cmdtype);
					$XeeCloudCmd->save();
				}
			}
		}
		$this->getInformations();
    }

    /* fonction appelé pendant la séquence de sauvegarde avant l'insertion 
     * dans la base de données pour une nouvelle entrée */
    public function preInsert() {

    }

    /* fonction appelé pendant la séquence de sauvegarde après l'insertion 
     * dans la base de données pour une nouvelle entrée */
    public function postInsert() {
        
    }

    /* fonction appelé après la fin de la séquence de sauvegarde */
    public function postSave() {
        
    }

    /* fonction appelé avant l'effacement d'une entrée */
    public function preRemove() {
        
    }

    /* fonnction appelé après l'effacement d'une entrée */
    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

	public function updateCmdsInfo() {
/*		$cmdsInfo = $this->getCmd('info', null, true);
		log::add('wundergroundAPI', 'info', "update " . $this->getHumanName());
		if (count($cmdsInfo) > 0) {
			$parsed_json = $this->callApi();
			foreach ($cmdsInfo as $cmdInfo) {
				$logicalId = $cmdInfo->getLogicalId();
				$tab = explode("/", $logicalId);
				$infoCmd = $parsed_json;
				// log::add('wundergroundAPI', 'info',"logicalId : $logicalId");
				for ($i = 1; $i < count($tab); $i++) {
					$attr = $tab[$i];
					// log::add('wundergroundAPI', 'info',"attr : $attr");
					if (is_array($infoCmd)) {
						$infoCmd = $infoCmd[$attr];
					} else {
						$infoCmd = $infoCmd->{$attr};//$parsed_json->{'current_observation'}
					}
				}
				// log::add('wundergroundAPI', 'info',"value : $infoCmd");
				//$cmdInfo->setValue($infoCmd);
				$cmdInfo->setConfiguration('wundergroundAPI_value', $infoCmd);
				$cmdInfo->save();
				$cmdInfo->event($infoCmd);
			}
		}*/
	}
	public function createCmdsInfo() {
/*		$feature = $this->getConfiguration('feature');
		if (isset($feature) && $feature != "") {
			$parsed_json = $this->callApi();
			// log::add('wundergroundAPI', 'debug', $parsed_json);
			$order=count($this->getCmd());
			$this->createCmdInfo($parsed_json, $feature, $order);
		}*/
	}
	
	public function createCmdInfo($infoCmd, $logicalId, $order=0) {
	
	}	 
	
    public function getInformationsXeeCloud($id = -1) {
    	log::add('XeeCloud', 'info', 'Récupération des données (getInformationsXeeCloud) Id : '.$id, 'config');
		// recup infos
		// Recuperation de client_id et client_secret dans la config
		// API v3
		$CLIENT_ID     = config::byKey('XeeCloudClientID','XeeCloud');
		$CLIENT_SECRET = config::byKey('XeeCloudClientIDSecret','XeeCloud');

		// API v4
		$CLIENTv4_ID     = config::byKey('XeeCloudAPIv4ClientID','XeeCloud');
		$CLIENTv4_SECRET = config::byKey('XeeCloudAPIv4ClientIDSecret','XeeCloud');
		$CLIENTv4_KEY 	 = config::byKey('XeeCloudAPIv4ClientIDKey','XeeCloud');

		$connected = false;
		
//		echo $this->getConfiguration('access_token')."-";
		//echo $this->getId()."-";
		if ($id == -1) {
			$eqLogic = $this;
		} else {
			$eqLogic = $this; //eqLogic::byId($id);
		}
		log::add('XeeCloud', 'debug', 'eqLogic ID : ' . $eqLogic->getId());
		if ($eqLogic->getEqType_name() == 'XeeCloud') {
			$access_token = $eqLogic->getConfiguration('access_token');
			$refresh_token = $eqLogic->getConfiguration('refresh_token');
			$token_expires_in = $eqLogic->getConfiguration('token_expires_in');
			$token_last_refresh_UTC = $eqLogic->getConfiguration('token_last_refresh_UTC');
			if ($eqLogic->getConfiguration('apiversion') == 4) {
				$XeeCloud = new XeeCloudAPIv4($CLIENTv4_KEY, $CLIENTv4_SECRET, $access_token, $refresh_token, '', $token_expires_in, $token_last_refresh_UTC);
			} else {	
				$XeeCloud = new XeeCloudAPI($CLIENT_ID, $CLIENT_SECRET, $access_token, $refresh_token);
			}
			$response = $XeeCloud->getXeeCloudInfos();
			//echo count($response);
			if (($XeeCloud->getRefreshToken() != '') && ($XeeCloud->getRefreshToken() != $refresh_token)) {
				$eqLogic->setConfiguration('access_token', $XeeCloud->getAccessToken());
				$eqLogic->setConfiguration('refresh_token', $XeeCloud->getRefreshToken());
				$eqLogic->setConfiguration('token_expires_in', $XeeCloud->getToken_Expires_in());
				$eqLogic->setConfiguration('token_last_refresh_UTC', $XeeCloud->getToken_Last_Refresh_UTC());
				$eqLogic->save();
			}
			if ((is_array($response)) && (count($response) > 0)) {
				$connected = true;
				$eqLogic->_XeeCloudDataAll = $response;
				$car_id = $eqLogic->getConfiguration('car');
				log::add('XeeCloud', 'debug', 'car_id : ' . $car_id);
				$response = $XeeCloud->convertInfosToNomLibelleValeur($car_id);
				log::add('XeeCloud', 'debug', 'count : ' . count($response));
				$eqLogic->_XeeCloudData = $response;
			}
		}
		return $connected;
	}

    public function getInformations($id = -1) {
    	log::add('XeeCloud', 'info', 'Récupération des données (getInformations) Id : '.$id, 'config');
		$geolocalisation = '';
		$connected = $this->getInformationsXeeCloud();
		if ($connected) {
			log::add('XeeCloud', 'debug', 'car_status_location_latitude ' . $this->_XeeCloudData['car_status_location_latitude']['Valeur']);
			log::add('XeeCloud', 'debug', 'car_status_location_longitude ' . $this->_XeeCloudData['car_status_location_longitude']['Valeur']);
			if (($this->_XeeCloudData['car_status_location_latitude']['Valeur'] != '') && ($this->_XeeCloudData['car_status_location_longitude']['Valeur'] != '')) {
				$geolocalisation = $this->_XeeCloudData['car_status_location_latitude']['Valeur'].",".$this->_XeeCloudData['car_status_location_longitude']['Valeur'];
			}
		}
		foreach ($this->getCmd() as $cmd) {
			if($cmd->getConfiguration('data')=="geolocalisation"){
				if ($geolocalisation != '') {
					$cmd->setConfiguration('value', $geolocalisation);
					$cmd->save();
					$cmd->event($geolocalisation);
					log::add('XeeCloud', 'debug', 'Geolocalisation ' . $geolocalisation);
				  }
			}
			if($cmd->getConfiguration('data')=="connected"){
				$cmd->setConfiguration('value', $connected);
				$cmd->save();
				$cmd->event($connected);
				log::add('XeeCloud', 'debug', 'Connecté au Cloud ' . $connected);
			}
			foreach ($this->_XeeCloudData as $key => $value) {
				if($cmd->getConfiguration('data')==$key){
					$Valeur = $value['Valeur'];
					// Ajustement de la date UTC fournie par le boitier Xee
					if (substr($key, -5) == '_date') {
						$timezone =  config::byKey('timezone');
						$this_tz = new DateTimeZone($timezone);
						$date = date_create_from_format('Y-m-d H:i:s', $Valeur, new DateTimeZone("UTC"));
						$date->setTimezone($this_tz);
						$Valeur = $date->format('Y-m-d H:i:s');
					}
					$cmd->setConfiguration('value', $Valeur);
					$cmd->save();
					$cmd->event($Valeur);
					log::add('XeeCloud', 'debug', $value['Libelle'] .' -> '. $Valeur);
				}
			}
		}
		if (($geolocalisation != '') && ($geolocalisation != ',')) {
			log::add('XeeCloud', 'debug', "Geolocalisation : " . $geolocalisation);
			
			$geoloc = $this->getConfiguration('geoloc', 'none');
			log::add('XeeCloud', 'debug', "Id geoloc : " . $geoloc);
			if ($geoloc != 'none') {
				$eqlogic = geolocCmd::byId($geoloc);
				if (is_object($eqlogic)) {
					if (($eqlogic->getEqLogic()->getEqType_name() == 'geoloc') && ($eqlogic->getConfiguration('mode') == 'dynamic')) {
						if ($eqlogic->getConfiguration('mode') == 'fixe') {
							$geolocval = $eqlogic->getConfiguration('coordinate');
						} else {
							$geolocval = $eqlogic->execCmd();
						}
						log::add('XeeCloud', 'debug', "Geolocalisation geoloc: " . $geolocval);
						if ($geolocval != $geolocalisation) {
							log::add('XeeCloud', 'debug', "Update Geolocalisation geoloc : " . $geolocval . ' -> ' . $geolocalisation);
							$Url = network::getNetworkAccess('internal') . '/plugins/geoloc/core/api/jeeGeoloc.php?apikey=' . jeedom::getApiKey('geoloc') . '&id='. $geoloc . '&value='.$geolocalisation;
							log::add('XeeCloud', 'debug', "URL Geoloc : " . $Url);
							file_get_contents($Url);
						}
					}
				} else {
					log::add('XeeCloud', 'error', "Commande ID geoloc inconnu : " . $geoloc);
				}
			}
			
			$geolocgeotrav = $this->getConfiguration('geolocgeotrav', 'none');
			log::add('XeeCloud', 'debug', "Id geolocgeotrav : " . $geolocgeotrav);
			if (($geolocgeotrav != 'none') && ($geolocgeotrav != '')) {
				$eqlogic = geotrav::byId($geolocgeotrav);
				if (is_object($eqlogic)) {
					if (($eqlogic->getEqType_name() == 'geotrav') && ($eqlogic->getConfiguration('type') == 'location')) {
						$geotravval = geotravCmd::byEqLogicIdAndLogicalId($geolocgeotrav,'location:coordinate')->execCmd();
						log::add('XeeCloud', 'debug', "Geolocalisation geotrav : " . $geotravval);
						if ($geolocgeotravval != $geolocalisation) {
							//	$Url = network::getNetworkAccess('internal') . '/plugins/geotrav/core/api/jeeGeotrav.php?apikey=' . jeedom::getApiKey('geotrav') . '&id='. $geolocgeotrav . '&value='.$geolocalisation;
							//	log::add('XeeCloud', 'debug', "URL Geoloc geotrav : " . $Url);
							//	file_get_contents($Url);
							log::add('XeeCloud', 'debug', "Update Geolocalisation geotrav : " . $geotravval . ' -> ' . $geolocalisation);
							$eqlogic->updateGeocodingReverse(trim($geolocalisation));
						}
					}
				} else {
					log::add('XeeCloud', 'error', "Commande ID geotrav inconnu : " . $geolocgeotrav);
				}
			}
		}
		return ;
	}

	public function getCars($_infos = '') {
		$return = array();
		// API v3
		$CLIENT_ID     = config::byKey('XeeCloudClientID','XeeCloud');
		$CLIENT_SECRET = config::byKey('XeeCloudClientIDSecret','XeeCloud');
		
		// API v4
		$CLIENTv4_ID     = config::byKey('XeeCloudAPIv4ClientID','XeeCloud');
		$CLIENTv4_SECRET = config::byKey('XeeCloudAPIv4ClientIDSecret','XeeCloud');
		$CLIENTv4_KEY 	 = config::byKey('XeeCloudAPIv4ClientIDKey','XeeCloud');

		$connected = false;
		
//		echo $this->getConfiguration('access_token')."-";
		//echo $this->getId()."-";
		if ($_infos != '') {
			$eqLogic = eqLogic::byId($_infos);
			if ($eqLogic->getEqType_name() == 'XeeCloud') {
				$access_token = $eqLogic->getConfiguration('access_token');
				$refresh_token = $eqLogic->getConfiguration('refresh_token');
				$token_expires_in = $eqLogic->getConfiguration('token_expires_in');
				$token_last_refresh_UTC = $eqLogic->getConfiguration('token_last_refresh_UTC');
				$memo_cars = $eqLogic->getConfiguration('cars');
				$Cars = explode(';', $memo_cars);
				if ($eqLogic->getConfiguration('apiversion') == 4) {
					$XeeCloud = new XeeCloudAPIv4($CLIENTv4_KEY, $CLIENTv4_SECRET, $access_token, $refresh_token);
				} else {	
					$XeeCloud = new XeeCloudAPI($CLIENT_ID, $CLIENT_SECRET, $access_token, $refresh_token);
				}
				$response = $XeeCloud->getXeeCloudInfos();
				//echo count($response);
				if (($XeeCloud->getRefreshToken() != '') && ($XeeCloud->getRefreshToken() != $refresh_token)) {
					$eqLogic->setConfiguration('access_token', $XeeCloud->getAccessToken());
					$eqLogic->setConfiguration('refresh_token', $XeeCloud->getRefreshToken());
					$eqLogic->setConfiguration('token_expires_in', $XeeCloud->getToken_Expires_in());
					$eqLogic->setConfiguration('token_last_refresh_UTC', $XeeCloud->getToken_Last_Refresh_UTC());
					$eqLogic->save();
				}
				if ((is_array($response)) && (count($response) > 0)) {
					$Cars = $response['cars'];
					$eqLogic->setConfiguration('cars', implode(";", $Cars));
					$eqLogic->save();
				}
				foreach ($Cars as $key => $value) {
					$return[$key] = array(
					'value' 			=> $value['name'],	// A conserver temporairement pour compatibilite. Puis a supprimer
					'car_id' 			=> $value['id'],
					'car_name' 			=> $value['name'],
					'car_brand' 		=> $value['brand'],
					'car_model' 		=> $value['model'],
					'car_licensePlate' 	=> $value['licensePlate'],
					);
				}
			}
		}
		return $return;
	}

	public function getGeoloc($_infos = '') {
	$return = array();
	foreach (eqLogic::byType('geoloc') as $geoloc) {
		foreach (geolocCmd::byEqLogicId($geoloc->getId()) as $geoinfo) {
//			if ($geoinfo->getConfiguration('mode') == 'fixe' || $geoinfo->getConfiguration('mode') == 'dynamic') {
			if ($geoinfo->getConfiguration('mode') == 'dynamic') {
				$return[$geoinfo->getId()] = array(
				'value' => $geoinfo->getName(),
				);
			}
		}
	}
	return $return;
	}
	
    /*     * **********************Getteur Setteur*************************** */
}

class XeeCloudCmd extends cmd {
    /******************************* Attributs *******************************/ 
    /* Ajouter ici toutes vos variables propre à votre classe */

    /***************************** Methode static ****************************/ 

    /*************************** Methode d'instance **************************/ 

    /* Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
    public function dontRemoveCmd() {
        return true;
    }
    */

    public function execute($_options = array()) {
        
    }

    /***************************** Getteur/Setteur ***************************/ 

    
}

?>
