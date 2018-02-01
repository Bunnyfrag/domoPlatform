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
					var id = this.dataset.id;
					event.preventDefault();
					console.log(this.dataset.id);
					swal({
					  title: 'Ajouter un groupe',
					  html:
					    '<input type=text name="label"></input>' +
					    '<input type=text name="ordre"></input>' +
					    '<input type=text name="icone"></input>',
					  showCloseButton: true,
					  showCancelButton: true,
					  focusConfirm: false,
					  confirmButtonText:	'Valider',
					  cancelButtonText:		'Annuler',
					}).then((result) => {
						console.log(result);
						if (result.value) {
							$.ajax({
							  url: "ajax/update_groupe.php?fuseaction=saveData",
							  data: {
									id		: id,
									label	: $('input[name="label"]'),
									ordre	: $('input[name="ordre"]'),
									icone	: $('input[name="icone"]')
								}
							})
							  .done(function( msg ) {
							    alert( "Data Saved: " + msg );
							  });
						}
					});
				});
			});
			$('a.delete').each(function(){
				$(this).click(function(event){
					var id = this.dataset.id;
					swal({
					  title: 'Supprimer?',
					  text: "You won't be able to revert this!",
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
							    alert( "Data Saved: " + msg );
							  });
					    swal(
					      'Supprimé',
					      'Your file has been deleted.',
					      'success'
					    )
					  }
					})
				});
			});


		});

	});
</script>
