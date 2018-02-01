<div id="pageAdmin">

	<div class="contentTitre">
		Administration
	</div>

	<div class="links">
		<a href="#&adm=groupes">stuff</a><br/>
		<a href="#">stuff</a><br/>
		<a href="#">stuff</a><br/>
	</div>

	<div class="content">
	<?php
	if ( isset( $_GET['adm'] ) && $_GET['adm'] != '' && file_exists( './layouts/admin/' . $_GET['adm'] . '.php' ) ){
		include('layouts/admin/' . $_GET['adm'] . '.php');
	}
	?>
	</div>

</div>

<script>
	$(document).ready( function(){
		$('a').click(function(e){
			e.preventDefault();

		});
	});
</script>
