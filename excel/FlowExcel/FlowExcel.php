<?php
class FlowExcel
{
	private $create = false;
	private $options = Array('row' => 0, 'col' => 0, 'rown' => 0, 'coln' => 0);
	private $file_string = '/xml/xl/sharedStrings.xml';
	private $file_table = '/xml/xl/worksheets/sheet1.xml';

	public function setStatus($json='')
	{
		$this->options = json_decode($json, true);
	}

	public function getStatus()
	{
		return json_encode($this->options, true);
	}

	public function getChar($n=0)
	{
		if ( $n > 26) {
			$str = '';
			while ($n > 26) {
				$a = ( $n - 1 ) % 26 + 1;
				$n = floor( ( $n - 1 ) / 26);
				$str = chr( $a + 64 ) . $str;
			}
			$str = chr( $n + 64 ) . $str;
			return $str;
		} else {
			return chr( $n + 64 );
		}
	}

	public function openFlow()
	{
		// Удаление старых файлов
		if ( file_exists( __DIR__ . $this->file_string ) ) unlink( __DIR__ . $this->file_string );
		if ( file_exists( __DIR__ . $this->file_table ) ) unlink( __DIR__ . $this->file_table );

		// Создание и запись новых файлов
		$f_string = fopen( __DIR__ . $this->file_string, 'a');
		fwrite($f_string, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n") or die('ERROR-ACCESS');
		fwrite($f_string, '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . ($this->options['coln'] * $this->options['rown']) . '" uniqueCount="' . ($this->options['coln'] * $this->options['rown']) . '">') or die('ERROR-ACCESS');
		fclose($f_string);

		$f_table = fopen( __DIR__ . $this->file_table, 'a');
		fwrite($f_table, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n") or die('ERROR-ACCESS');
		fwrite($f_table, '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mx="http://schemas.microsoft.com/office/mac/excel/2008/main" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:mv="urn:schemas-microsoft-com:mac:vml" xmlns:x14="http://schemas.microsoft.com/office/spreadsheetml/2009/9/main" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac" xmlns:xm="http://schemas.microsoft.com/office/excel/2006/main"><sheetViews><sheetView workbookViewId="0"/></sheetViews><sheetFormatPr customHeight="1" defaultColWidth="14.43" defaultRowHeight="15.75"/><sheetData>') or die('ERROR-ACCESS');
		fclose($f_table);
	}

	public function addRow($string=Array(), $style=2)
	{
		// COLS
		if ( file_exists( __DIR__ . $this->file_string ) ) {
			$f_string = fopen( __DIR__ . $this->file_string, 'a');
			$str = '';
			foreach($string as $value) {
				$str .= '<si><t>' . htmlspecialchars($value) . '</t></si>';
			}
			fwrite($f_string, $str) or die('ERROR-ACCESS');
			fclose($f_string);
		}
		// ROWS
		if ( file_exists( __DIR__ . $this->file_table ) ) {
			$f_table = fopen( __DIR__ . $this->file_table, 'a');
			$str = '<row r="' . $this->options['row'] . '">';
			for ($i=0; $i<$this->options['coln']; $i++) {
				$str .= '<c r="' . $this->getChar($i+1) . $this->options['row'] . '" s="' . $style . '" t="s"><v>' . $this->options['col'] . '</v></c>';
				$this->options['col']++;
			}
			$str .= '</row>' . "\r\n";
			$this->options['row']++;
			fwrite($f_table, $str) or die('ERROR-ACCESS');
			fclose($f_table);
		}
	}

	public function closeFlow()
	{
		$f_string = fopen( __DIR__ . $this->file_string, 'a');
		fwrite($f_string, '</sst>') or die('ERROR-ACCESS');
		fclose($f_string);
		$f_table = fopen( __DIR__ . $this->file_table, 'a');
		fwrite($f_table, '</sheetData><drawing r:id="rId1"/></worksheet>') or die('ERROR-ACCESS');
		fclose($f_table);
	}

	public function createExcel($dir)
	{
		require_once 'pclzip.lib.php';
		if ( file_exists( __DIR__ . '/Excel.xlsx.zip' ) ) unlink( __DIR__ . '/Excel.xlsx.zip');
		$zip = new PclZip( __DIR__ . '/Excel.xlsx.zip' );
		$this->addFiles($zip, __DIR__ . '/xml', __DIR__ . '/xml' );
		if ( file_exists( __DIR__ . '/Excel.xlsx.zip' ) ) copy( __DIR__ . '/Excel.xlsx.zip', $dir );
		// Удаление старых файлов
		if ( file_exists( __DIR__ . $this->file_string ) ) unlink( __DIR__ . $this->file_string );
		if ( file_exists( __DIR__ . $this->file_table ) ) unlink( __DIR__ . $this->file_table );
		if ( file_exists( __DIR__ . '/Excel.xlsx.zip' ) ) unlink( __DIR__ . '/Excel.xlsx.zip');
	}

	private function addFiles(&$zip, $path, $dir)
	{
		foreach (scandir($path) as $key => $value) {
			if ($value == '.' or $value == '..') continue;
			if ( is_dir($path . '/' . $value) ) {
				$this->addFiles($zip, $path . '/' . $value, $dir);
			} else {
				if ( $this->create ) {
					$e = $zip->add( $path . '/' . $value, PCLZIP_OPT_REMOVE_PATH, $dir);
				} else {
					$e = $zip->create( $path . '/' . $value, PCLZIP_OPT_REMOVE_PATH, $dir);
					$this->create = true;
				}
				if ($e == 0) die('ERROR-ZIP');
			}
		}
	}
}
?>
