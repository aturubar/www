<?php
  error_reporting(-1);
  include_once 'configuration.php';
  $conf = new JConfig;
  // CONSTANTS
  // root categories,update manually
//  $KITCHEN = 915;
//  $RUCHKI_MEBELNYE = 17;
//  $ACC_FOR_BATH = 83;
//  $FIRTNUTURA_DLYA_MEBELI = 70;
//  $RUCHKI_DVERNYE = 194;
//  $SVETILNIKI_MEBELNYE = 314;
  $DB_K2_CATEGORIES_TABLE_NAME = "k2_categories";

  // END CONSANTS
  $db_username = $conf->user;
  $db_name = $conf->db;
  $db_password = $conf->password;
  $db_hostname = $conf->host;
  $db_prefix = $conf->dbprefix;


  $pattern = '/[\d]{1,}[\.,]{0,1}[\d]{0,}/';// 1to extract rate from category description
//  $sql_cats_fields = 'id,parent,description'
  $mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);
  if($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno .') ' . $mysqli->connect_error);
  }

echo "<p>++++++++++++++++++++++++START+++++++++++++++++++++</p>";

// get all categories with exchange rate

  $sql_root_cats = 'SELECT id,description FROM ' . $db_prefix . $DB_K2_CATEGORIES_TABLE_NAME . ' WHERE description IS NOT NULL';
  print $sql_root_cats;
  $result = $mysqli->query($sql_root_cats);
  if ( $result->num_rows > 0 ) {
    while ($row = $result->fetch_assoc() ) {
      if( strpos(strtolower($row['description']), 'img') === false && strpos( $row['description'], ':') !== false) {
        $line = split("[:]", $row['description']);
        //$rate = preg_replace('/[\D]{0,}[\.]{0,1}[\D]{0,}/', '', $line[1]);
        //$rate = preg_replace()
        print "<br>$line[1]<br>";
        preg_match($pattern, $line[1], $matches);
        $rate = str_replace( ",", ".", $matches[0]);
        print "<br>";
        print ( "!!!!".$rate."!!!!" );
        print (gettype( $rate ));
        if( is_numeric ( $rate )) {
          $cats_hash[$rate] =  array($row['id']);
        }
      }
    }
  }
echo "<p>++++++++++++++++++++++++END+++++++++++++++++++++++</p>";
print_r($cats_hash);

foreach ($cats_hash as $key => $value){
  do {
    $query = 'SELECT id FROM ' . $db_prefix . $DB_K2_CATEGORIES_TABLE_NAME . ' WHERE parent=' . $cats_hash[$key][ count($cats_hash[$key]) - 1 ];
    print "<br> query is $query <br>";
    $result = $mysqli->query($query);
    while( $row = $result->fetch_assoc() ){
      array_push($cats_hash[$key], $row['id']);
    }
  } while ($result->num_rows > 0);
}
print "<p>!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!</p>";
var_dump($cats_hash);
$handle = fopen('rates.php', 'w');
fwrite($handle, '<?php ');
fwrite($handle, '$rates_for_cats = array ( ');
end($cats_hash);
$last_key = key( $cats_hash );
print "<p>Last key::$last_key</p>";
foreach($cats_hash as $key => $value){
//  fwrite($handle, $key . " => array( ");
  for($i = 0; $i < count($cats_hash[$key]); $i++ ){
    if($i == 0){
      fwrite($handle, '"' . $key . '" => array('. $cats_hash[$key][$i] . ',' );
    } elseif( $i == count($cats_hash[$key]) - 1 ) {
      fwrite($handle, $cats_hash[$key][$i] . ')' );
    } else {
      fwrite($handle, $cats_hash[$key][$i] . ',' );
    }

  }
  if($key == $last_key){
  } else {
    fwrite($handle, ',');
  }
}
fwrite($handle, '); ?>');
fclose($handle);
?>
