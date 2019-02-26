<?php
//ini_set('MAX_EXECUTION_TIME', -1);//set unlimited execution time
//set_time_limit(1200);
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_TIMEOUT, 0);

set_time_limit(100000); //megalosa to orio tou timeout gia to script, gia na ginei sosta o sygxronismos
include 'eshop_synch.php';

//update ton stoixeion tou ilektronikou katastimatos
$sync= new eshop_synch();
//$sync->update_product();

/*
$kodikos_proiontos= $sync->get_virtuemart_product_id("90.0408");
$eshop_price=500;
$price=510;
$sync->update_shoppergroup_price($kodikos_proiontos,0,$eshop_price,$price);

*/

//$sync->update_diathesimotita("90.0408",20);

$time_start = microtime(true); //enarksi xronometrou

$sync->update_products("test.xlsx");

$sync->close_connection();
echo "\nH diadikasia oloklirothike";

$time_end = microtime(true); //liksi xronometrou
$execution_time = ($time_end - $time_start)/60;
echo "Synolikos xronos ektelesis: ".$execution_time;


/**
 * Created by JetBrains PhpStorm.
 * User: Michalis Tsougranis
 * Date: 6/6/2012
 * Time: 12:02 μμ
 * To change this template use File | Settings | File Templates.
 */



?>