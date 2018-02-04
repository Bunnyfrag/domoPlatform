<?php

  //require ( 'parametres.php' );
  //require ( 'layouts/admin/login_func.php');

  sec_session_start();

  if (login_check($pdo) == true) {
    $logged = 'Connecté';
  } else {
    $logged = 'Déconnecté';
  }

?>

<script type="text/JavaScript" src="js/sha512.js"></script>

<div id="pageLogin">

  <div class="contentTitre">
    Page de connexion
  </div>

  <div class="contentContent">

    <?php
      if (isset($_GET['error'])) {
        echo '<p class="error">Erreur lors de la connexion</p>';
      }
    ?>

    <form action="layouts/admin/login_process.php" method="post" name="login_form">
      Utilisateur: <input type="text" name="login" /></br>
      Password: <input type="password" name="password" id="password"/></br>
      <input type="button" value="Connexion" onclick="formhash(this.form, this.form.password);" />
    </form>

    <?php
      if (login_check($pdo) == true) {
        echo '<p>' . $logged . ' en tant que ' . htmlentities($_SESSION['username']) . '.</p>';
        echo '<p>Voulez vous changer d\'utilisateur ? <a href="https://omfgdogs.com/">Déconnexion</a>.</p>';
      } else {
        echo '<p>' . $logged . '.</p>';
        echo "<p>Si vous n'êtes pas enregistré, veuillez <a href='https://omfgdogs.com/'>le faire ici</a></p>";
      }
    ?>

  </div>

</div>

<script>

function formhash(form, password) {
    // Create a new element input, this will be our hashed password field.
    var p = document.createElement("input");

    // Add the new element to our form.
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    // Make sure the plaintext password doesn't get sent.
    password.value = "";

    // Finally submit the form.
    form.submit();
}

function regformhash(form, uid, login, password, conf) {
     // Check each field has a value
    if (uid.value == ''         ||
          login.value == ''     ||
          password.value == ''  ||
          conf.value == '') {

        alert('You must provide all the requested details. Please try again');
        return false;
    }

    // Check the username

    re = /^\w+$/;
    if(!re.test(form.username.value)) {
        alert("Username must contain only letters, numbers and underscores. Please try again");
        form.username.focus();
        return false;
    }

    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('Passwords must be at least 6 characters long.  Please try again');
        form.password.focus();
        return false;
    }

    // At least one number, one lowercase and one uppercase letter
    // At least six characters

    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        return false;
    }

    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }

    // Create a new element input, this will be our hashed password field.
    var p = document.createElement("input");

    // Add the new element to our form.
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    // Make sure the plaintext password doesn't get sent.
    password.value = "";
    conf.value = "";

    // Finally submit the form.
    form.submit();
    return true;
}

</script>
