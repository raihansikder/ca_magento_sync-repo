
<?php
/*
* Initializing Channel Advisor API
*/

echo "Starting sync...<br>";
$accountID="700cd2eb-ff3c-4969-a542-dfde923e14fd";
$client = new SoapClient("https://api.channeladvisor.com/ChannelAdvisorAPI/v6/InventoryService.asmx?WSDL");
$hData = array('DeveloperKey' => "5dbb05c3-25bd-40cd-8d0b-bb756614cce3", 'Password' => "Razon!@#1");
$head = new SoapHeader("http://api.channeladvisor.com/webservices/","APICredentials",$hData);
echo "Connecting...<br>";
$client->__setSoapHeaders($head);
echo "Connected...<br>";
/*
* Magento system file include
*/
myprint_o($client);

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

function updateCAProductQuantity($sku,$newQty){
    echo "Updating CA Production Qty sku:$sku ,newQty: $newQty<br>";
    global $accountID;
	global $client;
	$result = $client->UpdateInventoryItemQuantityAndPrice(
	array(
    	"accountID" => $accountID,
    	"itemQuantityAndPrice" =>
            array(
              "Sku"=> $sku,
              "QuantityInfo"=>
               array(
                 "UpdateType"=>"Absolute",
                 "Total"=>"$newQty"
               )
            )
	));
	//myprint_o($result);
	if($resutl->UpdateInventoryItemQuantityAndPriceResult->Status=='Success'){
	  return true;
	}else{
	  return false;
	}

	/*
	 * <web:accountID>---</web:accountID>
         <web:itemQuantityAndPrice>
            <web:Sku>Sku1</web:Sku>
            <web:QuantityInfo>
               <web:UpdateType>Relative</web:UpdateType>
               <web:Total>5</web:Total>
            </web:QuantityInfo>
            <web:PriceInfo>
               <web:Cost>3</web:Cost>
               <web:RetailPrice>8</web:RetailPrice>
               <web:StartingPrice>5</web:StartingPrice>
               <web:ReservePrice>7</web:ReservePrice>
               <web:TakeItPrice>6</web:TakeItPrice>
               <web:SecondChanceOfferPrice>7</web:SecondChanceOfferPrice>
               <web:StorePrice>8</web:StorePrice>
            </web:PriceInfo>
         </web:itemQuantityAndPrice>
	 *
	 */
	return $result;
}


?>