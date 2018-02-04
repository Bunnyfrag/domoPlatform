<div id="menu">
	<ul>

		<li class="<?php echo ( $pageAffiche == 'accueil' ? 'selected' : '' ); ?>">
			<a href="index.php?page=accueil">
				<span class="fa fa-home"></span>Accueil
			</a>
		</li>

		<!--<li>
			<a href="index.php?page=groupe&id=1">
				<span class="fa fa-bed"></span>Chambre 1
			</a>
		</li>

		<li>
			<a href="index.php?page=groupe&id=2">
				<span class="fa fa-bed"></span>Chambre 2
			</a>
		</li>

		<li>
			<a href="index.php?page=groupe&id=3">
				<span class="fa fa-bed"></span>Chambre 3
			</a>
		</li>

		<li>
			<a href="index.php?page=groupe&id=4">
				<span class="fa fa-cutlery"></span>Cuisine
			</a>
		</li>-->
		<?php
			$query		= "SELECT id,label,icone,ordre FROM groupe ORDER BY ordre";
			$stmt		= $pdo->query($query);
			$groupes	= $stmt->fetchAll();
			if( isset($_GET['page']) ){
				$page = $_GET['page'];
			}else{
				$page = 1;
			}
			foreach( $groupes as $groupe ){
				$isSelected='';
				if( $page == 'groupe' && $_GET['id'] == $groupe['id'] ){
					 $isSelected=' class="selected"';
				}
				$element='<li'. $isSelected .'>'.
						 '	<a href="index.php?page=groupe&id='.$groupe['id'].'">'.
						 '	<span class="fa '.$groupe['icone'].'"></span>'.$groupe['label'].
						 '	</a>'.
						 '</li>';
				echo $element;
			}
		?>
		<!--<li>...</li>-->

		<li class="<?php echo ( $pageAffiche == 'message' ? 'selected' : '' ); ?>">
			<a href="index.php?page=message">
				<span class="fa fa-envelope"></span>Message
			</a>
		</li>

		<li class="<?php echo ( $pageAffiche == 'admin' ? 'selected' : '' ); ?>">
			<a href="index.php?page=admin">
				<span class="fa fa-gear"></span>Administration
			</a>
		</li>

		<li class="<?php echo ( $pageAffiche == 'login' ? 'selected' : '' ); ?>">
			<a href="index.php?page=login">
				<span class="fa fa-gear"></span>Connexion
			</a>
		</li>
	</ul>
	<br class="clear" />
</div>
