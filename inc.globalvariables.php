<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
/*
*	Database information and connection
*/
// Local

$dbhost='localhost';		
$dbuser='root';
$dbpass='';
$dbname='lingerieoutletstore';

// live
/*
$dbhost='localhost';		
$dbuser='smartsec';
$dbpass='activation';
$dbname='smartsec_demo1';
*/  
mysql_select_db($dbname,mysql_connect($dbhost, $dbuser, $dbpass));

/*
* initiate PHPMailer
*/
/*
require_once("phpmailer/class.phpmailer.php");
$mail = new PHPMailer();
$mail->IsSMTP(); // send via SMTP
$mail->Host = "ssl://smtp.gmail.com";
$mail->Port = 465;
$mail->SMTPAuth = true; // turn on SMTP authentication
$mail->Username = "tes.smartsec@gmail.com"; // SMTP username
$mail->Password = "activation"; // SMTP password
//$webmaster_email = "username@doamin.com"; //Reply to this email ID
//$email="spider.xy@gmail.com"; // Recipients email ID
//$name="name"; // Recipient's name	
$mail->From = "tes.smartsec@gmail.com";
$mail->FromName = "Tesecurity";	
$mail->AddReplyTo($_SESSION[current_user_email],$_SESSION[current_user_fullname]);
$mail->AddCC("tesshaz@gmail.com","Tes Shaz");
$mail->AddCC("raihan.act@gmail.com","Raihan");
$mail->WordWrap = 50; // set word wrap
//$mail->AddAttachment("D:/a.txt"); // attachment
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
$mail->IsHTML(true); // send as HTML
/***********************************************/

?>
