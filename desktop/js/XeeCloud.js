
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

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

/* Fonction appelé pour mettre l'affichage du tableau des commandes de votre eqLogic
 * _cmd: les détails de votre commande
 */
/* global jeedom */

function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    if (init(_cmd.type) == 'info') {
        //var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
			tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
			tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="configuration" data-l2key="value"></span>';
        tr += '</td>';
        tr += '<td>';
		tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
		tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" />{{Historiser}}</label></span>';
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        }
        tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
        tr += '</td>';
        tr += '</tr>';
        $('#table_cmd tbody').append(tr);
        $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }
}

$('#btn_auth').on('click', function () {
	var XeeCloudFormId = $('.eqLogicAttr[data-l1key=id]').val();
	$('.eqLogicAction[data-action=save]').click();
	var url = 'plugins/XeeCloud/3rdparty/oauth.php?state=' + XeeCloudFormId;
	window.open(url,'Authentification Xee','menubar=no, location=no top=50, left=50, width=600, height=770');
	//$('#md_modal').dialog({title: "{{Authentification Xee}}"});
	//$('#md_modal').load('index.php?v=d&plugin=XeeCloud&modal=modal.XeeCloud&url=' + encodeURI(url)).dialog('open');
});

$('#btnSearchCar').on('click', function () {
	$('#md_modal').dialog({title: "{{Selectionner le vehicule}}", height: 600, width: 600});
    $('#md_modal').load('index.php?v=d&plugin=XeeCloud&modal=modal.SearchCar').dialog('open');
});
