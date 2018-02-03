<div id="pageSonde">

	<div class="contentTitre">
		Sonde
	</div>

	<div class="contentContent">
		<a href="#" class="edit" style="width:20px;"data-id="0">Ajouter un sonde</a>
		<div class="sondeTable">
			<table id="SondeTable" class="stripe row-border dataTable">

				<thead>
					<tr>
						<th>Code</th>
						<th>Valeur</th>
						<th>Label</th>
						<th>Type de sonde</th>
						<th>Groupe</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>code</td>
						<td>valeur</td>
						<td>label</td>
						<td>type</td>
						<td>groupe</td>
						<td>Edition</td>
						<td>Deletion</td>
					</tr>
				</tbody>

			</table>
		</div>
	</div>

</div>

<script>
	$(document).ready( function(){

			table = $('#SondeTable').DataTable( {
					"ajax": './ajax/update_sonde.php?fuseaction=getDataTableValues',
					"iDisplayLength": 10
			} );

		table.on( 'draw', function () {
			$('a.edit').each(function(){
				$(this).click(function(event){
					let label = '', valeur = '', code = '', type = '', groupe = '';
					var id = this.dataset.id;
					var groupeList,typeSondeList;
					event.preventDefault();

						//Retriving groupe list
						$.ajax({
							url				: 'ajax/update_sonde.php?fuseaction=getGroupeListForSelect',
							type			: 'POST',
							dataType	: 'text',
							async			: false,
							success		: function( response ){
								groupeList = response;
							}

						});

						//Retriving type_sonde list
						$.ajax({
							url				: 'ajax/update_sonde.php?fuseaction=getTypeSondeListForSelect',
							type			: 'POST',
							dataType	: 'text',
							async			: false,
							success		: function( response ){
								typeSondeList = response;
							}

						});

						//load form
						$.ajax({
							url			: 'ajax/update_sonde.php?fuseaction=getValuesById',
							type		: 'POST',
							data		: {
								id	: this.dataset.id
							},
							dataType	: 'json',
							async			: false,
							success		: function( response ){
								// Response management
									if(response){
										action = 'Ajouter';

										if (id>0) {
											action = 'Modifier';
											code		= response.code;
											valeur	= response.valeur;
											label		= response.label;
											type		= response.type;
											groupe	= response.groupe;
										}
										swal({
										  title: Action + ' une sonde',
										  html:
										    '<label for="code">Code</label><input type=text name="code" value="'+code+'">' +
										    '<label for="valeur">Valeur</label><input type=text name="valeur" value="'+valeur+'">' +
										    '<label for="label">Label</label><input type=text name="label" value="'+label+'">'+
												'<label for="groupe_id">Groupe</label><select name="groupe_id" value="'+groupe+'">'+groupeList+'</select><br/>'+
												'<label for="type_sonde_id">Type</label><select type=text name="type_sonde_id" value="'+type+'">'+typeSondeList+'</select><br/>',
										  showCloseButton: true,
										  showCancelButton: true,
										  focusConfirm: false,
										  confirmButtonText:	'Valider',
										  cancelButtonText:		'Annuler',
										}).then((result) => {
											if (result.value) {

												$.ajax({
													url			: "ajax/update_sonde.php?fuseaction=saveData",
													type		: 'POST',
													async		: false,
													data		: {
														id			: id,
														label		: $('input[name="label"]').val(),
														valeur	: $('input[name="valeur"]').val(),
														code		: $('input[name="code"]').val(),
														type_sonde_id		: $('select[name="type_sonde_id"]').val(),
														groupe_id	: $('select[name="groupe_id"]').val(),
													},
													dataType	: 'json',
													success		: function( response ){
															 table.ajax.reload();
													}
												});

											}
										});

									}else{
										alert('Erreur lors de la mise à jour du formulaire');
										return;
									}
									resultId = parseInt( response.result );
							}
						});


				});
			});
			$('a.delete').each(function(){
				$(this).click(function(event){
					var id = this.dataset.id;
					swal({
					  title: 'Supprimer?',
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor:	'#3085d6',
					  cancelButtonColor:	'#d33',
					  confirmButtonText:	'Oui',
						cancelButtonText:		'Non'
					}).then((result) => {
					  if (result.value) {
							$.ajax({
							  url: "ajax/update_sonde.php?fuseaction=delData",
							  data: { id: id }
							})
							  .done(function( msg ) {
									swal(
										'Confirmé',
										'Sonde supprimé.',
										'success'
									);
									table.ajax.reload();
							  });

					  }
					})
				});
			});


		});

	});
</script>
