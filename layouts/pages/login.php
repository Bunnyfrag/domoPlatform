<?php

  //require ( 'parametres.php' );
  //require ( 'layouts/admin/login_func.php');

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

      echo(__FILE__).'<br/>';
      echo basename($_SERVER['SCRIPT_NAME']).'<br/>';
      echo $_SERVER['PHP_SELF'].'<br/>';
    ?>

    <div id="login_form">
      Utilisateur: <input type="text" name="login" /></br>
      Password: <input type="password" name="password" id="password"/></br>
      <input type="button" value="Connexion" />
    </div>

  </div>

</div>

<script>

$(document).ready( function(){
  $('#login_form input[type="button"]').click(function(ev){
    ev.preventDefault();

    $.ajax({
      url			: 'ajax/connect.php',
      type		: 'POST',
      data		: {
        login		: $('input[name="login"]').val(),
        password: hex_sha512( $('input[name="password"]').val() )
      },
      dataType	: 'text',
      success		: function( response ){
        // Response management
          if(response){
            console.log(response);
          }
      }
    });
  });

});
</script>
