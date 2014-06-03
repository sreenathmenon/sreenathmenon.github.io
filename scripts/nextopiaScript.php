<?php
/**
 * @File created by Sreenath for creating the feed for nextopia search. 
 * The product details will be output into a csv file.
 * @author : Sreenath M M
 * @date : May 9, 2014
 * @version :1.0.0
 */


header("Content-type: application/octet-stream");
#header("Content-Disposition: attachment; filename=testFeed.csv");
header("Pragma: no-cache");
header("Expires: 0");

//Defining constants. This is used for accessing the DB
define('BMI_EE_LOCAL', 'localhost');
define('BMI_EE_USER', 'bmi_ee');
define('BMI_EE_PASS', 's51TlbH890');

//Constants for accessing the sync DB
define('BMI_SYNC_LOCAL', 'localhost');
define('BMI_SYNC_USER', 'bmi_ee');
define('BMI_SYNC_PASS', 's51TlbH890');

//Setting FileName
define('CSV_FILE', 'productsInBMISurplus.csv');

$fileatt = "/home/staging.bmisurplus.com/".CSV_FILE;
$fileatt_type = "text/csv; charset=utf-8"; // File Type
$fileatt_name = CSV_FILE;

/* Headers for the csv file */
$cr = "\n";

//Inserting the headers into the csv file
$data = "SKU" . ',' . "Entry Id" . ',' . "Name" . ',' . "Price" . ',' . "Image" . ',' . "URL" . ',' . "Description" .$cr;
$insertData = fopen("/home/staging.bmisurplus.com/".CSV_FILE, "w+");
$fp = fwrite($insertData, $data);
$cr = "\n";

/**
 * Function to connect to the database bmi_ee
 * @author : Sreenath M.M
 * @params : null
 * @return : null
 */
function bmiEE_Connect() {
    $link = mysql_connect(BMI_EE_LOCAL, BMI_EE_USER, BMI_EE_PASS);
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $db_selected = mysql_select_db('bmi_ee', $link);
    if (!$db_selected) {
        die('Can\'t use managec_bsys : ' . mysql_error());
    }
}

/**
 * Function to connect to the database bmi_sync
 * @author : Sreenath M.M
 * @params : null
 * @return : null
 */
function bmiSync_Connect() {
    $link = mysql_connect(BMI_SYNC_LOCAL, BMI_SYNC_USER, BMI_SYNC_PASS);
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $db_selected = mysql_select_db('bmi_sync', $link);
    if (!$db_selected) {
        die('Can\'t use managec_bsys : ' . mysql_error());
    }
}

//Connecting to bmi_ee database
bmiEE_Connect();

//Query to fetch the sku's
$skuQuery  = mysql_query("SELECT field_id_15 AS SKU FROM exp_channel_data WHERE channel_id = 1 ORDER BY sku asc");
while($res = mysql_fetch_array($skuQuery)) {
    $sku = $res['SKU'];
    
    //Query to fetch the product Name, entryId, description, image, product url
    $query = "SELECT exp_channel_data.entry_id AS entry_id, field_id_33 AS Name, field_id_23 AS Price, ";
    $query.= "field_id_32 AS Image, url_title AS URL, field_id_11 AS Description ";
    $query.= "FROM exp_channel_data, exp_channel_titles WHERE exp_channel_data.entry_id = exp_channel_titles.entry_id "; 
    $query.= "AND field_id_15 = '$sku'";
    $output = mysql_query($query) or die(mysql_error());
    $res = mysql_fetch_array($output);
    
    //Fetching the values
    $productName = $res['Name'];
    $entryId = $res['entry_id'];
    
    //There could be comma's in the product name. We are removing the comma's so that it doesn't affect while generating the csv file.
    $productName = str_replace(',', '|', $productName);
    $productPrice = $res['Price'];
    
    //Rounding off the price to 2 digits
    $productPrice = round($productPrice, 2);
    
    //Appending the first part to get the product Image and product url
    $productImage = "http://www.bmisurplus.com/uploads/product_images/".$res['Image'];
    $productUrl =  "http://www.bmisurplus.com/products/".$res['URL'];
    $productDesc = $res['Description'];
    
    //Replaces comma's in th description
    $productDesc = str_replace(',', '|', $productDesc);
    
    //Removing html tags from the description section
    $productDesc = strip_tags($productDesc);
    
    //If there is no description, then we can use the name itself as the description
    if ($productDesc == "") {
        $productDesc = $productName;
    }
    print "Inserting SKU -".$sku.'<br />';
    $cr = "\n";
    
    //Inserting the values into the csv file
    $insertValues = $sku . ',' . $entryId . ',' . $productName . ',' . $productPrice . ',' . $productImage . ',' . $productUrl . ',' . $productDesc .$cr;
    $insertData = fopen("/home/staging.bmisurplus.com/".CSV_FILE, "a");
    $fp = fwrite($insertData, $insertValues);
    $cr = "\n";   
}
?>
