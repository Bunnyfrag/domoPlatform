<div id="pageAdmin">

	<div class="contentTitre">
		Administration
	</div>

	<div class="links">
		<a href="#"data-page='groupes'>Gestion des Groupes</a><br/>
		<a href="#"data-page='sondes'>Gestion des Sondes</a><br/>
		<a href="#"data-page='utilisateurs'>Gestion des Utilisateurs</a><br/>
	</div>

	<div id="adminContent"class="content">

	</div>

</div>

<script>
	$(document).ready( function(){

		//admin display
		$('a').click(function(e){
			e.preventDefault();
			$.ajax({
				url				: 'layouts/admin/'+this.dataset.page+'.php',
				type			: 'POST',
				dataType	: 'text',
				async			: false,
				success		: function( response ){
					$('#adminContent').html(response);
				}

			});
		});
	});
</script>
