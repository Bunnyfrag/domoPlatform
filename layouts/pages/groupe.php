<?php

// Extraction de l'id
	$groupeId = 0;
	if ( isset( $_GET['id'] ) && $_GET['id'] > 0 ){
		$groupeId = $_GET['id'];
	}

?>

<div id="pageGroupe">

	<div class="contentTitre">
		Nom du groupe [<?php echo $groupeId; ?>]
	</div>

	<div class="contentContent">
	<?php
		//TODO QUERY IS BAD (USE BIND INSTEAD)
		$query		= "SELECT id,code,valeur,label,type_sonde_id,groupe_id FROM sonde WHERE groupe_id =".$groupeId.";";
		$stmt		= $pdo->query($query);
		$sondes		= $stmt->fetchAll();
		foreach( $sondes as $sonde ){
			$img='';
			$btnLayout='';

			switch($sonde['type_sonde_id']){
				case TYPE_SONDE_TEMPERATURE:
				$img = 'thermometre.png';
				$btnLayout = '<div class="number">'.$sonde['valeur'].'°c</div>';
				break;

				case TYPE_SONDE_HORAIRE:
				$img = '';
				break;

				case TYPE_SONDE_PORTE:
				$img = 'porte_';
				$img.= $sonde['valeur'] == 0?'ferme':'ouverte';
				$img.= '.png';
				$btnLayout = '<div class="blocBtn">'.
							 '	<input name="btnPorte" value="';
				$btnLayout.= $sonde['valeur'] == 0?'ouvrir':'Fermer';
				$btnLayout.= '"type="button">'.
							 '</div>';
				break;

				case TYPE_SONDE_FENETRE:
				$img = 'volet_ferme.png';
				$btnLayout = '<img src="img/volet_ouvert.png" style="position: relative;bottom: 70px;left: 50px; width:80px;">';
				$btnLayout.= '<img src="img/volet_moitie_ouvert.png" style="position: relative;bottom: 70px;left: 40px; width:80px;">';
				break;

				case TYPE_SONDE_ECLAIRAGE:
				$img = 'lampe_';
				$img.= $sonde['valeur'] == 0?'eteinte':'allumee';
				$img.= '.png';
				$btnLayout = '<div class="blocBtn">'.
							 '	<input name="btnLampe" value="';
				$btnLayout.= $sonde['valeur'] == 0?'Eteindre':'Allumer';
				$btnLayout.= '" type="button">'.
				'</div>';
				break;

				case TYPE_SONDE_LUMINOSITE:
				$img = 'luminosite.png';
				$btnLayout = '<div class="number">'.$sonde['valeur'].'%</div>';
				break;

				case TYPE_SONDE_CHAUFFAGE:
				case TYPE_SONDE_TEMPERATURE:
				$img = 'thermometre.png';
				$btnLayout = '<span class="chauffageAction fa fa-plus-circle" style="color:green;font-size:xx-large;position: relative;top: 25px;right: 50px;" data-action="add"></span>';
				$btnLayout.= '<div class="number">'.$sonde['valeur'].'°c</div>';
				$btnLayout.= '<span class="chauffageAction fa fa-minus-circle" style="color:red;font-size:xx-large; position: relative;top: 70px;right: 50px;" data-action="del"></span>';
				break;

			}

			$element= '<div class="sonde typeSonde'.$sonde['type_sonde_id'].'" data-sonde-id="'. $sonde['id'] .'">'.
					  '	<div class="titre">'. $sonde['label'] .'</div>'.
					  '	<div class="content">';
			if(!empty($img)){
				$element.=
					  '		<div class="imageSeule">'.
					  '			<img src="img/'.$img.'">'.
					  '		</div>';
			}
			$element.= $btnLayout;
			$element.='	</div><!-- content -->'.
					  '</div>';
			echo $element;
		}
		?>

		<!--
		<div class="sonde typeSonde5" data-sonde-id="3">
			<div class="titre">Lampe du plafond</div>
			<div class="content">
				<div class="imageSeule">
					<img src="img/lampe_eteinte.png">
				</div>
				<div class="blocBtn">
					<input name="btnLampe" value="Allumer" type="button">
				</div>
			</div>
		</div>-->
	</div>

</div>

<script>
	$(document).ready( function(){

		$('#pageGroupe').on( 'click', 'input[name="btnLampe"]', function( e ){
			// Stop la propagation
				e.preventDefault();

			// Récupération du parent
				var parent = $(this).parents('.sonde');

			// Récupérationde l'id de la sonde
				var sondeId = parent.data('sonde-id');

			// Envoi des données au serveur
				$.ajax({
					url			: 'ajax/update_lampe.php',
					type		: 'POST',
					data		: {
						sonde_id	: sondeId
					},
					dataType	: 'json',
					success		: function( response ){
						// Gestion de la réponse
							resultId = parseInt( response.result );
							if ( resultId > 0 ){
								if ( response.newValeur == 1 ){
									parent.find('.imageSeule img').attr('src', 'img/lampe_allumee.png');
									parent.find('input[name="btnLampe"]').val('Eteindre');
								} else {
									parent.find('.imageSeule img').attr('src', 'img/lampe_eteinte.png');
									parent.find('input[name="btnLampe"]').val('Allumer');
								}
							} else {
								alert('Erreur lors de la mise à jour de la lampe');
							}
					}
				});
		});

		$('#pageGroupe').on( 'click', 'input[name="btnPorte"]', function( e ){
			// Stop la propagation
				e.preventDefault();

			// Récupération du parent
				var parent = $(this).parents('.sonde');

			// Récupérationde l'id de la sonde
				var sondeId = parent.data('sonde-id');

			// Envoi des données au serveur
				$.ajax({
					url			: 'ajax/update_porte.php',
					type		: 'POST',
					data		: {
						sonde_id	: sondeId
					},
					dataType	: 'json',
					success		: function( response ){
						// Gestion de la réponse
							resultId = parseInt( response.result );
							if ( resultId > 0 ){
								if ( response.newValeur == 1 ){
									parent.find('.imageSeule img').attr('src', 'img/porte_ouverte.png');
									parent.find('input[name="btnPorte"]').val('Fermer');
								} else {
									parent.find('.imageSeule img').attr('src', 'img/porte_ferme.png');
									parent.find('input[name="btnPorte"]').val('Ouvrir');
								}
							} else {
								alert('Erreur lors de la mise à jour de la porte');
							}
					}
				});
		});

		$('#pageGroupe').on( 'click','.chauffageAction', function( e ){
			// Stop la propagation
				e.preventDefault();

			// Récupération du parent
				var parent = $(this).parents('.sonde');

			// Récupérationde l'id de la sonde
				var sondeId = parent.data('sonde-id');

			// Envoi des données au serveur
				$.ajax({
					url			: 'ajax/update_chauffage.php',
					type		: 'POST',
					data		: {
						sonde_id	: sondeId,
						action		: this.dataset.action
					},
					dataType	: 'json',
					success		: function( response ){
						// Gestion de la réponse
							resultId = parseInt( response.result );
							if ( resultId > 0 ){
								parent.find('.number').html(response.newValeur)+'°';
							} else {
								alert('Erreur lors de la mise à jour du chauffage');
							}
					}
				});
		});
	});

</script>
