<?php

// Include parameter file
	require ( 'parametres.php' );
	require ( 'layouts/admin/login_func.php');

// Choosing which page to show
	$pageAffiche = 'accueil';
	if ( isset( $_GET['page'] ) && $_GET['page'] != '' && file_exists( 'layouts/pages/' . $_GET['page'] . '.php' ) ){
		$pageAffiche = $_GET['page'];
	}

// Calling header
	require ( 'layouts/header.php' );

// Calling menu
	require ( 'layouts/menu.php' );

// Manage content
	echo '<div id="content">';
		require ( 'layouts/pages/' . $pageAffiche . '.php' );
	echo '</div>';

// Calling footer
	require ( 'layouts/footer.php' );

?>
