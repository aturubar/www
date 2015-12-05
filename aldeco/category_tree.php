<?php
  include_once configuration.php;
  $conf = new JCfonfig;
  # CONSTANTS
  # root categories,update manually
  $KITCHEN = 915;
  $RUCHKI_MEBELNYE = 17;
  $ACC_FOR_BATH = 83;
  $FIRTNUTURA_DLYA_MEBELI = 70;
  $RUCHKI_DVERNYE = 194;
  $SVETILNIKI_MEBELNYE = 314;
  $DB_K2_CATEGORIES_TABLE_NAME = "K2_categories";

  # END CONSANTS
  $db_username = $conf->user;
  $db_name = $conf->db;
  $db_password = $conf->password;
  $db_hostname = $conf->host;
  $db_prefix = $conf->dbprefix;

  $mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);
  if($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno .') ' . $mysqli->connect_error);
  }



  # query for all categories
  $query_all_cats = "SELECT * FROM " . $db_prefix "_" . $DB_K2_CATEGORIES_TABLE_NAME;
  $endWhile = false;
  do{
    
  } while $endWhile;
?>
