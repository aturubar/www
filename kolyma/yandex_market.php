

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

/*try {
    $conn = new PDO('mysql:host=' . $sql_hostname . ';dbname=' .
$sql_database, $sql_username, $sql_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = $conn->query('SELECT * FROM myTable WHERE name = ' .
$conn->quote($name));

    foreach($data as $row) {
        print_r($row);
    }
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}*/

$items_table_name = $Jconf->dbprefix . "virtuemart_products_ru_ru";
$items_to_cats_table_name = $Jconf->dbprefix . "virtuemart_product_categories";
$item_price_table = $Jconf->dbprefix . "virtuemart_product_prices";
$cat_table_name = $Jconf->dbprefix . "virtuemart_categories_ru_ru";
$cat_attrs = "virtuemart_category_id,category_name";
$item_attrs = "*";
$cats_query = "SELECT $cat_attrs FROM $cat_table_name";
$items_query = "SELECT $item_attrs FROM $items_table_name";
$today = date('Y-m-d H:i');
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
echo '<yml_catalog date = "' . $today . '">';
echo '<shop>';

insTagwithVal("name", "Sportbasic");
insTagwithVal("company","Sportbasic");
insTagwithVal("url", $Jconf->sitename);
insTagwithVal("platform","Joomla");
insTagwithVal("version","2.5");
insTagwithVal("email","circa666@list.ru");
echo '<currencies>';
insTagwithVal("currency","", "id=\"RUR\" rate=\"1\"");

echo '</currencies>';
echo '<categories>';

$conn = new PDO('mysql:host=' . $sql_hostname . ';dbname=' .
$sql_database, $sql_username, $sql_password);
//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $cats = $conn->query( $cats_query );

    foreach($cats as $row) {
        insTagwithVal("category", $row['category_name'], 'id="' .
$row['virtuemart_category_id'] . '"');
    }
echo '</categories>';
echo '<cpa>1</cpa>';
$offer_id_prefix = "SB";
$items = $conn->query( $items_query );
foreach( $items as $row ){
$item_id = $row['virtuemart_product_id'];
echo '<offer id="'.$offer_id_prefix. '-' . $item_id . '">';
$item_cat = $conn->query("SELECT * FROM $cat_table_name as
c,$items_to_cats_table_name as cp WHERE
cp.virtuemart_product_id=$item_id and
c.virtuemart_category_id=cp.virtuemart_category_id" );
foreach($item_cat as $cat){
$cur_item_cat = $cat['slug'];
$cur_cat_id = $cat['virtuemart_category_id'];
}
$price_query = $conn->query("SELECT product_price FROM
$item_price_table WHERE virtuemart_product_id=$item_id");
foreach($price_query as $item){
$price = $item['product_price'];
}
$url = $Jconf->sitename . $cur_item_cat . "/" . $row['slug'] . '-detail';
insTagwithVal("url", $url);
insTagwithVal("price", $price);
insTagwithVal("currencyID", "RUR");
insTagwithVal("categoryID", $cur_cat_id);
insTagwithVal("name", $row['product_name']);
//echo $category_name;
echo '</offer>';
}
echo '</shop>';
echo '</yml_catalog>';


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
