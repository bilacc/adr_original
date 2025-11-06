<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
	
	error_reporting(1);
	require_once 'phpexcel/PHPExcel.php';
	
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objReader->setReadDataOnly(true);

	$objPHPExcel = $objReader->load("elion.xlsx");
	$objWorksheet = $objPHPExcel->getActiveSheet();


	$e1 = 0;
	foreach ($objWorksheet->getRowIterator() as $row)
	{
		$e1++;
		
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false); // dali da uzima i redove koji su prazni, false = ne
		
		$e2 = 0;
		foreach ($cellIterator as $cell)
		{
			$e2++;
			$red[$e1][$e2] = $cell->getValue();
			
			// print $cell->getValue().'<br />';
			
			
		}
		
		// print '-----------------';
			// print '<br />';
	}
	
	$h_hr = fopen("lang_hr.txt", "w+");
	$h_en = fopen("lang_en.txt", "w+");
	
	print count($red);
	
	$err = false;
	$tablica = '';
	foreach($red as $k)
	{
		//print_r($k);
		$aa = array();
		for($i=1; $i<=11; $i++) 																	// broj redova koliko idemo u širinu
		{
			if( substr($k[1],0,1) == '_' )	
			{
				if( $i > 1 )
				{
					if( $i == 2 ) 																// red kojeg upisujemo u file - hrvatski
					{
						$aa[$i] = trim($k[1], ' =').' = '.$k[$i]."\n";
						
						// print $aa[$i];
						fwrite($h_hr, $aa[$i]);
					}
				
					if( $i == 3 ) 																// red kojeg upisujemo u file - engleski
					{
						$aa[$i] = trim($k[1], ' =').' = '.$k[$i]."\n";
						
						// print $aa[$i];
						fwrite($h_en, $aa[$i]);
					}
					
				
				}
			}
			elseif( trim($k[1]) == "" && $i == 1 )
			{
				//print 'razmak <br>';
				fwrite($h_hr, "\n");
				fwrite($h_en, "\n");
			}
		}
		//print $vals.'<br />';
	}
	
	//fclose($h_hr);
	fclose($h_hr);
	fclose($h_en);
	//fclose($h_ru);
?>
</body>
</html>
