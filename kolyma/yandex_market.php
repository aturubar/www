<?php /*
 * The Query Method
 * Anti-Pattern
 */
  require_once('./configuration.php');
  $Jconf = new JConfig;
  $sql_hostname = $Jconf->host;
  $sql_username = $Jconf->user;
  $sql_password = $Jconf->password;
  $sql_database = $Jconf->db;
  $site_url = $Jconf->live_site;


try {
    $conn = new PDO('mysql:host=localhost;dbname=myDatabase', $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = $conn->query('SELECT * FROM myTable WHERE name = ' . $conn->quote($name));

    foreach($data as $row) {
        print_r($row);
    }
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

$cat_table_name = "";
$items_table_name = "";
$cat_query = "SELECT * FROM " . $cat_table_name;
$items_query = "SELECT " . $item_attrs . " FROM " . $items_table_name;


function insTagwithVal($tagname, $value, $tagAttr = "") {
  if($tagAttr != "") {
echo "<$tagname $tagAttr>$value</$tagname>";
}
else {
    echo "<$tagname>" . $value . "</$tagname>";
}
//echo '<br>';
}

?>
