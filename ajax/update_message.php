<?php

// On inclu le fichier avec tous les paramétres
	require '../parametres.php';


// Extraction de la sonde

	$queryMsg = 'SELECT * FROM message ';

	if( isset($_REQUEST['sSearch']) ){
		$queryMsg .= 'WHERE Groupe-Type LIKE :s OR date LIKE :s';
	}
	$stmtMsg = $pdo->query( $queryMsg );
	if( isset($_REQUEST['sSearch']) ){
		$stmtMsg->bindParam(':s', $_REQUEST['sSearch']);
	}
	$msgs	= $stmtMsg->fetchAll();
	$ret['iTotalRecords'] = count($msgs);
	$ret['iTotalDisplayRecords'] = count($msgs);
	$ret['aaData'] = $msgs;

// Tout est Ok
	echo json_encode( $ret );

// Fin d'éxécution
	exit(0);

?>
