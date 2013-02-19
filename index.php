<?
include_once('config.php');
$client = new SoapClient("https://api.channeladvisor.com/ChannelAdvisorAPI/v6/InventoryService.asmx?WSDL");
$hData = array('DeveloperKey' => "5dbb05c3-25bd-40cd-8d0b-bb756614cce3", 'Password' => "Razon!@#1");
$head = new SoapHeader("http://api.channeladvisor.com/webservices/","APICredentials",$hData);
$client->__setSoapHeaders($head);
//$result = $client->DoesSkuExist(array("accountID" => "700cd2eb-ff3c-4969-a542-dfde923e14fd","sku" => "FRE3216SLECHILD5"));
//print $result->DoesSkuExistResult->Status;

$result = $client->GetInventoryItemQuantityInfo(array("accountID" => "700cd2eb-ff3c-4969-a542-dfde923e14fd","sku" => "FRE3216SLECHILD5"));
//var_dump($result);

print '<pre>';
var_export($result);
print '</pre>';


?>