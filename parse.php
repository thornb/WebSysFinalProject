<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require ('simple_html_dom.php');
include ('site_builder_functions.php');

//if We Posted
if(isset($_POST['URL'])){

  //if Logged in
  if(isset($_SESSION['username']) ){

    $configs = include('config.php');
    try{

      $host = $configs['host'];
      $user =  $configs['username'];
      $pass = $configs['password'];
      $dbname = $configs['database'];
      
      $dbconn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }

    catch (Exception $e){
      echo "Error: " . $e->getMessage();
    }

    //Add url to history
    $history_insert = $dbconn->prepare("INSERT INTO `history` (`username`,`url`) VALUES (:username, :URL)");
    $history_insert->execute( array(':username' => $_SESSION['username'], ':URL' => $_POST['URL']) );
    

  }

  //Indicate encoding? Parse page
	$html = file_get_contents($_POST["URL"]);
  echo $html;
}

?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
  <meta charset="utf-8">
  <!-- If you delete this meta tag World War Z will become a reality -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sansight - Parse</title>


  <?php if(isset($_POST['URL'])){ echo "<script src='./recurse.js'>";echo "</script>"; } ?>
</head>

 <!-- body content here -->

<body>
<!-- from site_builder_functions.php -->
  <?php menu_builder(); ?>

   <form action="parse.php" method="post" id="parse-form"  class="medium-6 columns">
     <h1 class="title">URL</h1>
     <?php if (isset($msg)) echo "<p class=\"err-msg\">$msg</p>"; $msg = NULL; ?>
     


     
      <input type="url" name="URL" id="url_input" value=""></input>
   	  <input id ="url_input_submit" type="submit" name="Parse" value="Submit" class="button expanded"></input>
     
   </form>


 <!-- foundation required function call -->
  <script>
  
    $(document).foundation();
  </script>
</body>
</html>