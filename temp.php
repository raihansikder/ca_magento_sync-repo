<?
include_once('config.php');
$client = new SoapClient("https://api.channeladvisor.com/ChannelAdvisorAPI/v6/InventoryService.asmx?WSDL");
$hData = array('DeveloperKey' => "5dbb05c3-25bd-40cd-8d0b-bb756614cce3", 'Password' => "Razon!@#1");
//print_r($hData);
$head = new SoapHeader("http://api.channeladvisor.com/webservices/","APICredentials",$hData);
//print_r($head);
$client->__setSoapHeaders($head);
$result = $client->DoesSkuExist(array("accountID" => "700cd2eb-ff3c-4969-a542-dfde923e14fd","sku" => "ELO2188BLKCHILD1"));
//myprint_r($result);
// Print the results.
print $result->DoesSkuExistResult->Status;


//updateCAProductQuantity('ELO2188BLKCHILD1',2)



//var_dump($result);
/*
print '<pre>';
var_export($result);
print '</pre>';


/*
$developer_key = '5dbb05c3-25bd-40cd-8d0b-bb756614cce3';
$password = 'Razon!@#1';
$account_guid = '700cd2eb-ff3c-4969-a542-dfde923e14fd';
$sku = 'FRE3216SLECHILD5';

$wsdl_url = 'https://api.channeladvisor.com/ChannelAdvisorAPI/v3/InventoryService.asmx?WSDL';
$api_url = 'http://api.channeladvisor.com/webservices/';

// Instantiate the client.
$client = new SoapClient($wsdl_url);

// Pass along login information
$soap_header = new SoapHeader(
	$api_url,
	'APICredentials',
	array(
		'DeveloperKey' => $developer_key,
		'Password' => $password
	)
);
$client->__setSoapHeaders($soap_header);

// Initiate the request.
$result = $client->DoesSkuExist(array(
	'accountID' => $account_guid,
	'sku' => $sku
));

// Print the results.
print '<pre>';
var_export($result);
print '</pre>';
*/

?>