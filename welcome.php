<?php
/**********SETUP***********/
require 'soap_class.php';
session_start();
$oSoap = new SOAP;	
/*********INPUT***********/
isset($_SESSION['id']) ? $_SESSION['id']            : header('Location:/portal_website');
$sCommand = isset($_POST['command']) ? $_POST['command']: NULL;

/*********OPERATION*******/
if($sCommand =='Logout'){
	$oSoap->logout_to_portal();	
	header('Location:/portal_website');
}


	$sTodayDate = date("Y-m-d");
	$oTable = '';
		
	$aRegionResponse = $oSoap->oClient->getAdvertRegions($_SESSION['id']);

	$aAdvertResponse1 = array();
	
	foreach($aRegionResponse as $oRegionResponse){
		$aAdvertResponse1[$oRegionResponse->id] = $oRegionResponse->label;	
	}
	
	
	$aRegionFilterGP = array('Gauteng' => 
							array('field' => 'region', 'operator' => '=', 'value' => array_search('Gauteng', $aAdvertResponse1))); 
	
	
	$aRegionFilterMP = array('Mpumalanga' => 
							array('field' => 'region', 'operator' => '=', 'value' => array_search('Mpumalanga', $aAdvertResponse1))); 
							
	 
	$aRegionFilterCT = array('Western Cape' => 
							array('field' => 'region', 'operator' => '=', 'value' => array_search('Western Cape', $aAdvertResponse1)));					


	$aAdvertResponseGP = $oSoap->oClient->getAdverts($_SESSION['id'], $aRegionFilterGP);
	$aAdvertResponseMP = $oSoap->oClient->getAdverts($_SESSION['id'], $aRegionFilterMP);
	$aAdvertResponseCT = $oSoap->oClient->getAdverts($_SESSION['id'], $aRegionFilterCT);
	
	$arr = array();
	$arr [] = $aAdvertResponseGP;
	$arr [] = $aAdvertResponseMP;
	$arr [] = $aAdvertResponseCT;
	
	
	
	$table = array();
	$columnHeaders = array('','Mpumalanga');

	foreach ($arr as $aCandidates) {
		foreach($aCandidates as $candidate){	
		  $date = ceil(abs(strtotime($candidate->start_date) - strtotime($sTodayDate)) / 60 / 60 / 24 / 7);	  
		
		  if (!isset($table[$date])) {
			$table[$date] = array();
			//sort($table);
			
		  }
		  if (!isset($table[$date][$candidate->region])) {
			$table[$date][$candidate->region] = 0;
		  }
		  $table [$date][$candidate->region] += 1;
		  if (!in_array($candidate->region, $columnHeaders)) {
			$columnHeaders[] = $candidate->region;
		  }
		}
		//var_dump($table);
	}
	
	$oTable .= '<table border=1><tr><td colspan=2>';
	$oTable .=implode('</td><td>', $columnHeaders);

	$oTable .='</td></tr>';
	foreach($table as $week => $row) {
		
	  $oTable .= '<tr><td>Week # ' . $week . '</td>';
	  foreach($columnHeaders as $location) {
		$oTable .= '<td>';
		$oTable .= isset($row[$location])?$row[$location]: NULL;
		$oTable .= '</td>';
	  }
	  $oTable .= '</tr>';
	}
	




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome Page</title>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
	
	function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Week", "Advert", { role: "style" } ],
		["1", 3, "blue"],
        ["2", 0, "red"],
        ["3", 1, "yellow"],
       
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Advertisement statistics by region",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
		ticks: [0, .3, .6, .9, 1],

      };
	  
		var chart = new google.visualization.ColumnChart(document.getElementById('piechart'));
		chart.draw(data, options);	
	}
</script>
</head>

<body>
	<div class="container">
    	<div class="row">
        
        	<div class="navbar-header pull-right">
                <form action="" method="post">
                	<button class="btn btn-danger" type="submit" name="command" value="Logout">LOGOUT &emsp;<i class="glyphicon glyphicon-log-out"></i></button>
                </form>
                <!--<a class="pull-left" href="index.php"><img class="navbar-brand" src="images/DPS-logo.png" alt="logo" /></a>-->
             </div><!--End navbar-header-->
        
        
            <h1>Welcome to Portal Website</h1>
            <?php //var_dump($GLOBALS); ?>
            
            <div class="breadcrumb">
            	<?php 
					
					echo $oTable; 
					
					if(isset($_POST['btnSubmit'])){
						header("Conten-Type: application/xls");
						header("Content-Disposition:attachment, filename=download.xls"); 
					}
				
				?>
       
               
            </div>
            
            <div class="form-control">
                <form action="" method="post">
   					<input type="submit" value="Download excel file" name="btnSubmit"/>
   				</form>
            </div><!--Form-control-->
            
            <div class="breadcrumb" id="piechart">
            
            </div>
    	
        </div><!--End row-->
    </div><!--End container-->
</body>
</html>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>