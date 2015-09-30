<!-- Fichier inséré dans le formulaire de modification d'une intervention. 
Positionne un marqueur à l'emplacement ou a été localisé l'intervention. 
Ce marqueur est déplacable et ses coordonnées sont affichées dans un champs à chaque déplacement. 
Les valeurs x et y en WGS84 seront ensuite mises à jour dans la BD PostgreSQL -->

	<div id="map_1" style="margin: 0 auto; border: 1px solid rgb(69, 69, 85); padding: 10px; width: 770px; height: 400px; background-color: rgb(229, 227, 223); color: rgb(32, 183, 255); font-size: 16px; font-weight: bold; vertical-align: middle; position: relative;">
	</div>
	<div style="margin: 0 auto; width: 800px">
		<p class="commentaire">
			1 - Placez approximativement le marqueur &agrave; l'aide de la souris. <br>
			2 - D&eacute;placez vous et zoomez (&agrave; l'aide des outils en haut &agrave; gauche ou grace &agrave; la molette de la souris) pour vous localiser plus precis&eacute;ment. <br>
			3 - Ajustez la position du marqueur.
		</p>
		<hr color="#dcdcdc" > 
		<p>
			Coordonn&eacute;es du marqueur : <br>
			<span class="commentaire">
				X : <input id="longEnd" value="<? echo $x; ?>" maxlength="30" type="text" name="fx" readonly="true" class="commentaire" style="border:0px"> - d&eacute;termin&eacute; selon la position du marqueur<br>
				Y : <input id="latEnd" value="<? echo $y; ?>" maxlength="30" type="text" name="fy" readonly="true" class="commentaire" style="border:0px"> - d&eacute;termin&eacute; selon la position du marqueur</span/>
			</span>
		</p>
		<p>
			<span class="colbord">Commune : </span>
			<span class="colcom">
				<input id="fcomm" name="fcomm" type="hidden" value="<? echo $comm_id; ?>">
				<select id="fcomm1" name="fcomm1" disabled ="disabled">
					<option value="">...</option>
						<?
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
							$sql_infr = "SELECT id_commune , commune
							FROM layers.l_communes
							ORDER BY commune";
							$result = pg_query($sql_infr) or die ("Erreur requête") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant à la valeur selectionnée.   -->
					<option value="<?=$val['id_commune'];?>"<?php if ($comm_id == $val['id_commune']) : ?>selected <? endif ; ?>><?=$val['commune']?></option>
						<? } ?>
				</select> <span class="commentaire">- d&eacute;termin&eacute;e selon la position du marqueur</span>
			</span>
		</p>
        <p>
			<span class="colbord">Secteur : </span>
			<span class="colcom">
				<input id="fsect" name="fsect" type="hidden" value="<? echo $secteur_id; ?>">
				<select id="fsect1" name="fsect1" disabled ="disabled">
					<option value="">...</option>
						<?
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
							$sql_infr = "SELECT id_sect , secteur
							FROM layers.l_secteurs
							ORDER BY secteur";
							$result = pg_query($sql_infr) or die ("Erreur requête") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant à la valeur selectionnée.   -->
					<option value="<?=$val['id_sect'];?>"<?php if ($secteur_id == $val['id_sect']) : ?>selected <? endif ; ?>><?=$val['secteur']?></option>
						<? } ?>
				</select> <span class="commentaire">- d&eacute;termin&eacute;e selon la position du marqueur</span>
			</span>
		</p>
		<p>
			<span class="colbord">Statut de la zone : </span>
			<span class="colzone">
				<input id="fstatut" name="fstatut" type="hidden" value="<? echo $statut_id; ?>">
				<select id="fstatut1" name="fstatut1" disabled ="disabled">
					<option value="">...</option>
						<?
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
							$sql_infr = "SELECT id_statutzone , statutzone
							FROM interventions.bib_statutszone";
							$result = pg_query($sql_infr) or die ("Erreur requête") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant à la valeur selectionnée.  -->
					<option value="<?=$val['id_statutzone'];?>" <?php if ($statut_id == $val['id_statutzone']) : ?>selected <? endif ; ?>><?=$val['statutzone'];?></option>
						<? } ?>
				</select>  <span class="commentaire">- d&eacute;termin&eacute; selon la position du marqueur</span/>
		</p>
	</div>

	