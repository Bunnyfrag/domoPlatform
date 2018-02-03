<?php

function getDataTableValues(){
	// On inclu le fichier avec tous les paramétres
		require '../parametres.php';


		$querySonde = 'SELECT sonde.id, sonde.code, sonde.valeur, sonde.label , sonde.type_sonde_id, type_sonde.label as type_sonde_label, sonde.groupe_id, groupe.label as groupe_label  FROM sonde ';

		if( isset($_REQUEST['sSearch']) ){
			$querySonde .= 'WHERE label LIKE :s ';
		}

		$querySonde.='LEFT JOIN type_sonde ON sonde.type_sonde_id=type_sonde.id LEFT JOIN groupe ON sonde.groupe_id=groupe.id ';

		$stmtSonde = $pdo->query( $querySonde );
		if( isset($_REQUEST['sSearch']) ){
			$stmtSonde->bindParam(':s', $_REQUEST['sSearch']);
		}

		$sondes	= $stmtSonde->fetchAll();

		$sondeValues = array();
		foreach ($sondes as $sonde ) {
			$sondeValue = array();
			$sondeValue[] = $sonde['code'];
			$sondeValue[] = $sonde['valeur'];
			$sondeValue[] = $sonde['label'];
			$sondeValue[] = $sonde['type_sonde_label'];
			$sondeValue[] = $sonde['groupe_label'];
			$sondeValue[] = '<a href="#" class="edit" data-id="'.$sonde['id'].'"><span style="color:green;" class="fa fa-edit"></span></a>';
			$sondeValue[] = '<a href="#" class="delete" data-id="'.$sonde['id'].'"><span style="color:red;" class="fa fa-trash"></span></a>';
			$sondeValues[] = $sondeValue;
		}
		$ret['iTotalRecords'] = count($sondes);
		$ret['iTotalDisplayRecords'] = count($sondes);
		$ret['aaData'] = $sondeValues;

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
		$query = 'SELECT * FROM sonde WHERE id = :id ';
		$stmt = $pdo->prepare( $query );
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$result = $stmt->fetch();
		return json_encode($result);
}


function saveData($id){
	require '../parametres.php';
	$query = 'SELECT * FROM sonde WHERE id = :id ';
	$stmt = $pdo->prepare( $query );
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$result = count( $stmt->fetchAll() );
	if( $result < 1){
		$request = 'INSERT INTO sonde ( code, valeur, label, type_sonde_id, groupe_id ) VALUES ( :code, :valeur, :label, :type_sonde_id, :groupe_id)';
	}else{
		$request = 'UPDATE sonde SET code = :code, valeur = :valeur, label = :label, type_sonde_id = :type_sonde_id, groupe_id = :groupe_id WHERE id = :id';
	}
	$prep = $pdo->prepare( $request );
	$prep->bindValue(':code', $_REQUEST['code']);
	$prep->bindValue(':valeur', $_REQUEST['valeur']);
	$prep->bindValue(':label', $_REQUEST['label']);
	$prep->bindValue(':type_sonde_id', $_REQUEST['type_sonde_id']);
	$prep->bindValue(':groupe_id', $_REQUEST['groupe_id']);
	if( $result > 0){
		$prep->bindParam(':id', $id);
	}
	return( $prep->execute() );

}

function delData($id){
	require '../parametres.php';
	$request = 'DELETE FROM sonde WHERE id = :id ;';
	$prep = $pdo->prepare( $request );
	$prep->bindValue(':id', $id);
	return( $prep->execute() );
}

function getGroupeListForSelect(){
	require '../parametres.php';
		$query = 'SELECT id,label FROM groupe ';
		$stmt = $pdo->prepare( $query );
		$stmt->execute();
		$groupes = $stmt->fetchAll();
		$html = '
';
		foreach ($groupes as $groupe ) {
		$html.= '<option value="'.$groupe['id'].'">'.$groupe['label'].'</option>
';
		}
		return $html;
}

function getTypeSondeListForSelect(){
	require '../parametres.php';
		$query = 'SELECT id,label FROM type_sonde ';
		$stmt = $pdo->prepare( $query );
		$stmt->execute();
		$typeSondes = $stmt->fetchAll();
		$html = '';
		foreach ($typeSondes as $typeSonde ) {
		$html.= '<option value="'.$typeSonde['id'].'">'.$typeSonde['label'].'</option>
		';
		}
		return $html;
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

	case 'getGroupeListForSelect':
		echo getGroupeListForSelect();
	break;

	case 'getTypeSondeListForSelect':
		echo getTypeSondeListForSelect();
	break;

	default:
		echo -1;
		break;
}
?>
