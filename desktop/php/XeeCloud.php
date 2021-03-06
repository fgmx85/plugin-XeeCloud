<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'XeeCloud');
$eqLogics = eqLogic::byType('XeeCloud');
?>
<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes Xee}}
        </legend>
            <div class="eqLogicThumbnailContainer">
                      <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
           <center>
            <i class="fa fa-plus-circle" style="font-size : 7em;color:#00979c;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>Ajouter</center></span>
    </div>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                    echo "<center>";
                    echo '<img src="plugins/XeeCloud/docs/images/XeeCloud_icon.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
                ?>
            </div>
    </div>


    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <div class="row">
            <div class="col-sm-6">
                <form class="form-horizontal">
            <fieldset>
                <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i>  {{Général}}
                <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
                </legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Nom de l'équipement Xee}}</label>
                    <div class="col-sm-8">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Xee}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label" >{{Objet parent}}</label>
                    <div class="col-md-4">
                        <select class="form-control eqLogicAttr" data-l1key="object_id">
                            <option value="">{{Aucun}}</option>
                            <?php
                            foreach (jeeObject::all() as $object) {
                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">{{Catégorie}}</label>
                    <div class="col-md-8">
                        <?php
                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                            echo '<label class="checkbox-inline">';
                            echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                            echo '</label>';
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group">
                <label class="col-md-3 control-label" ></label>
                <div class="col-sm-8">
					<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
					<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Commentaire}}</label>
                    <div class="col-md-8">
                        <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
                    </div>
                </div>

            </fieldset>

        </form>
        </div>

                <div id="infoNode" class="col-sm-6">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>{{Configuration}}</legend>

                        <div class="form-group">
                          <label class="col-md-3 control-label">{{Géolocalisation}}</label>
                          <div class="col-md-4">
                            <select class="form-control eqLogicAttr configuration" id="geoloc" data-l1key="configuration" data-l2key="geoloc">
                              <option value="none">{{Aucun}}</option>
                              <?php
								  foreach (eqLogic::byType('geoloc') as $geoloc) {
									foreach (geolocCmd::byEqLogicId($geoloc->getId()) as $geoinfo) {
										if ($geoinfo->getConfiguration('mode') == 'dynamic') {
											echo '<option value="' . $geoinfo->getId() . '">' . $geoinfo->getName() . '</option>';
										}
									}
								  }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-md-3 control-label">{{Géolocalisation (geotrav)}}</label>
                          <div class="col-md-4">
                            <select class="form-control eqLogicAttr configuration" id="geolocgeotrav" data-l1key="configuration" data-l2key="geolocgeotrav">
                              <option value="none">{{Aucun}}</option>
                              <?php
								if (class_exists('geotravCmd')) {
									foreach (eqLogic::byType('geotrav') as $geoloc) {
										if ($geoloc->getConfiguration('type') == 'location') {
											echo '<option value="' . $geoloc->getId() . '">' . $geoloc->getName() . '</option>';
										}
									}
								} else {
									echo '<option value="">Pas de localisation disponible</option>';
								}
                              ?>
                            </select>
                          </div>
                        </div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >{{API}}</label>
							<div class="col-md-4">
								<select class="form-control eqLogicAttr configuration" id="apiversion" data-l1key="configuration" data-l2key="apiversion">
									<option value="4">v4</option>
									<!-- <option value="3" disabled>v3</option> -->
								</select>
							</div>
                        </div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >{{Authentification}}</label>
							<div class="col-md-4">
								<a class="btn btn-default" id='btn_auth'><i class="fa fa-search"></i> {{Authentification Xee}}</a>
							</div>
                        </div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >{{Véhicule}}</label>
							<div class="col-lg-3">
								<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="carNom" type="text" placeholder="{{Vehicule}}" id="mfCarNom" disabled>
							</div>
							<div class="col-lg-2">
							   <a class="btn btn-default" id='btnSearchCar'><i class="fa fa-search"></i> {{Choix vehicule}}</a>
							</div>
							<div class="col-lg-5">
								<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="car" type="text" placeholder="{{ID Vehicule}}" id="mfCarId" disabled style="display: none;" >
								 <!-- style="display: none;" -->
							</div>
						</div>
                    </fieldset>
                </form>
            </div>
        </div>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

		<legend>{{Informations}}</legend>

        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 300px;">{{Nom}}</th>
                    <th style="width: 250px;">{{Valeur}}</th>
                    <th style="width: 200px;">{{Paramètres}}</th>
                    <th style="width: 100px;">{{Action}}</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

    </div>
</div>

<?php include_file('desktop', 'XeeCloud', 'js', 'XeeCloud'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
