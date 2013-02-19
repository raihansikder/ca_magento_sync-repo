
<?php
/*
* Initializing Channel Advisor API
*/
$accountID="700cd2eb-ff3c-4969-a542-dfde923e14fd";
$client = new SoapClient("https://api.channeladvisor.com/ChannelAdvisorAPI/v6/InventoryService.asmx?WSDL");
$hData = array('DeveloperKey' => "5dbb05c3-25bd-40cd-8d0b-bb756614cce3", 'Password' => "Razon!@#1");
$head = new SoapHeader("http://api.channeladvisor.com/webservices/","APICredentials",$hData);
$client->__setSoapHeaders($head);
/*
* Magento system file include
*/
//include('../includes/config.php');
require_once('../app/Mage.php');
Mage::app('default');
/*
* get product info from SKU
*/
$_Pdetails =Mage::getModel('catalog/product')->loadByAttribute('sku','200662648242');
//myprint_o($_Pdetails);
/***/

$categories = Mage::getResourceModel('catalog/category_collection');
foreach ($categories as $category) {
    $products = $category->getProductCollection();
    foreach ($products as $product) {
        $sku_arr[$product->getId()] = $product->getSku();
		$productId=$product->getId();
		$sku=$product->getSku();				
		if(MagentoSkuMatchesWithCASku($sku)){			
			echo "<div style='background-color:green; padding: 3px; color:#fff;'>";
			echo "$sku - exists!<br>";
			$sku_list=array("$sku");
			//$result = $client->GetInventoryItemAttributeList(array("accountID" => $accountID,"sku" => $sku));
			$result = $client->GetInventoryItemList(array("accountID" => $accountID,"skuList" => $sku_list));
			//myprint_o($result);
			$productCAprice= $result->GetInventoryItemListResult->ResultData->InventoryItemResponse->PriceInfo->TakeItPrice;						
			echo "productCAprice: $productCAprice<br>";
			
			Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
			$product->setPrice($productCAprice)->save();
						
			$result = $client->GetInventoryItemQuantityInfo(array("accountID" => $accountID,"sku" => $sku));
			$availableQuantityInCA=$result->GetInventoryItemQuantityInfoResult->ResultData->Available;
			echo "Available Quantity in ChannelAdvisor : $availableQuantityInCA<br>";
			/*
			*	Update local quantity
			*/				
				$stockItem =Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
				$stockItemId = $stockItem->getId();			
				$stockItem->setData('manage_stock', 1);
				$stockItem->setData('qty', (integer)$availableQuantityInCA);			
				$stockItem->save();
				echo "[Product ID:$productId] [sku:$sku] Updated Quanty to $availableQuantityInCA <br>";			
				
			//myprint_o($result);
			echo "</div>";
		}else{
			echo "<div style='background-color:red; padding: 3px; color:#fff;'>";
			echo "SKU $sku - Doesn't match with ChannelAdvisor Inventory<br>";
			echo "</div>";
		}
		//echo $product->getSku()."<br>";
    }
}
//myprint_r($sku_arr);


/*
echo $_Pdetails->getName();
echo $_Pdetails->getDescription();
echo $_Pdetails->getPrice();
echo $_Pdetails->getProductUrl();
*/

/*
$product = Mage::helper('catalog/product')->getProduct('200662648242', Mage::app()->getStore()->getId(), 'sku'); 
myprint_o($product);
*/



/***********************************/
function GetInventoryItemQuantityInfo($accountID,$sku){
	global $accountID;
	global $client;	
	$result = $client->GetInventoryItemQuantityInfo(array("accountID" => $accountID,"sku" => $sku));			
	return $result;	
}

function MagentoSkuMatchesWithCASku($sku){
	global $accountID;
	global $client;		
	$result = $client->DoesSkuExist(array("accountID" => $accountID,"sku" =>$sku));
	if($result->DoesSkuExistResult->ResultData==true){
		//myprint_o($result);	
		return true; 
	}else{
		return false;
	}
}

function updateProductQty($product_id, $new_quantity) {
 	$sql="UPDATE cataloginventory_stock_item item_stock, cataloginventory_stock_status status_stock
       SET item_stock.qty = '$new_quantity', item_stock.is_in_stock = IF('$new_quantity'>0, 1,0),
       status_stock.qty = '$new_quantity', status_stock.stock_status = IF('$new_quantity'>0, 1,0)
       WHERE item_stock.product_id = '$product_id' AND item_stock.product_id = status_stock.product_id " ;
	 echo "<br>Query:<br>_______<br>$sql<br>";  
	 $r=mysql_query($sql)or die(mysql_error()."<br>Query:<br>_______<br>$sql<br>");  
	 echo "Affected Rows: ".mysql_affected_rows($r)."<br>";
	 return true;
}



?>