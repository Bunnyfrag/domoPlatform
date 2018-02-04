<?php

  require ( '../../parametres.php' );
  require ( 'login_func.php');

  sec_session_start(); // Our custom secure way of starting a PHP session.

  if (isset($_POST['login'], $_POST['p'])) {
    $login = $_POST['login'];
    $password = $_POST['p']; // The hashed password.

    if (login($login, $password, $pdo) == true) {
      // Login success
      header('Location: http://grit.esiee-amiens.fr:9980/~coulbaux_c/index.php?page=accueil');
    } else {
      // Login failed
      header('Location: http://grit.esiee-amiens.fr:9980/~coulbaux_c/index.php?page=login');
    }
  } else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
  }

?>
