<?php

function getDataTableValues(){
	// On inclu le fichier avec tous les paramétres
		require '../parametres.php';


		$queryGroupe = 'SELECT id,label,ordre,icone FROM groupe ';

		if( isset($_REQUEST['sSearch']) ){
			$queryGroupe .= 'WHERE label LIKE :s OR icone LIKE :s ';
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
			$groupeValue[] = '<a href="#" class="delete" data-id="'.$groupe['id'].'" data-lbl="'.$groupe['label'].'"><span style="color:red;" class="fa fa-trash"></span></a>';
			$groupeValues[] = $groupeValue;
		}
		$ret['iTotalRecords'] = count($groupes);
		$ret['iTotalDisplayRecords'] = count($groupes);
		$ret['aaData'] = $groupeValues;

	// Tout est Ok
		return json_encode( $ret );

	// Fin d'éxécution
		exit(0);
}

function getValuesById($id){
		if ($id==0) {
			return json_encode(true);
		}
	require '../parametres.php';
		$query = 'SELECT * FROM groupe WHERE id = :id ';
		//$stmt = $pdo->query( $query );
		$stmt = $pdo->prepare( $query );
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$result = $stmt->fetch();
		return json_encode($result);
}


function saveData($id){
	require '../parametres.php';
	$query = 'SELECT * FROM groupe WHERE id = :id ';
	$stmt = $pdo->prepare( $query );
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$result = count( $stmt->fetchAll() );
	if( $result < 1){
		$request = 'INSERT INTO groupe ( label, ordre, icone ) VALUES ( :label , :ordre , :icone)';
	}else{
		$request = 'UPDATE groupe SET label = :label , ordre = :ordre , icone = :icone WHERE id = :id';
	}
	$prep = $pdo->prepare( $request );
	$prep->bindValue(':label', $_REQUEST['label']);
	$prep->bindValue(':ordre', $_REQUEST['ordre']);
	$prep->bindValue(':icone', $_REQUEST['icone']);
	if( $result > 0){
		$prep->bindParam(':id', $id);
	}
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
		echo getDataTableValues();
	break;

	case 'getValuesById':
		if(isset ($_REQUEST['id']) ){
			echo getValuesById($_REQUEST['id']);
		}else{
			echo -2;
		}
	break;

	case 'saveData':
		if(isset ($_REQUEST['id']) ){
			echo saveData($_REQUEST['id']);
		}else{
			echo -2;
		}
	break;

	case 'delData':
		if(isset ($_REQUEST['id']) ){
			echo delData($_REQUEST['id']);
		}else{
			echo -2;
		}
	break;

	default:
		echo -1;
		break;
}
?>
