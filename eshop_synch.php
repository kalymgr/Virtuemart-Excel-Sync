<?php


//synchronized with Subversion

/**
 * Created by JetBrains PhpStorm.
 * User: Michalis Tsougranis
 * Date: 6/6/2012
 * Time: 11:36 πμ
 * To change this template use File | Settings | File Templates.
 */

include 'DbUtilities/DbConnection.php';
include 'DbUtilities/mysql_DbConnection.php';
include 'Eshop/customer.php';
require_once dirname(__FILE__) . '/phpexcel/phpexcel1_8_0/Classes/PHPExcel.php';


class eshop_synch{
    //stoixeia prosvasis Virtuemart
    private $vm_dbservername='***';
    private $vm_dbusername='***';
    private $vm_dbpassword='***';
    private $vm_database='***';
    private $mysql_dblink;
    private $mysql_dbcon;

    function __construct()
    {//arxikopoiei to systima sygxronismou ton vaseon


        //gia ti MYSQL - Virtuemart
        $this->mysql_dbcon= new mysql_DbConnection();
        $this->mysql_dblink=$this->mysql_dbcon->set_mySQL_Connection($this->vm_dbservername, $this->vm_dbusername,$this->vm_dbpassword, $this->vm_database);


        //$this->set_vat_table(); //dimourgia tou pinaka me ta vat



    }

    function update_diathesimotita($product_sku,$diathesimi_posotita)
    {//synartisi pou enimeronei ti diathesimotita ton proionton tou softone me paromoia SKU
            //enimerose afto i tis parallages tou me ti diathesimi posotita
            $sqlquery2="update xz4j3_virtuemart_products set product_in_stock=".$diathesimi_posotita." where product_sku=".$product_sku." or instr(product_sku,concat('".$product_sku."','-'))=1";//pros to paron, ginetai gia dokimastiko proion
            mysql_query($sqlquery2,$this->mysql_dblink);

        }

    function  update_shoppergroup_price($product_sku, $kodikos_shoppergroup,$eshop_price,$price)
    { //Enimeronei tis times eshop kai katastimatos enos proiontos me vasi to product_id
        //enimerose ton pinaka me tis times, ana shoppergroup
       // echo 'Mpike sto shopper group price';
        $product_ids=$this->get_virtuemart_product_ids($product_sku);

        if ($product_ids<>-1){

            foreach ($product_ids as $kodikos_proiontos) { //gia kathe ena apo ta proionta pou antistoixoun sto sygkekrimeno SKU
                //elegxos an yparxoun idi kataxorimenes times gia to sygkekrimeno proion
                $sqlquery3 = "select * from xz4j3_virtuemart_product_prices where virtuemart_product_id= $kodikos_proiontos and virtuemart_shoppergroup_id=$kodikos_shoppergroup";
                $result3 = mysql_query($sqlquery3, $this->mysql_dblink);

                //trexousa imerominia
                $today = new DateTime();
                //$date_modified_timestamp=$today->getTimestamp();
                //$current_date=date_format($date_modified_timestamp,'Y-m-d H:i:s');
                //echo "Timestamp: $date_modified_timestamp";
                $date = new DateTime();
                $date_modified_timestamp = $date->format('Y-m-d H:i:s');


                if (mysql_num_rows($result3))//an to sygkekrimeno product exei idi timi gia to sygkekrimeno shopper group, apla enimerose ton pinaka
                {
                    echo "Allagi timis...";
                    //echo "Idi exei kathoristei timi gia to proion, gia to sygkekrimeno shopper group";
                    $row3 = mysql_fetch_assoc($result3);
                    $price_id = $row3['virtuemart_product_price_id'];
                    //echo "Price id: ".$price_id.". Nea timi proiontos: ".$price."\n";

                    $sqlquery4 = "update xz4j3_virtuemart_product_prices set product_override_price=$eshop_price,product_price=$price,";
                    $sqlquery4 .= "modified_on= '$date_modified_timestamp' where virtuemart_product_price_id=$price_id";
                    mysql_query($sqlquery4, $this->mysql_dblink);

                    // echo "Vrethike i timi tou shopper group";
                } else     //an den exei oristei timi gia to sygkekrimeno shopper group, prosthese tin
                {
                    echo "Prosthiki neas timis";
                    // echo "product id: $kodikos_proiontos";
                    $sqlquery4 = "insert into xz4j3_virtuemart_product_prices(virtuemart_product_id,product_price,product_override_price,";
                    $sqlquery4 .= "override,product_currency,created_on,modified_on,virtuemart_shoppergroup_id,price_quantity_start,price_quantity_end,";
                    $sqlquery4 .= "product_tax_id,product_discount_id,product_price_publish_up,product_price_publish_down, created_by,modified_by) ";
                    $sqlquery4 .= "values(" . $kodikos_proiontos . "," . $price . "," . $eshop_price . ",1,47,";
                    $sqlquery4 .= "'" . $date_modified_timestamp . "','" . $date_modified_timestamp . "'," . $kodikos_shoppergroup . ",0,0,0,0,0,0,397,397)";

                    if (true == mysql_query($sqlquery4, $this->mysql_dblink))
                        echo "\nI nea timi prostethike gia ton proion " . $kodikos_proiontos;
                    else
                        echo "\nProblima";
                }

            }



        }


    }

    function get_virtuemart_product_ids($product_sku){
        //tha allaksei oste na epistrefei pinaka me ola ta product_ids pou antistoixoun sto sygkekrimeno SKU i stis paralagges tou p.x. 77777-1, 77777-2
        $sqlquery="SELECT virtuemart_product_id FROM captain_virtuemart.xz4j3_virtuemart_products where product_sku like '".$product_sku."' or instr(product_sku,concat('".$product_sku."','-'))=1";
        $result=mysql_query($sqlquery,$this->mysql_dblink);

        if (mysql_num_rows($result)){
           $i=0;
            while ($row = mysql_fetch_array($result)){
                $product_ids[$i]=$row[0];
                $i++;
            }

            return $product_ids;
        }
        //$numberofrows=mysql_num_rows($result);
        else
            return -1;

    }

    function update_products($excelfilename){
        //Synartisi pou tha kalei tis antistoixes synartiseis gia ti diathesimotita kai tis times kai tha enimeronei ta proionta tou virtuemart
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);

        $objPHPExcel = $objReader->load($excelfilename);
        $objWorksheet = $objPHPExcel->getActiveSheet();

        foreach ($objWorksheet->getRowIterator() as $row) {


            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
            // even if it is not set.
            // By default, only cells
            // that are set will be
            // iterated.
            $i=1;
            foreach ($cellIterator as $cell) { //for each cell of the row
                if ($i==1) {
                    echo "Kodikos " . $cell->getValue() . "-";
                    $sku = $cell->getValue();
                }

                elseif ($i==2)
                    //echo  "Timi katastimatos ".$cell->getValue() . "-";
                    $timi_katastimatos=$cell->getValue();
                elseif ($i==3)
                    //echo  "Timi eshop ".$cell->getValue() . "-";
                    $timi_eshop=$cell->getValue();
                else {
                    //echo "Diathesimotita ".$cell->getValue() . "-";
                    $diathesimotita = $cell->getValue();
                    $this->update_shoppergroup_price($sku, 0, $timi_eshop, $timi_katastimatos);
                    $this->update_diathesimotita($sku, $diathesimotita);
                }
                $i=$i+1;
            }

            echo  "\n";
        }
    }

    function close_connection(){
        //Synartisi pou kleinei ti syndesi me ti vasi dedomenon
        $this->mysql_dbcon->closeConnection($this->mysql_dblink);
    }

}

?>