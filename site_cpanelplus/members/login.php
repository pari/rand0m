<?
$uemembername = $_POST["membername"];
$uepwd= $_POST["memberpwd"];

include "../mysql.inc";
$link = mysql_connect("$mysqlhost", "$mysqluser", "$mysqlpassword") or die("Could not connect : " . mysql_error());
mysql_select_db("$mysqldb") or die("Could not select database"); 
$result = mysql_query("select password,name,nexion,emailid from members where username ='$uemembername'") or die("Query failed : " . mysql_error());
$num_rows = mysql_num_rows($result); 

if($num_rows <> 1){ echo "Invalid Login <BR><BR> <A href=\"/login.html\">Ok</A>"; exit();}

while($row = mysql_fetch_array($result, MYSQL_ASSOC)){ 
	$password = $row["password"] ; 
	$membername = $row["name"];
	$nexionstatus = $row["nexion"];
	$memberemail = $row["emailid"];
	}
if($uepwd !== $password){echo "Invalid Login"; exit();}

// Yes Authenticated
session_start();
$_SESSION["lmembername"]= $uemembername;
$_SESSION["nexionstatus"] = $nexionstatus ;
$_SESSION["memberemail"] = $memberemail ;


mysql_close($link); 
header("Location: member.php"); exit();


?>