<?
session_start();

	if(!$_SESSION["lmembername"]){
		session_unset(); 
		session_destroy(); 
		header("Location: index.php"); 
		exit();
	}

	$membername = $_SESSION["lmembername"] ;

if($_SESSION["nexionstatus"]!='Y'){echo "You have not purchased Nexion !"; exit();}


$number = $_GET["nx"];

switch ($number) { 
   case 1: 
       $file = "./dlds/default";
	   $filename = "Nexion_Default.cptheme";
       break; 
   case 2: 
       $file = "./dlds/red";
		$filename = "Nexion_Red.cptheme";
       break; 
   case 3: 
       $file = "./dlds/green";
	   $filename = "Nexion_Green.cptheme";
       break; 
   case 4: 
       $file = "./dlds/silver";
		$filename = "Nexion_Silver.cptheme";
       break; 
	default: 
       $file = "./dlds/default";
	   $filename = "Nexion_Default.cptheme";
} 



$len = filesize($file);
header("Cache-Control: public");
header("Content-Type: application/x-download");
header("Content-Disposition: attachment; filename=$filename");
header("Accept-Ranges: bytes");
header("Content-Length: $len");
readfile($file);
?>
