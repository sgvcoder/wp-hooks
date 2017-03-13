<?php

class Pdf_Html_lib {

    private $uploadPath = '';

    function __construct() {
        
        ini_set('memory_limit', "128M");
        define('DOMPDF_ENABLE_REMOTE', true);
        //define('DOMPDF_DPI', 300);
        define('DOMPDF_DPI', 150);
    }

    public function create() {

        // include lib
        require_once(__DIR__ . '/vendor/dompdf/autoload.inc.php');

        $style = '<style>'
                . 'table td {'
                . 'vertical-align: top;'
                . '}'
                . '</style>';

        $html = '<table>'
                . '<tr>'
                . '<td>1 xxxxxx xxxxxxx xx xxxx xxxxxx xxxxxx xxxxx</td>'
                . '<td>2 xxxxxxxxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>3 xxxxxx xxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>4 xxxxxx xxxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>5 xxxxxx xxxxx xx xxxx xxxxxx xxxx xxxxx</td>'
                . '<td>6 xxxxxx xxxxxxx xx xxxx xxxxxx xxxxxx xxxxx</td>'
                . '<td>7 xxxxxxxxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>8 xxxxxx xxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>9 xxxxxx xxxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>10 xxxxxx xxxxx xx xxxx xxxxxx xxxx xxxxx</td>'
                . '</tr>'
                . '<tr>'
                . '<td>1 xxxxxx xxxxxxx xx xxxx xxxxxx xxxxxx xxxxx</td>'
                . '<td>2 xxxxxxxxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>3 xxxxxx xxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>4 xxxxxx xxxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>5 xxxxxx xxxxx xx xxxx xxxxxx xxxx xxxxx</td>'
                . '<td>6 xxxxxx xxxxxxx xx xxxx xxxxxx xxxxxx xxxxx</td>'
                . '<td>7 xxxxxxxxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>8 xxxxxx xxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>9 xxxxxx xxxxxxx xx xxxx xxxxxx xxxxx xxxxx</td>'
                . '<td>10 xxxxxx xxxxx xx xxxx xxxxxx xxxx xxxxx</td>'
                . '</tr>'
                . '</table>';

        // instantiate and use the dompdf class
        $dompdf = new Dompdf\Dompdf();
        $dompdf->set_paper("A4", "portrait");
        $dompdf->load_html($style . $html);
        $dompdf->render();
        $dompdf->stream("hello.pdf");
//        $output = $dompdf->output();
//        file_put_contents($this->uploadPath . 'form.pdf', $output);
    }

}

$pdf = new Pdf_Html_lib();
$pdf->create();
