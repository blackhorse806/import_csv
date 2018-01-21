<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "cups";
$conn = new mysqli($hostname,$username,$password,$database);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");
?>
<html>
<head>
<style type="text/css">
body
{
margin: 0;
padding: 0;
background-color:#FFFFFF;
text-align:center;
}
.top-bar
{
width: 100%;
height: auto;
text-align: center;
background-color:#FFF;
border-bottom: 1px solid #000;
margin-bottom: 20px;
}
.inside-top-bar
{
margin-top: 5px;
margin-bottom: 5px;
}
.link
{
font-size: 18px;
text-decoration: none;
background-color: #000;
color: #FFF;
padding: 5px;
}
.link:hover
{
background-color: #FCF3F3;
}
</style>

</head>
<body>
<div class="top-bar">
	<div class="inside-top-bar">Import Excel Spreadsheet Data into MYSQL Table<br><br>
	</div>
</div>
<div style="text-align:left; border:1px solid #333333; width:300px; margin:0 auto; padding:10px;">

<form name="import" method="post" enctype="multipart/form-data">
	<input type="file" name="file" /><br />
	<input type="submit" name="submit" value="Submit" />
</form>
<?php
if(isset($_POST['submit']))
{
	$file = $_FILES['file']['tmp_name'];
	$handle = fopen($file, "r");
	$c = 0;

	$today = date('Y-m-d');

	while(($line = fgetcsv($handle, 1000, ",")) !== false)
	{
		$unit_code = $line[0];
		$position = $line[1];
		$name = $line[2];
		$type = $line[3];
		$length = $line[4];
		$weight = $line[5];
		$ulo = $line[6];
		$threshold = $line[7];

		if (empty($type)) {
			$type = NULL;
		}

		// Remove LO in front of a ulo item
		list($lo, $rest) = explode(':', $ulo);

		if ($threshold == 'Yes') {
			$thres = '1';
		}
		else {
			$thres = '0';
		}

		// Retrieve unit id from DB
		$sql = "SELECT unit_id FROM unit WHERE unit_code='$unit_code'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$unit_id = $row['unit_id'];

		// Retrieve group id from DB
		$sql = "SELECT id FROM unit_assessment_group WHERE unit_id='$unit_id'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$grp_id = $row['id'];

		$sql = "INSERT INTO unit_assessments (group_id, name, type, length, ulos, weight, threshold, position, gi)
				VALUES ('$grp_id','$name', '$type', '$length', '$rest', '$weight', '$thres', '$position', 'Individual')";
		$result = $conn->query($sql);

		$c = $c + 1;
	}

	if($result){
		echo "You database has imported successfully. You have inserted ". $c ." records";
	}else{
		echo "Sorry! There is some problem.";
	}

}
?>
</div>
</body>
</html>
