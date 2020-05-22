<?php

$username = "admin";
$password = "admin";
$dbname = "KLTN";

// Create connection
$conn = new mysqli('',$username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST['ok2'])){
$day2= $_POST['subject2'];
} 
//Calculate the day of the week for chart 2
$today=date("Y-m-d");
$date = new DateTime($day2);

$dayOfWeek = date("w", strtotime($day2));
$gettime=($date->getTimestamp())*1000;
$sunDayTime = $gettime - ($dayOfWeek + 1)  * 86400000;
$days = array();
for ($i = 1; $i <=7; $i++){
	$dayMiliSecond = $sunDayTime + $i*86400000;
	$seconds = $dayMiliSecond / 1000;
	$dateTemp =date("Y-m-d", $seconds);
	$temp=$dateTemp;
	array_push($days, $temp);	
}

//get data from database for chart 2
$sql1 = "SELECT Time, Cars_count FROM data WHERE Date='"."".$days[0]."'";
$result1 = $conn->query($sql1);
$dataPoints1 = array();
if ($result1->num_rows > 0) {
	while($row1 = mysqli_fetch_row($result1)) {
	  
	    $dataPoints1[] = array("x" => strtotime($row1[0])*1000, "y" => $row1[1]); 
	}
}
//---
$sql2 = "SELECT Time, Cars_count FROM data WHERE Date='"."".$days[1]."'";
$result2 = $conn->query($sql2);
$dataPoints2 = array();
if ($result2->num_rows > 0) {
	while($row2 = mysqli_fetch_row($result2)) {
	  
	    $dataPoints2[] = array("x" => strtotime($row2[0])*1000, "y" => $row2[1]); 
	}
}
//---
$sql3 = "SELECT Time, Cars_count FROM data WHERE Date='"."".$days[2]."'";
$result3 = $conn->query($sql3);
$dataPoints3 = array();
if ($result3->num_rows > 0) {
	while($row3 = mysqli_fetch_row($result3)) {
	  
	    $dataPoints3[] = array("x" => strtotime($row3[0])*1000, "y" => $row3[1]); 
	}
}
//---
$sql4 = "SELECT Time, Cars_count FROM data WHERE Date='"."".$days[3]."'";
$result4 = $conn->query($sql4);
$dataPoints4 = array();
if ($result4->num_rows > 0) {
	while($row4 = mysqli_fetch_row($result4)) {
	  
	    $dataPoints4[] = array("x" => strtotime($row4[0])*1000, "y" => $row4[1]); 
	}
}
//---
$sql5 = "SELECT Time, Cars_count FROM data WHERE Date='"."".$days[4]."'";
$result5 = $conn->query($sql5);
$dataPoints5 = array();
if ($result5->num_rows > 0) {
	while($row5 = mysqli_fetch_row($result5)) {
	  
	    $dataPoints5[] = array("x" => strtotime($row5[0])*1000, "y" => $row5[1]); 
	}
}
//---
$sql6 = "SELECT Time, Cars_count FROM data WHERE Date='"."".$days[5]."'";
$result6 = $conn->query($sql6);
$dataPoints6= array();
if ($result6->num_rows > 0) {
	while($row6 = mysqli_fetch_row($result6)) {
	  
	    $dataPoints6[] = array("x" => strtotime($row6[0])*1000, "y" => $row6[1]); 
	}
}
//---
$sql7 = "SELECT Time, Cars_count FROM data WHERE Date='"."".$days[6]."'";
$result7 = $conn->query($sql7);
$dataPoints7 = array();
if ($result7->num_rows > 0) {
	while($row7 = mysqli_fetch_row($result7)) {
	  
	    $dataPoints7[] = array("x" => strtotime($row7[0])*1000, "y" => $row7[1]); 
	}
}
// close connect database
$conn->close();
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>VEHICLE CHART</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    .navbar {
      margin-bottom: 0;
      border-radius: 2;
    }
    .row.content {height: 450px}
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }
  </style> 
  <script>
window.onload = function () {
var today = "<?php echo $today; ?>";
document.getElementById("today").innerHTML=today;
var day1 = "<?php echo $day1; ?>";
var line1 = "<?php echo $days[0]; ?>";
var line2 = "<?php echo $days[1]; ?>";
var line3 = "<?php echo $days[2]; ?>";
var line4 = "<?php echo $days[3]; ?>";
var line5 = "<?php echo $days[4]; ?>";
var line6 = "<?php echo $days[5]; ?>";
var line7 = "<?php echo $days[6]; ?>";
var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
	  text: "Vehicle traffic of week "
	      },
	axisX:{  
	      title: "Time",
	      valueFormatString: "HH:mm"
	      },
	axisY:{  
	      title: "Cars"
	      },
	      	legend:{
		cursor: "pointer",
		fontSize: 16,
		itemclick: toggleDataSeries
	},
	toolTip:{
		shared: true
	},
	data: [{
		name: line1,
		type: "line",
		xValueFormatString: "YYYY-MM-DD HH:mm",
		yValueFormatString: "#### Cars",
		xValueType: "dateTime",
		showInLegend: true,
		dataPoints:  <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK) ?>
		
	},
	{
		name: line2,
		type: "line",
		xValueFormatString: "YYYY-MM-DD HH:mm",
		yValueFormatString: "#### Cars",
		showInLegend: true,
		xValueType: "dateTime",
		dataPoints:  <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK) ?>
		
	},
	{
		name: line3,
		type: "line",
		xValueFormatString: "YYYY-MM-DD HH:mm",
		yValueFormatString: "#### Cars",
		showInLegend: true,
		xValueType: "dateTime",
		dataPoints:  <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK) ?>
		
	}
	,
	{
		name: line4,
		type: "line",
		xValueFormatString: "YYYY-MM-DD HH:mm",
		yValueFormatString: "#### Cars",
		showInLegend: true,
		xValueType: "dateTime",
		dataPoints:  <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK) ?>
		
	}
	,
	{
		name: line5,
		type: "line",
		xValueFormatString: "YYYY-MM-DD HH:mm",
		yValueFormatString: "#### Cars",
		showInLegend: true,
		xValueType: "dateTime",
		dataPoints:  <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK) ?>
		
	}
	,
	{
		name: line6,
		type: "line",
		xValueFormatString: "YYYY-MM-DD HH:mm",
		yValueFormatString: "#### Cars",
		showInLegend: true,
		xValueType: "dateTime",
		dataPoints:  <?php echo json_encode($dataPoints6, JSON_NUMERIC_CHECK) ?>
		
	}
	,
	{
		name: line7,
		type: "line",
		xValueFormatString: "YYYY-MM-DD HH:mm",
		yValueFormatString: "#### Cars",
		showInLegend: true,
		xValueType: "dateTime",
		dataPoints:  <?php echo json_encode($dataPoints7, JSON_NUMERIC_CHECK) ?>
		
	}]
});
chart2.render();
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart2.render();
}
}
</script>
    

</head>
<body>
  
    <div><img style="height: 100px;margin-left: 350px;" src="iuh.png"></div>
    <nav class="navbar navbar-inverse">
	 	<div class="container-fluid">
		    <div class="navbar-header">
		      	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>                        
		      	</button>
		        <a class="navbar-brand" href="#">SMART TRAFFIC LIGHT</a>
		    </div>
		    <div class="collapse navbar-collapse" id="myNavbar">
		      	<ul class="nav navbar-nav">
			        <li style="margin-left: 20px;" ><a href="index.php">Date - Chart</a></li>
			        <li style="margin-left: 30px;" class="active"><a href="WeekChart.php">Week - Chart</a></li>
			        <li style="margin-left: 30px;"><a href="data.php">Data</a></li>
			        <li style="margin-left: 30px;"><a href="About.html">About</a></li>
		      	</ul>
		      	<ul class="nav navbar-nav navbar-right">
		      	<li>
				<div style="color: gray; margin-top:10px;">Today is: <div style="display: inline; " id="today"></div>
		      	</li>
		      	</ul>
		    </div>
	  	</div>
	</nav>
    <br>
    

	<form action="" method="post">
		<span style="margin-left:30px;">Select day of week: </span>
		<input  type="date" name="subject2" id="subject2">
		<button type="submit" name="ok2" class="btn btn-primary">OK</button>
		
	</form>
	<br>
	
<div id="chartContainer2" style="height: 300px; width: 100%;"></div>
<script src="canvasjs.min.js"></script>
</body>
<br>
<hr>
<footer class="container-fluid text-center">
	  <p>&copy; 2020. Instructor M.I.T Nguyen Thanh Thai
	     |  Created by Nguyen Luong Huy - Bui Huynh Nam.
	 </p>
	</footer>
</html>
