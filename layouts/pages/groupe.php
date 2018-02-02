<?php

// Extracting the group ID
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
		$query = $pdo->prepare('SELECT id, code, valeur, label, type_sonde_id, groupe_id
														FROM sonde
														WHERE groupe_id = :groupID');
		$query->execute(array(':groupID' => $groupeId));
		$sondes	= $query->fetchAll();

		foreach( $sondes as $sonde ){
			$img='';
			$btnLayout='';

			switch($sonde['type_sonde_id']){
				case TYPE_SONDE_TEMPERATURE:
				$img = 'thermometre.png';
				$btnLayout = '<div class="number">'.$sonde['valeur'].'°C</div>';
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
				$img = 'volet_';
				if ($sonde['valeur'] == 0){
								$img.= 'ouvert';
							} elseif ($sonde['valeur'] == 1){
								$img.= 'moitie_ouvert';
							} else {
								$img.= 'ferme';
							}
				$img.= '.png';
				$btnLayout = '<div class="blocBtn">'.
									'	<input class="fenetreAction" name="btnFenetre1" value="';
				$btnLayout.= $sonde['valeur'] == 0?'Abaisser':'Ouvrir';	// 1st button
				$btnLayout.= '" type="button" data-action="';
				$btnLayout.= $sonde['valeur'] == 0?'semi-open':'open';
				$btnLayout.= '">'.
									'</div>'.
									'<div class="blocBtn">'.
									'	<input class="fenetreAction" name="btnFenetre2" value="';
				$btnLayout.= $sonde['valeur'] == 2?'Entrouvrir':'Fermer';	// 2nd button
				$btnLayout.= '" type="button" data-action="';
				$btnLayout.= $sonde['valeur'] == 2?'semi-open':'close';
				$btnLayout.='">'.
									'</div>';
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

		$('#pageGroupe').on( 'click', 'input[name="btnLampe"]', function( e ){	/* Toggle the light */
			// Stop propagating
				e.preventDefault();

			// Fetch the parent
				var parent = $(this).parents('.sonde');

			// Fetch the probe ID
				var sondeId = parent.data('sonde-id');

			// Send data to the server
				$.ajax({
					url			: 'ajax/update_lampe.php',
					type		: 'POST',
					data		: {
						sonde_id	: sondeId
					},
					dataType	: 'json',
					success		: function( response ){
						// Response management
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
			// Stop propagating
				e.preventDefault();

			// Fetch the parent
				var parent = $(this).parents('.sonde');

			// Fetch the probe ID
				var sondeId = parent.data('sonde-id');

			// Send data to the server
				$.ajax({
					url			: 'ajax/update_porte.php',
					type		: 'POST',
					data		: {
						sonde_id	: sondeId
					},
					dataType	: 'json',
					success		: function( response ){
						// Response management
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
			// Stop propagating
				e.preventDefault();

			// Fetch the parent
				var parent = $(this).parents('.sonde');

			// Fetch the probe ID
				var sondeId = parent.data('sonde-id');

			// Send data to the server
				$.ajax({
					url			: 'ajax/update_chauffage.php',
					type		: 'POST',
					data		: {
						sonde_id	: sondeId,
						action		: this.dataset.action
					},
					dataType	: 'json',
					success		: function( response ){
						// Response management
							resultId = parseInt( response.result );
							if ( resultId > 0 ){
								parent.find('.number').html(response.newValeur)+'°';
							} else {
								alert('Erreur lors de la mise à jour du chauffage');
							}
					}
				});
		});


		$('#pageGroupe').on( 'click','.fenetreAction', function( e ){
			// Stop propagating
				e.preventDefault();

			// Fetch the parent
				var parent = $(this).parents('.sonde');

			// Fetch the probe ID
				var sondeId = parent.data('sonde-id');

			// Send data to the server
				$.ajax({
					url			: 'ajax/update_fenetre.php',
					type		: 'POST',
					data		: {
						sonde_id	: sondeId,
						action		: this.dataset.action
					},
					dataType	: 'json',
					success		: function( response ){
						// Response management
							resultId = parseInt( response.result );
							if ( resultId > 0 ){
								if ( response.newValeur == 0 ){
									parent.find('.imageSeule img').attr('src', 'img/volet_ouvert.png');
									parent.find('input[name="btnFenetre1"]').val('Abaisser');
									parent.find('input[name="btnFenetre1"]').attr('data-action', 'semi-open');
									parent.find('input[name="btnFenetre2"]').val('Fermer');
									parent.find('input[name="btnFenetre2"]').attr('data-action', 'close');
								} else if (response.newValeur == 1) {
									parent.find('.imageSeule img').attr('src', 'img/volet_moitie_ouvert.png');
									parent.find('input[name="btnFenetre1"]').val('Ouvrir');
									parent.find('input[name="btnFenetre1"]').attr('data-action', 'open');
									parent.find('input[name="btnFenetre2"]').val('Fermer');
									parent.find('input[name="btnFenetre2"]').attr('data-action', 'close');
								} else {
									parent.find('.imageSeule img').attr('src', 'img/volet_ferme.png');
									parent.find('input[name="btnFenetre1"]').val('Ouvrir');
									parent.find('input[name="btnFenetre1"]').attr('data-action', 'open');
									parent.find('input[name="btnFenetre2"]').val('Entrouvrir');
									parent.find('input[name="btnFenetre2"]').attr('data-action', 'semi-open');
								}
							} else {
								alert('Erreur lors de la mise à jour de la fenetre');
							}
					}
				});
		});


	});

</script>
