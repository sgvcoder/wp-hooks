<?php

error_reporting(E_ALL);
ini_set("display_errors", "On");

/**
 * Description of newPHPClass
 *
 * @author sgv
 */
class newPHPClass {

    public function exportFile() {

        $params = array();
        $data = $this->exportXLSX($params);
        echo json_encode($data);
        exit;
    }

    public function exportXLSX($params) {

        require_once 'library/FlowExcel/FlowExcel.php';

        $FlowExcel = new FlowExcel();
        $file_name = new DateTime();
        $is_first_record = true;
        if (isset($_POST['name'])) {

            $file_name = $_POST['name'];
            $is_first_record = false;
        } else {

            $file_name = $file_name->format('d-m-Y_H-i-s') . '.xlsx';
        }

        $_columns = array(
            array("type" => "field1", "name" => "name1"),
            array("type" => "field2", "name" => "name2"),
            array("type" => "field3", "name" => "name3")
        );

        $fields = array(
            "field1" => array("name1" => "val1"),
            "field2" => array("name2" => "val2"),
            "field3" => array("name3" => "val3")
        );

        if ($is_first_record == true) {

            $FlowExcel->setStatus('{"row":1,"col":0,"rown":10,"coln":' . count($_columns) . '}');
            $FlowExcel->openFlow();
            $line_xlsx = Array();
            for ($i = 0, $n = count($_columns); $i < $n; $i++) {
                $type = $_columns[$i]['type'];
                $name = $_columns[$i]['name'];
                if (isset($fields[$type][$name])) {
                    $line_xlsx[] = $fields[$type][$name];
                }
            }
            $FlowExcel->addRow($line_xlsx, 1);
        } else {
            $FlowExcel->setStatus($_POST['excel']);
        }
        if (isset($_POST['limit'])) {
            $limit = $_POST['limit'];
        } else {
            $limit = '0,2';
        }

        if (isset($_POST['count'])) {
            $total_count = $_POST['count'];
        } else {
            $total_count = count($fields);
        }

        $table = array(
            array("field1" => "val1", "field2" => "val11", "field3" => "val111"),
            array("field1" => "val2", "field2" => "val22", "field3" => "val333"),
            array("field1" => "val3", "field2" => "val33", "field3" => "val222")
        );

        for ($r = 0, $x = count($table); $r < $x; $r++) {
            $line_xlsx = Array();
            for ($i = 0, $n = count($_columns); $i < $n; $i++) {
                $type = $_columns[$i]['type'];
                $name = $_columns[$i]['name'];
                if (isset($table[$r][$type])) {
                    $line_xlsx[] = $table[$r][$type];
                } else {
                    $line_xlsx[] = '';
                }
            }
            $FlowExcel->addRow($line_xlsx);
        }

        $json = Array();
        $json['count'] = (int) $total_count;
        $json['name'] = $file_name;
        $json['limit'] = $limit;
        $json['excel'] = $FlowExcel->getStatus();
        $lm = explode(',', $limit);

        if (((int) $lm[0] + (int) $lm[1]) >= $total_count) {
            $FlowExcel->closeFlow();
            touch(__DIR__ . "/tmp/" . $file_name);
            chmod(__DIR__ . "/tmp/" . $file_name, 0777);
            $FlowExcel->createExcel(__DIR__ . "/tmp/" . $file_name);
            $json['href'] = __DIR__ .  '/tmp/' . $file_name;
        }
        return $json;
    }

}

$c = new newPHPClass();
$c->exportFile();