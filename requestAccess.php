<?php
/*
*	http://developer.channeladvisor.com/display/cadn/PHP+GetInventoryQuantity
*/
$client = new SoapClient("https://api.channeladvisor.com/ChannelAdvisorAPI/v6/AdminService.asmx?WSDL");
$hData = array('DeveloperKey' => "5dbb05c3-25bd-40cd-8d0b-bb756614cce3", 'Password' => "Razon!@#1");
print_r($hData);
$head = new SoapHeader("http://api.channeladvisor.com/webservices/","APICredentials",$hData);
print_r($head);
$client->__setSoapHeaders($head);
$result = $client->RequestAccess(array("localID" => "72001399"));
?>