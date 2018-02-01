<div id="pageGroupe">

	<div class="contentTitre">
		Groupe
	</div>

	<div class="contentContent">
		<div class="dataTableContainer">
			<table id="GroupeTable" class="stripe row-border dataTable">

				<thead>
					<tr>
						<th>Label</th>
						<th>Ordre</th>
						<th>Icone</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td class="textCenter fontSize20">Icone</td>
						<td>Label</td>
						<td>Groupe</td>
					</tr>
				</tbody>

			</table>
		</div>
	</div>

</div>

<script>
	$(document).ready( function(){

		$('#messageTable').DataTable( {
				"ajax": './ajax/update_groupe.php',
				"iDisplayLength": 10
		} );

	});
</script>
