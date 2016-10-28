<?php 

error_reporting(E_ALL);
ini_set("display_errors", "On");


echo "Including FlowExcel\n";
require_once('./FlowExcel/FlowExcel.php');

echo "Create FlowExcel object\n";
$FlowExcel = new FlowExcel();

echo "Set status\n";
$FlowExcel->setStatus('{"row":1,"col":0,"rown":2,"coln":10}');

echo "Call openFlow\n";
$FlowExcel->openFlow();

echo "Set data\n";
$FlowExcel->addRow(array("_ta_", "_tb_", "_tc_", "_td_", "_te_", "_taa_", "_tab_", "_tac_", "_tad_", "_tae_"), 1);
$FlowExcel->addRow(array("_a1_", "_b1_", "_c1_", "_d1_", "_e1_", "_aa_", "_ab_", "_ac_", "_ad_", "_ae_"));
$FlowExcel->addRow(array("_a2_", "_b2_", "_c2_", "_d2_", "_e2_", "_aa_", "_ab_", "_ac_", "_ad_", "_ae_"));
$FlowExcel->addRow(array("_a3_", "_b3_", "_c3_", "_d3_", "_e3_", "_aa_", "_ab_", "_ac_", "_ad_", "_ae_"));

$dt = new DateTime();
$file_name = $dt->format('d-m-Y_H-i-s') . '.xlsx';
$FlowExcel->closeFlow();
$FlowExcel->createExcel(__DIR__ . "/tmp/" . $file_name);
echo "File created: " . __DIR__ . "/tmp/" . $file_name . "\n";