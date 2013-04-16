<?
include_once('config.php');
include_once('../app/Mage.php');
Mage::app('default');
echo "Mage initiated...<br>";
//$result = $client->DoesSkuExist(array("accountID" => "700cd2eb-ff3c-4969-a542-dfde923e14fd","sku" => "FRE3216SLECHILD5"));
//print $result->DoesSkuExistResult->Status;
//myprint_o(GetInventoryItemQuantityInfo($accountID,'FRE3216SLECHILD5'));

/*
* get product info from SKU
*/
//$_Pdetails =Mage::getModel('catalog/product')->loadByAttribute('sku','200662648242');
//myprint_o($_Pdetails);
/***/

$categories = Mage::getResourceModel('catalog/category_collection');
//myprint_o($categories);

foreach ($categories as $category) {
    $products = $category->getProductCollection();
    //myprint_o($products);

    $i=0;
    foreach ($products as $product) {

        $sku_arr[$product->getId()] = $product->getSku(); // not used
    	$productId=$product->getId();
    	$MageProductSku=$product->getSku();

    	if(MagentoSkuMatchesWithCASku($MageProductSku)){
    		echo "<div style='background-color:green; padding: 3px; color:#fff;'>";
    		echo "$MageProductSku - exists!<br>";
    		$sku_list=array("$MageProductSku");
    		//$result = $client->GetInventoryItemAttributeList(array("accountID" => $accountID,"sku" => $sku));
    		$result = $client->GetInventoryItemList(array("accountID" => $accountID,"skuList" => $sku_list));
    		//myprint_o($result);
    		$productCAprice= $result->GetInventoryItemListResult->ResultData->InventoryItemResponse->PriceInfo->TakeItPrice;
    		echo "productCAprice: $productCAprice<br>";

    		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
    		$product->setPrice($productCAprice)->save();

    		$result = $client->GetInventoryItemQuantityInfo(array("accountID" => $accountID,"sku" => $MageProductSku));
    		$availableQuantityInCA=$result->GetInventoryItemQuantityInfoResult->ResultData->Available;
    		echo "Available Quantity in ChannelAdvisor : $availableQuantityInCA<br>";

    		//
    		//	Update local quantity
    		//
  			$stockItem =Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
  			$stockItemId = $stockItem->getId();
  			$MageProductQty=$stockItem->getQty();
  			if($availableQuantityInCA<$MageProductQty){
    			$stockItem->setData('manage_stock', 1);
    			$stockItem->setData('qty', (integer)$availableQuantityInCA);
    			$stockItem->save();
    			echo "[Product ID: $productId] [sku: $MageProductSku] Updated Quanty to $availableQuantityInCA <br>";
    			echo "Magento quantity updated<br>";
  			}else{
  			    if(updateCAProductQuantity($MageProductSku,$MageProductQty)){
  			      echo "CA quantity updated<br>";
  			    }
  			}

    		//myprint_o($result);
    		echo "</div>";
		}else{
			echo "<div style='background-color:red; padding: 3px; color:#fff;'>";
			echo "SKU $MageProductSku - Doesn't match with ChannelAdvisor Inventory<br>";
			echo "</div>";
		}

		//echo $MageProductSku."[".$MageProductQty."]<br>";
		$i++;
		if($i==2)break;
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




?>