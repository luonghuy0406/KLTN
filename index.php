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
//get data from calendar
if(isset($_POST['ok'])){
$day1= $_POST['subject'];
} 

//get data from databse for chart 1
$today=date("Y-m-d");
$sql = "SELECT Time, Cars_count  FROM data WHERE Date='"."".$day1."'";
$result = $conn->query($sql);
$dataPoints = array();
if ($result->num_rows > 0) {
	while($row = mysqli_fetch_row($result)) {
	    $dataPoints[] = array("x" => strtotime($row[0])*1000, "y" => $row[1]); 
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
//chart 1
var chart1 = new CanvasJS.Chart("chartContainer1", {
	animationEnabled: true,
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
	  text: "Vehicle traffic of "+ day1
	      },
	axisX:{  
	      title: "Time",
	      valueFormatString: "HH:mm"
	      },
	axisY:{  
	      title: "Cars"
	      },
	data: [{
		name: day1,
		type: "line",
		xValueFormatString: "YYYY-MM-DD HH:mm",
		yValueFormatString: "#### Cars",
		xValueType: "dateTime",
		dataPoints:  <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK) ?>
		
	}]
});
chart1.render();
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart1.render();
}
}
</script>
    

</head>
<body>
  
    <div><img style="height: 60px;" src="iuh.png"></div>
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
			        <li style="margin-left: 20px;" class="active"><a href="index.php">Date - Chart</a></li>
			        <li style="margin-left: 30px;"><a href="WeekChart.php">Week - Chart</a></li>
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
		<span style="margin-left:30px;">Select date: </span>
		<input  type="date" name="subject" id="subject">
		<button type="submit" name="ok" class="btn btn-primary">OK</button>
	</form>
	<br>
	
<div id="chartContainer1" style="height: 300px; width: 100%;"></div>


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
