<?php 

/** Error reporting */
error_reporting(0);

//include_once('functions.php');

/** PHPExcel */
require_once 'phpexcel/PHPExcel.php';



// -- čitamo prijevode iz fajla
$prijevodi = array();
$handle = @fopen("lang_hr.txt", "r");
if ($handle)
{
	$i=0;
	while(!feof($handle))
	{
		$i++;
		$buffer = fgets($handle, 4096);
		
		$buffer=trim($buffer);
		if($buffer != '')
		{
			list($const, $val) = explode('=', $buffer);
			$const = trim($const);
			$val = trim($val);
			
			$prijevodi[$i]['const'] = $const;
			$prijevodi[$i]['val'] = $val;
		}
		else
		{
			$prijevodi[$i]['const'] = '';
			$prijevodi[$i]['val'] = '';
		}
	}
	fclose($handle);
}

$prijevodi_en = array();
$handle = @fopen("lang_en.txt", "r");
if ($handle)
{
	$i=0;
	while(!feof($handle))
	{
		$i++;
		$buffer = fgets($handle, 4096);
		
		$buffer=trim($buffer);
		if($buffer != '')
		{
			list($const, $val) = explode('=', $buffer);
			$const = trim($const);
			$val = trim($val);
			
			$prijevodi_en[$i]['const'] = $const;
			$prijevodi_en[$i]['val'] = $val;
		}
		else
		{
			$prijevodi_en[$i]['const'] = '';
			$prijevodi_en[$i]['val'] = '';
		}
	}
	fclose($handle);
}









$i=0;
foreach (range('A', 'Z') as $char) {
  $slova[++$i] = $char;
}

$brojevi = $slova;

$i=26;
foreach($slova as $k1 => $v1)
{
	foreach($slova as $k2 => $v2)
	{
		$brojevi[++$i] = $v1.$v2;
	}
}

// -- dio koji upisuje podatke

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Virtus-dizajn.com")
							 ->setLastModifiedBy("virtus")
							 ->setTitle("Office XLS Document")
							 ->setSubject("Office XLS Document")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


/* $styleThickBrownBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => 'FF303030'),
			),
		),
	);
$objPHPExcel->getActiveSheet()->getStyle($brojevi[$row].'2')->applyFromArray($styleThickBrownBorderOutline); */

// Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'NE MIJENJATI !!!');
$objPHPExcel->getActiveSheet()->setCellValue('A4', 'NE BRISATI RED !!!');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'HRVATSKI'); 		//jezici
$objPHPExcel->getActiveSheet()->setCellValue('C3', 'ENGLISH');

$row = 6;
foreach($prijevodi as $k)
{
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $k['const']);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $k['val']);
	
	$row++;
}

$row = 6;
foreach($prijevodi_en as $k)
{
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $k['val']);
	
	$row++;
}


// Set column widths
//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);


// -- ovo postavimo tek tolko da nam bude to selektiorano kad otvorimo excel file
$objPHPExcel->getActiveSheet()->setCellValue('A1', date('d.m.Y'));
$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray(
	array(
		'font' => array(
			'bold' => true,
			'size' => 10
		)
	)
);
// -- --



// Set text orientation
//$objPHPExcel->getActiveSheet()->getStyle('M3')->getAlignment()->setTextRotation(90);


// Set page orientation and size
//$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('lng export');


// -- --

/** PHPExcel_IOFactory */
require_once 'phpexcel/PHPExcel/IOFactory.php';


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Salvia_export.xls"');		//ime exel filea
header('Cache-Control: max-age=0');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
$objWriter->save('php://output');
?>