<?php

function getDataTableValues(){
	// On inclu le fichier avec tous les paramétres
		require '../parametres.php';


		$queryGroupe = 'SELECT id,label,ordre,icone FROM groupe ';

		if( isset($_REQUEST['sSearch']) ){
			$queryGroupe .= 'WHERE label LIKE :s ';
		}
		$stmtGroupe = $pdo->query( $queryGroupe );
		if( isset($_REQUEST['sSearch']) ){
			$stmtGroupe->bindParam(':s', $_REQUEST['sSearch']);
		}
		$groupes	= $stmtGroupe->fetchAll();

		$groupeValues = array();
		foreach ($groupes as $groupe ) {
			$groupeValue = array();
			$groupeValue[] = $groupe['label'];
			$groupeValue[] = $groupe['ordre'];
			$groupeValue[] = '<span class="fa '.$groupe['icone'].'"></span>';
			$groupeValue[] = '<a href="#" class="edit" data-id="'.$groupe['id'].'"><span style="color:green;" class="fa fa-edit"></span></a>';
			$groupeValue[] = '<a href="#" class="delete" data-id="'.$groupe['id'].'"><span style="color:red;" class="fa fa-trash"></span></a>';
			$groupeValues[] = $groupeValue;
		}
		$ret['iTotalRecords'] = count($groupes);
		$ret['iTotalDisplayRecords'] = count($groupes);
		$ret['aaData'] = $groupeValues;

	// Tout est Ok
		echo json_encode( $ret );

	// Fin d'éxécution
		exit(0);
}

function getValuesById($id){
	require '../parametres.php';
		$query = 'SELECT * FROM groupe WHERE id = :id ';
		$stmt = $pdo->query( $query );
		$stmt->bindParam(':id', $id);
		$result = $stmt->fetchAll();
		echo json_encode($result);
}


function saveData($id){
	require '../parametres.php';
	$query = 'SELECT * FROM groupe WHERE id = :id ';
	$stmt = $pdo->query( $query );
	$stmt->bindParam(':id', $id);
	$result = count( $stmt->fetchAll() );
	if( $result < 1){
		$request = 'INSERT INTO groupe 	VALUES :label , :ordre , :icone';
	}else{
		$request = 'UPDATE SET label = :label , ordre = :ordre , icone = :icone';
	}
	$prep = $pdo->prepare( $request );
	$prep->bindValue(':label', $_REQUEST['label']);
	$prep->bindValue(':ordre', $_REQUEST['ordre']);
	$prep->bindValue(':icone', $_REQUEST['icone']);
	return( $prep->execute() );

}

function delData($id){
	require '../parametres.php';
	$request = 'DELETE FROM groupe WHERE id = :id ;';
	$prep = $pdo->prepare( $request );
	$prep->bindValue(':id', $id);
	return( $prep->execute() );
}

switch ($_GET['fuseaction']) {
	case 'getDataTableValues':
		getDataTableValues();
	break;

	case 'getValuesById':
		if(isset ($_REQUEST['sonde_id']) ){
			getValuesById($_REQUEST['sonde_id']);
		}else{
			return -2;
		}
	break;

	case 'saveData':
		if(isset ($_REQUEST['sonde_id']) ){
			saveData($_REQUEST['sonde_id']);
		}else{
			return -2;
		}
	break;

	case 'delData':
		if(isset ($_REQUEST['sonde_id']) ){
			delData($_REQUEST['sonde_id']);
		}else{
			return -2;
		}
	break;

	default:
		return -1;
		break;
}
?>
