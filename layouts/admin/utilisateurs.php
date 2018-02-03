<div id="pageUtilisateur">

	<div class="contentTitre">
		Utilisateur
	</div>

	<div class="contentContent">
		<a href="#" class="edit" style="width:20px;"data-id="0">Ajouter un utilisateur</a>
		<div class="utilisateurTable">
			<table id="UtilisateurTable" class="stripe row-border dataTable">

				<thead>
					<tr>
						<th>Nom</th>
						<th>Prenom</th>
						<th>type d'utilisateur</th>
						<th>Login</th>
						<th>Mot de passe</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>Nom</td>
						<td>Prenom</td>
						<td>type utilisateur</td>
						<td>Login</td>
						<td>MDP</td>
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

			table = $('#UtilisateurTable').DataTable( {
					"ajax": './ajax/update_utilisateur.php?fuseaction=getDataTableValues',
					"iDisplayLength": 10
			} );

		table.on( 'draw', function () {
			$('a.edit').each(function(){
				$(this).click(function(event){
					let nom = '', prenom = '', type_utilisateur_id = '', login = '', password = '' ;
					var id = this.dataset.id;
					var typeUtilisateurList;
					event.preventDefault();
					console.log(this.dataset.id);


						//Retriving type_utilisateur list
						$.ajax({
							url				: 'ajax/update_utilisateur.php?fuseaction=getTypeUtilisateurListForSelect',
							type			: 'POST',
							dataType	: 'text',
							async			: false,
							success		: function( response ){
								typeUtilisateurList = response;
							}

						});

						$.ajax({
							url			: 'ajax/update_utilisateur.php?fuseaction=getValuesById',
							type		: 'POST',
							data		: {
								id	: this.dataset.id
							},
							dataType	: 'json',
							success		: function( response ){
								// Response management
									action = 'Ajouter';
									
									if(response){
										if (id>0) {
											action	= 'Modifier';
											nom			= response.nom;
											prenom	= response.prenom;
											type_utilisateur_id = response.type_utilisateur_id;
											login		= response.login;
											password= response.password;
										}
										swal({
										  title: action + ' un utilisateur',
										  html:
										    '<label for="nom">Nom</label><input type=text name="nom" value="'+nom+'">' +
										    '<label for="prenom">Prenom</label><input type=text name="prenom" value="'+prenom+'">' +
												'<label for="type_utilisateur_id">Type d\'utilisateur</label><select name="type_utilisateur_id" value="'+type_utilisateur_id+'">'+typeUtilisateurList+'</select><br/>'+
										    '<label for="login">Login</label><input type=text name="login" value="'+login+'">'+
												'<label for="password">Mot de passe</label><input type=text name="password" value="'+password+'">',
										  showCloseButton: true,
										  showCancelButton: true,
										  focusConfirm: false,
										  confirmButtonText:	'Valider',
										  cancelButtonText:		'Annuler',
										}).then((result) => {
											console.log(result);
											if (result.value) {

												$.ajax({
													url			: "ajax/update_utilisateur.php?fuseaction=saveData",
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
							  url: "ajax/update_utilisateur.php?fuseaction=delData",
							  data: { id: id }
							})
							  .done(function( msg ) {
									swal(
							      'Confirmé',
							      'Utilisateur supprimé.',
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
