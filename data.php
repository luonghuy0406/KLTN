
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
			        <li style="margin-left: 30px;"><a href="WeekChart.php">Week - Chart</a></li>
			        <li style="margin-left: 30px;" class="active"><a href="data.php">Data</a></li>
			        <li style="margin-left: 30px;"><a href="About.html">About</a></li>
		      	</ul>
		    </div>
	  	</div>
	</nav>
    <br>
    <div class = "container-fluid text-center">
    <h1>full data</h1>
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

//get data from databse for chart 1
$sql = "SELECT Date,Time, Cars_count  FROM data";
$result = $conn->query($sql);
$dataPoints = array();
if ($result->num_rows > 0) {
    // output dữ liệu trên trang
    while($row = $result->fetch_assoc()) {
	 
	echo "Date: " . $row["Date"]. " - Time: " . $row["Time"]. " Cars_count:"
            . $row["Cars_count"]. "<br>";
    }
} else {
    echo "0 results";
}

// close connect database
$conn->close();
?>
    </div>
</body>
<br>
<hr>
<footer class="container-fluid text-center">
	  <p>&copy; 2020. Instructor M.I.T Nguyen Thanh Thai
	     |  Created by Nguyen Luong Huy - Bui Huynh Nam.
	 </p>
	</footer>
</html>
