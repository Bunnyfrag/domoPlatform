<?php

		require '../parametres.php';
		$query = 'SELECT password FROM utilisateur WHERE login = :login ';
		$stmt = $pdo->prepare( $query );
		$stmt->bindParam(':login', $_REQUEST['login']);
		$stmt->execute();
		$result = $stmt->fetch();
		if ( $result['password'] == $_REQUEST['password'] ) {
			echo 'yes';
			/*$request = 'UPDATE utilisateur SET date_derniere_connexion = :date WHERE login = :login';
			$prep = $pdo->prepare( $request );
			$prep->bindParam(':date', date("Y-m-d H:i:s") );
			$prep->bindValue(':login', $_REQUEST['login']);
			$prep->execute();
			*/
		}else{
			echo 'no';
		}
		exit(0);
