<?php

function getDataTableValues(){
	// On inclu le fichier avec tous les paramétres
		require '../parametres.php';


		$queryUtilisateur = 'SELECT utilisateur.id,nom,prenom,type_utilisateur_id,login,password,type_utilisateur.label FROM utilisateur ';

		if( isset($_REQUEST['sSearch']) ){
			$queryUtilisateur .= 'WHERE label LIKE :s OR nom LIKE :s OR prenom LIKE :s OR login LIKE :s ';
		}

		$queryUtilisateur.='LEFT JOIN type_utilisateur ON utilisateur.type_utilisateur_id=type_utilisateur.id ';

		$stmtUtilisateur = $pdo->query( $queryUtilisateur );
		if( isset($_REQUEST['sSearch']) ){
			$stmtUtilisateur->bindParam(':s', $_REQUEST['sSearch']);
		}
		$utilisateurs	= $stmtUtilisateur->fetchAll();

		$utilisateurValues = array();
		foreach ($utilisateurs as $utilisateur ) {
			$utilisateurValue = array();
			$utilisateurValue[] = $utilisateur['nom'];
			$utilisateurValue[] = $utilisateur['prenom'];
			$utilisateurValue[] = $utilisateur['label'];
			$utilisateurValue[] = $utilisateur['login'];
			$utilisateurValue[] = $utilisateur['password'];
			$utilisateurValue[] = '<a href="#" class="edit" data-id="'.$utilisateur['id'].'"><span style="color:green;" class="fa fa-edit"></span></a>';
			$utilisateurValue[] = '<a href="#" class="delete" data-id="'.$utilisateur['id'].'" data-lbl="'.$utilisateur['login'].'"><span style="color:red;" class="fa fa-trash"></span></a>';
			$utilisateurValues[] = $utilisateurValue;
		}
		$ret['iTotalRecords'] = count($utilisateurs);
		$ret['iTotalDisplayRecords'] = count($utilisateurs);
		$ret['aaData'] = $utilisateurValues;

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
		$query = 'SELECT * FROM utilisateur WHERE id = :id ';

		$stmt = $pdo->prepare( $query );
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$result = $stmt->fetch();
		return json_encode($result);
}


function saveData($id){
	require '../parametres.php';
	$query = 'SELECT * FROM utilisateur WHERE id = :id ';
	$stmt = $pdo->prepare( $query );
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$result = count( $stmt->fetchAll() );
	if( $result < 1){
		if ( !isset($_REQUEST['password']) || empty($_REQUEST['password']) ) {
			return(false);
		}
		$request = 'INSERT INTO utilisateur ( nom, prenom, type_utilisateur_id, login, password, date_derniere_connexion ) VALUES ( :nom, :prenom, :type_utilisateur_id, :login, :password , :date)';
	}else{
		$request = 'UPDATE utilisateur SET nom = :nom, prenom = :prenom, type_utilisateur_id = :type_utilisateur_id, login = :login';
		if ( isset($_REQUEST['password']) && !empty($_REQUEST['password']) ) {
			$request.=	', password = :password';
		}
		$request.=		' WHERE id = :id ';
	}
	$prep = $pdo->prepare( $request );
	$prep->bindValue(':nom', $_REQUEST['nom']);
	$prep->bindValue(':prenom', $_REQUEST['prenom']);
	$prep->bindValue(':type_utilisateur_id', $_REQUEST['type_utilisateur_id']);
	$prep->bindValue(':login', $_REQUEST['login']);
	$prep->bindValue(':password', hash('sha512', $_REQUEST['password']) );
	if( $result > 0){
		$prep->bindParam(':id', $id);
	}else{
		$prep->bindParam(':date', date("Y-m-d H:i:s") );
	}
	return( $prep->execute() );

}

function delData($id){
	require '../parametres.php';
	$request = 'DELETE FROM utilisateur WHERE id = :id ;';
	$prep = $pdo->prepare( $request );
	$prep->bindValue(':id', $id);
	return( $prep->execute() );
}

function getTypeUtilisateurListForSelect(){
	require '../parametres.php';
		$query = 'SELECT id,label FROM type_utilisateur ';
		$stmt = $pdo->prepare( $query );
		$stmt->execute();
		$typeUtilisateurs = $stmt->fetchAll();
		$html = '';
		foreach ($typeUtilisateurs as $typeUtilisateur ) {
		$html.= '<option value="'.$typeUtilisateur['id'].'">'.$typeUtilisateur['label'].'</option>
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

	case 'getTypeUtilisateurListForSelect':
		echo getTypeUtilisateurListForSelect();
	break;

	default:
		echo -1;
		break;
}
?>
