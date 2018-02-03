<div id="pageGroupe">

	<div class="contentTitre">
		Groupe
	</div>

	<div class="contentContent">
		<a href="#" class="edit" style="width:20px;"data-id="0">Ajouter un groupe</a>
		<div class="groupeTable">
			<table id="GroupeTable" class="stripe row-border dataTable">

				<thead>
					<tr>
						<th>Label</th>
						<th>Ordre</th>
						<th>Icone</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td class="textCenter fontSize20">Icone</td>
						<td>Label</td>
						<td>Groupe</td>
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

			table = $('#GroupeTable').DataTable( {
					"ajax": './ajax/update_groupe.php?fuseaction=getDataTableValues',
					"iDisplayLength": 10
			} );

		table.on( 'draw', function () {
			$('a.edit').each(function(){
				$(this).click(function(event){
					let label = '',ordre = '',icone = '';
					var id = this.dataset.id;
					event.preventDefault();
					console.log(this.dataset.id);

						$.ajax({
							url			: 'ajax/update_groupe.php?fuseaction=getValuesById',
							type		: 'POST',
							data		: {
								id	: this.dataset.id
							},
							dataType	: 'json',
							success		: function( response ){
								// Response management
									if(response){
										if (id>0) {
										label = response.label;
										ordre = response.ordre;
										icone = response.icone;
										}
										swal({
										  title: 'Ajouter un groupe',
										  html:
										    '<label for="label">Label</label><input type=text name="label" value="'+label+'">' +
										    '<label for="ordre">Ordre</label><input type=text name="ordre" value="'+ordre+'">' +
										    '<label for="icone">Icone</label><input type=text name="icone" value="'+icone+'">',
										  showCloseButton: true,
										  showCancelButton: true,
										  focusConfirm: false,
										  confirmButtonText:	'Valider',
										  cancelButtonText:		'Annuler',
										}).then((result) => {
											console.log(result);
											if (result.value) {

												$.ajax({
													url			: "ajax/update_groupe.php?fuseaction=saveData",
													type		: 'POST',
													data		: {
														id		: id,
														label	: $('input[name="label"]').val(),
														ordre	: $('input[name="ordre"]').val(),
														icone	: $('input[name="icone"]').val()
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
									console.log(label);
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
							  url: "ajax/update_groupe.php?fuseaction=delData",
							  data: { id: id }
							})
							  .done(function( msg ) {
									swal(
							      'Confirmé',
							      'Groupe supprimé.',
							      'success'
							    )
									table.ajax.reload();
							  });

					  }
					})
				});
			});


		});

	});
</script>
