<?php
/*
* Magento system file include
*/
include('../includes/config.php');
$mageFilename = '../app/Mage.php';
require_once('../app/Mage.php');
umask(0);
/* Store or website code */
$mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';
/* Run store or run website */
$mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';
Mage::run($mageRunCode, $mageRunType);
//$product = Mage::helper('catalog/product')->getProduct('SKU_GOES_HERE', Mage::app()->getStore()->getId(), 'sku'); 
$product = Mage::helper('catalog/product')->getProduct('200662648242', Mage::app()->getStore()->getId(), 'sku'); 
myprint_o($product);
/*
* Initializing Channel Advisor API
*/
$accountID="700cd2eb-ff3c-4969-a542-dfde923e14fd";
$client = new SoapClient("https://api.channeladvisor.com/ChannelAdvisorAPI/v6/InventoryService.asmx?WSDL");
$hData = array('DeveloperKey' => "5dbb05c3-25bd-40cd-8d0b-bb756614cce3", 'Password' => "Razon!@#1");
$head = new SoapHeader("http://api.channeladvisor.com/webservices/","APICredentials",$hData);
$client->__setSoapHeaders($head);
/***********************************/
function GetInventoryItemQuantityInfo($accountID,$sku){
	global $accountID;
	global $client;	
	$result = $client->GetInventoryItemQuantityInfo(array("accountID" => $accountID,"sku" => $sku));			
	return $result;	
}
?>