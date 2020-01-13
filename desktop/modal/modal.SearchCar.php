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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="row" style="text-align: center; margin-top: 10px; margin-bottom: 10px;" id='searchCarStatus'>
	Recherche en cours...
</div>
<div class="row" id='searchCarResults'></div>

<script>
	var XeeCloudFormId = $('.eqLogicAttr[data-l1key=id]').val();
	getCars(XeeCloudFormId);

	function getCars(_id) {
		var pendingRequest = Array();
		request = $.ajax({// fonction permettant de faire de l'ajax
		  type: "POST", // méthode de transmission des données au fichier php
		  url: "plugins/XeeCloud/core/ajax/XeeCloud.ajax.php", // url du fichier php
		  data: {
			action: "getCars",
			id: _id,
		  },
		  dataType: 'json',
		  global: false,
		  error: function (request, status, error) {
			handleAjaxError(request, status, error);
		  },
		  success: function (data) { // si l'appel a bien fonctionné
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				div = '<div id="SearchResult" class="list-group">';
				for (var i in data.result) {
					var value = data.result[i]['value'];	// A conserver temporairement pour compatibilite. Puis a supprimer
					var car_id = data.result[i]['car_id'];
					var car_name = data.result[i]['car_name'];
					var car_brand = data.result[i]['car_brand'];
					var car_model = data.result[i]['car_model'];
					var car_licensePlate = data.result[i]['car_licensePlate'];
					div += '  <a href="#" data-mfnumber="' + car_id + '" class="list-group-item" title="' + car_brand + ' ' + car_model + ' ' + car_licensePlate + '"' + '>' + car_name + '</a>';
				}
				div += '</div>';
				$('#searchCarResults').html(div);
				$('#searchCarStatus').html("Recherche en cours... OK");

				$( "#SearchResult" ).on( "click", "a", function() {
					$('#mfCarNom').val($( this ).text());
					$('#mfCarId').val($( this ).data('mfnumber'));

					$('#md_modal').dialog('close');
				});

			}
		});
		pendingRequest.push(request);
	} 
	
</script>