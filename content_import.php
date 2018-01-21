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

<?php
$sql = "SELECT unit_code FROM unit";

$result = $conn->query($sql) or die($conn->error);

while ($row = $result->fetch_assoc()) {
	$unit_code = $row['unit_code'];
	//echo $unit_id;
	// search for all content items for this unit
	$sql = "SELECT item FROM content_items WHERE unit_code='$unit_code'";
	$res = $conn->query($sql);

	$contents = '';
	while ($rows = $res->fetch_assoc()) {
		$contents .= ($rows['item'] . "\n");
	}

	$contents = addslashes($contents);

	// Import contents into unit table
	$sql = "UPDATE unit SET unit_content='$contents' WHERE unit_code='$unit_code'";
	$res = $conn->query($sql);
}

if ($res) {
	echo "Contents imported into unit table successfully!<br>";
}
?>
</div>
</body>
</html>
