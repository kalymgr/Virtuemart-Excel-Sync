<?php
/**
 * Created by PhpStorm.
 * User: mtsougranis
 * Date: 27/4/2016
 * Time: 9:08 πμ
 */
require_once dirname(__FILE__) . '/phpexcel1_8_0/Classes/PHPExcel.php';

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);

$objPHPExcel = $objReader->load("test.xlsx");
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
        if ($i==1)
            echo  "Kodikos ".$cell->getValue() . "-";
        elseif ($i==2)
            echo  "Timi katastimatos ".$cell->getValue() . "-";
        elseif ($i==3)
            echo  "Timi eshop ".$cell->getValue() . "-";
        else
            echo "Diathesimotita ".$cell->getValue() . "-";
        $i=$i+1;
    }

    echo  "\n";
}



// Get cell A2
//echo $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 2)->getValue();



