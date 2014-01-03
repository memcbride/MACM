<?php 
/*
Copyright (c) 1999 - 2003 Mark E. McBride & Christian Ratterman. All Rights Reserved.

You may study, use, modify, and distribute this software for any 
purpose within an academic environment provided that this copyright 
notice appears in all copies. Business and Corporate use requires 
expressed permission from the authors listed below.

@Authors: Mark E. McBride & Christian Ratterman
@Contact: mcbridme@muohio.edu
@Version: 2.1
@date: May 6, 2003
*/
?>
<?php require_once('../Connections/CAPM.php'); ?>
<?php include('header.php');
/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

 Functions for clear_market.php
 
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/

//ClearQuantity function
function ClearQuantity($row, $a_bids){
	$clearQ = 0;
	if($row >= 0 && $row < count($a_bids)){
		if($a_bids[$row]['QD'] < $a_bids[$row]['QS']){
			$clearQ = $a_bids[$row]['QD'];
		}else{
			$clearQ = $a_bids[$row]['QS'];
		}
	}
	return $clearQ;
}

//FillOrders function
function FillOrders($theQuantity, $thePrice, $round, $asset){
include('../Connections/CAPM.php');
// setup local counters
	$ns = 0;  // counts number of suppliers at equil price
	$nd = 0;  // counts number of demanders at equil price
	$mqs = 0; // counts marginal quantity supplied
	$mqd = 0; // counts marginal quantity demanded
	$marQ = $theQuantity; // remaining quantity to allocate to marginals
	$sqs = 0; // share of marginal quantity supplied
	$sqd = 0; // share of marginal quantity demanded
	$remainNd = 0; // marginal demanders not satisfied
	$remainNs = 0; // marginal demanders not satisfied
	$remainQ = $theQuantity;
	
	//Query to get orders and bids for FillOrders function
	mysql_select_db($database_CAPM, $CAPM);
	$query_fill = "SELECT orders.order_id, orders.quantity, orders.price, orders.type, orders.executed FROM orders WHERE orders.round_id = $round AND orders.asset_id = $asset AND orders.quantity != 0 ORDER BY orders.price, orders.quantity";
	$fill = mysql_query($query_fill, $CAPM) or die(mysql_error());
	$row_fill = mysql_fetch_assoc($fill);
	$totalRows_fill = mysql_num_rows($fill);
	
	//creates array Called a_orders that holds all orders.
	do{
		$order_id = $row_fill['order_id'] + 0;
		$quantity = $row_fill['quantity'] + 0;
		$price = $row_fill['price'] + 0;
		$type = $row_fill['type'];
		$executed = $row_fill['executed'];
		
		$a = compact('order_id','quantity','price','type','executed');
		$a_orders[] = $a;
	} while ($row_fill = mysql_fetch_assoc($fill)); 

	mysql_free_result($fill); //It's in the array so it dumps the results

	// count the number of marginal suppliers
	for ($i=0;$i< count($a_orders);$i++) {
		if ($a_orders[$i]['price'] == $thePrice && $a_orders[$i]['type']== "Offer") {
			$ns++;
			$mqs += $a_orders[$i]['quantity'];
		}
	}
	$remainNs = $ns;
	// count the number of marginal demanders
	for ($i=0; $i < count($a_orders);$i++) {
		if ($a_orders[$i]['price'] == $thePrice && $a_orders[$i]['type']== "Bid") {
			$nd++;
			$mqd += $a_orders[$i]['quantity'];
		}
	}
	$remainNd = $nd;
	
	// Since we want to be able to re-run this 
	// let's first zero all of the executed orders
	for ($i=0;$i< count($a_orders);$i++) {
		$a_orders[$i]['executed'] = 0;
	}
	
	// Makes two passes through the orders
	// First, fill the offers
	for ($i=0;$i< count($a_orders);$i++) {
		if ($a_orders[$i]['type']== "offer") {
			if ($a_orders[$i]['price'] < $thePrice) {
					if (($remainQ - $a_orders[$i]['quantity'])>0) {
						$a_orders[$i]['executed'] = $a_orders[$i]['quantity'];
						$remainQ -= $a_orders[$i]['quantity'];
						$marQ = $remainQ;
					}
					else {
						$a_orders[$i]['executed'] = $remainQ;
						$remainQ = 0;
					}
			} 
			elseif ($a_orders[$i]['price'] == $thePrice) {
				if ($remainNs > 1) {
					$sqs = ($a_orders[$i]['quantity']/$mqs);
					$a_orders[$i]['executed'] = round($marQ*$sqs);
					$remainQ -= round($marQ * $sqs);
					$remainNs--;
				}
				else if ($remainQ > 0){
					if (($remainQ-$a_orders[$i]['quantity'])>0) {
						$a_orders[$i]['executed'] = $a_orders[$i]['quantity'];
						$remainQ -= $a_orders[$i]['quantity'];
						$marQ = $remainQ;
					}
					else {
						$a_orders[$i]['executed'] = $remainQ;
						$remainQ = 0;
					}
				}
			}
		}
	}
	// Second, fill the bids
	
	$marQ = $theQuantity;
	$remainQ = $theQuantity;
	for($i=count($a_orders)-1; $i>=0; $i--) {
		
		if ($a_orders[$i]['type']== "bid") {
			if ($a_orders[$i]['price'] > $thePrice) {
					$a_orders[$i]['executed'] = $a_orders[$i]['quantity'];
					$remainQ -= $a_orders[$i]['quantity'];
					$marQ = $remainQ;
				
			}
			elseif ($a_orders[$i]['price'] == $thePrice) {
				if ($remainNd > 1) {
					$sqd = ($a_orders[$i]['quantity']/$mqd);
					$a_orders[$i]['executed'] = round(marQ*sqd);
					$remainQ -= round($marQ*$sqd);
					$remainNd--;
					
				}
				elseif ($remainQ > 0) {
					if (($remainQ-$a_orders[$i]['quantity'])>0) {
					$a_orders[$i]['executed'] = $a_orders[$i]['quantity'];
					$remainQ  -= $a_orders[$i]['quantity'];
					$marQ = $remainQ;
					
					}
					else {
						$a_orders[$i]['executed'] = $remainQ;
						$remainQ = 0;
						
					}
				}
			}
		}
	}
	return $a_orders;
}



/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

CREATES VALUES FOR MARKET & CLEARING TABLES AND UPDATES ORDERS TABLE

XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
$round = $HTTP_POST_VARS['rid']; 
$rnum = $HTTP_POST_VARS['rnum']; 
$clearing = 0;

mysql_select_db($database_CAPM, $CAPM);
$query_assets = "SELECT asset.asset_id, asset.name FROM asset, round WHERE asset.experiment_id = round.experiment_id AND round.round_id = $round";
$assets = mysql_query($query_assets, $CAPM) or die(mysql_error());
$row_assets = mysql_fetch_assoc($assets);
$totalRows_assets = mysql_num_rows($assets);

do{
$asset = $row_assets['asset_id'];
$asset_name = $row_assets['name'];
unset($a_bids, $a_offers, $a_orders);


//below query gets all bids and orders them form high price to low price
mysql_select_db($database_CAPM, $CAPM);
$query_Get_bids = "SELECT orders.order_id, orders.quantity, orders.price FROM orders WHERE orders.round_id = $round AND orders.asset_id = $asset AND orders.type = 'bid' AND orders.quantity != 0 ORDER BY orders.price DESC, orders.quantity DESC";
$Get_bids = mysql_query($query_Get_bids, $CAPM) or die(mysql_error());
$row_Get_bids = mysql_fetch_assoc($Get_bids);
$totalRows_Get_bids = mysql_num_rows($Get_bids);

//Build bids Array a_bids

if($totalRows_Get_bids > 0){//checks to make sure there are bids
	$QD = 0;
	$count = 0;
	$last_price = 0;
	do {
		if($last_price == $row_Get_bids['price']){ //checks to see if the row price is the same as the last one.
			$row = $count - 1;
			$a_bids[$row]['quantity'] += $row_Get_bids['quantity'];
			$a_bids[$row]['QD'] += $row_Get_bids['quantity'];// adds quantity of = price to last entry
			$a_bids[$row]['ND'] += 1;
			$QD += $row_Get_bids['quantity']; // keeps the upcount on QD going
		}else{
			$quantity = $row_Get_bids['quantity'] + 0;
			$price = $row_Get_bids['price'] + 0; 
			$QD += $row_Get_bids['quantity'];
			$ND = 1;
			$QS = 0;
			$NS = 0;
			$ED = 0;
			$NT = 0;
		
			$a = compact('quantity', 'price', 'QS', 'NS', 'QD', 'ND', 'ED', 'NT');
			$a_bids[] = $a; //bids array
			$last_price = $price;
			$count += 1;
		}
		
	} while ($row_Get_bids = mysql_fetch_assoc($Get_bids)); 
}else{
	$a_bids = 0;
}

//below query gets all offers and orders them form low price to high price
mysql_select_db($database_CAPM, $CAPM);
$query_get_offer = "SELECT orders.order_id, orders.price, orders.quantity FROM orders WHERE orders.round_id = $round AND orders.asset_id = $asset AND orders.type = 'offer' AND orders.quantity != 0 ORDER BY orders.price, orders.quantity ";
$get_offer = mysql_query($query_get_offer, $CAPM) or die(mysql_error());
$row_get_offer = mysql_fetch_assoc($get_offer);
$totalRows_get_offer = mysql_num_rows($get_offer);



//Build bids Array a_offers
if($totalRows_get_offer > 0){
	$QS = 0;//$QS_total;
	$count = 0;
	$last_price = null;
	do {
		if($last_price == $row_get_offer['price']){ //checks to see if the row price is the same as the last one.
			$row = $count - 1;
			$a_offers[$row]['quantity'] += $row_get_offer['quantity'];// adds quantity of = price to last entry
			$a_offers[$row]['QS'] += $row_get_offer['quantity'];
			$a_offers[$row]['NS'] += 1;
			$QS += $row_get_offer['quantity']; //keeps count going for QS
		}else{
			$quantity = $row_get_offer['quantity'] + 0;
			$QS += $row_get_offer['quantity'] + 0;
			$price = $row_get_offer['price'] + 0; 
			$NS = 1;
			$QD = 0;
			$ND = 0;
			$ED = 0;
			$NT = 0;
		
			$a = compact('quantity', 'price', 'QS', 'NS', 'QD', 'ND', 'ED', 'NT');
			$a_offers[] = $a; //offers array
			
			$last_price = $price;
			$count += 1;
		}
		
	} while ($row_get_offer = mysql_fetch_assoc($get_offer)); 
}else{
	$a_offers= 0;
}

//merge the a_offers and a_bids into a_market
if($totalRows_get_offer > 0){
	$ao_count = count($a_offers) -1;//used for loop to make sure it does not go up as things are inserted
	if($a_bids == 0){
		$a_bids = $a_offers;
	}else{
		$ab_count = count($a_bids);
	
		$QS = 0;
		
		for($i=$ao_count; $i >= 0; $i--){ //loop for a_offers
			$inserted = false;
			for($j=0; $j < $ab_count; $j++){ //loop for a_bids
				if($a_offers[$i]['price'] == $a_bids[$j]['price']){ // if offer price is == to bid price then the two are joined 
					$a_bids[$j]['QS'] += $a_offers[$i]['QS'];
					$a_bids[$j]['NS'] += $a_offers[$i]['NS'];
					$inserted = true;
					break;
				}
			}
			//adds all supply offers which are not == to a bid
			if($inserted == false){
				$quantity = $a_offers[$i]['quantity'] + 0;
				$price = $a_offers[$i]['price'] + 0; 
				$QS = $a_offers[$i]['QS'] + 0;
				$NS = $a_offers[$i]['NS'] + 0;
				$QD = 0;
				$ND = 0;
				$ED = 0;
				$NT = 0;
				$a = compact('quantity', 'price', 'QS', 'NS', 'QD', 'ND', 'ED', 'NT');
				$a_bids[] = $a; 
			}
		}
	}
}	
		
//sorts the a_bids array to be ordered by price
//Get sorted array of prices.
if($totalRows_get_offer > 0 || $totalRows_Get_bids > 0){
	for($i=0; $i < count($a_bids); $i++){
		$a_prices[] = $a_bids[$i]['price'];
	}
	sort($a_prices, SORT_NUMERIC);
	//Create new array containing values in bids in the order of the price array
	for($i=0; $i < count($a_prices); $i++){
		for($j=0; $j < count($a_bids); $j++){
			if($a_prices[$i] == $a_bids[$j]['price']){
				$a_sorted_bids[] = $a_bids[$j];
				break;
			}
		}
	}
	$a_bids = $a_sorted_bids; //makes sorted list bids
	unset($a_sorted_bids); //destroys sorted bids array
	unset($a_prices);
	
	
	// Fill in Zeros for QS (forward through array)
	$VALUE1 = $a_bids[0]['QS'];
	$totalRecords = count($a_bids);
	for($i=1; $i < $totalRecords; $i++){
		$VALUE2 = $a_bids[$i]['QS'] + 0;
		if($VALUE2 == 0){
			$a_bids[$i]['QS'] = $VALUE1;
		}else{
			$VALUE1 = $VALUE2;
		}
	}
	
	
	//fill in Zeros for QD (backward through array)
	$high_id = count($a_bids) - 1;
	$VALUE1 = $a_bids[$high_id]['QD'];
	for($i=$high_id - 1; $i >= 0; $i--){
		$VALUE2 = $a_bids[$i]['QD'];
		if($VALUE2 == 0){
			$a_bids[$i]['QD'] = $VALUE1;
		}else{
			$VALUE1 = $VALUE2;
		}
	}
	
	//Calculate EXCESS DEMAND
	for($i=0; $i < count($a_bids); $i++){
		$a_bids[$i]['ED'] = $a_bids[$i]['QD'] - $a_bids[$i]['QS'];
	}
	
	
	//Finds Market Clearing Excess Demand
	$oldmin = abs($a_bids[0]['ED']);
	$min_row = 0;
	for($i=1; $i < count($a_bids); $i++){
		if(abs($a_bids[$i]['ED']) <= $oldmin){
			$oldmin = abs($a_bids[$i]['ED']);
			$min_row = $i;
		}
	}

	
	//Check row found for special cases
	$clearing = $min_row;
	$clear =  ClearQuantity($min_row, $a_bids);
	$cleara = ClearQuantity($min_row + 1, $a_bids);
	$clearb = ClearQuantity($min_row - 1, $a_bids);
	if($clear > $clearb && $clear > $cleara){
		$clearing = $min_row;
	}elseif($clearb > $clear && $clearb > $cleara){
		$clearing = $min_row - 1;
	}elseif($cleara > $clear && $cleara > $clearb){
		$clearing = $min_row + 1;
	}

	
	//check for 2 == absolute values
	if(($clearing - 1)>=0){
		if(abs($a_bids[$clearing]['ED']) == abs($a_bids[$clearing - 1]['ED'])){
			$median= $clearing - 1; //median is the row it is set == to
		}
	}
	if(($clearing + 1) < count($a_bids)){
		if(abs($a_bids[$clearing]['ED']) == abs($a_bids[$clearing + 1]['ED'])){
			$median = $clearing + 1;
		}
	}
	//fill orders array with executed
	if(isset($median)){
		$clearP = ($a_bids[$median]['price'] + $a_bids[$clearing]['price']) / 2;
	}else{
		$clearP = $a_bids[$clearing]['price'];
	}
	if($a_bids == 0){
		$clearQ = 0;
	}else{
		$clearQ = ClearQuantity($clearing, $a_bids);
	}
	if($a_bids != 0){
		$a_orders = FillOrders($clearQ, $clearP, $round, $asset);
	}else{
		$a_orders = 0;
	}


	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	INSERTS CLEARING VALUES INTO DB
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	if(isset($a_bids[$clearing]['price'])){
	$Cprice = $a_bids[$clearing]['price'];
	}else{
	$Cprice = 0;
	}
	
	mysql_select_db($database_CAPM, $CAPM);
	$query_insert_clear = "INSERT INTO clearing (round_id, asset_id, price, volume) VALUES ($round, $asset, $Cprice, $clearQ)";
	mysql_query($query_insert_clear, $CAPM) or die($query_insert_clear);
	
	
	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	INSERT VALUES IN THE A_BIDS ARRAY INTO THE DB
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	if($a_bids != 0){
		for($i=0; $i< count($a_bids); $i++){
			$price = $a_bids[$i]['price'];
			$QS = $a_bids[$i]['QS'];
			$QD = $a_bids[$i]['QD'];
			$NS = $a_bids[$i]['NS'];
			$ND = $a_bids[$i]['ND'];
			$ED = $a_bids[$i]['ED'];
			$NT = $a_bids[$i]['NT'];
			
			mysql_select_db($database_CAPM, $CAPM);
			$query_insert_abids = "INSERT INTO market (round_id, asset_id, price, QS, QD, NS, ND, ED, ntrans) VALUES ($round, $asset, $price, $QS, $QD, $NS, $ND, $ED, $NT)";
			mysql_query($query_insert_abids, $CAPM) or die($query_insert_abids);
		}
	}
	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	UPDATE ORDERS TO CONTAIN THE NUMBER OF ORDERS EXECUTED
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	if($a_orders != 0){
		for($i=0; $i< count($a_orders); $i++){
			if($a_orders[$i]['executed'] > 0){
				$ex = $a_orders[$i]['executed'];
				$oid = $a_orders[$i]['order_id'];
				
				mysql_select_db($database_CAPM, $CAPM);
				$query_update_aorders = "UPDATE orders SET executed = $ex WHERE order_id = $oid";
				mysql_query($query_update_aorders, $CAPM) or die(mysql_error());
			}
		}
	}
}



/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Prints the arrays for test viewing
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
/*?>

Round ID =<?php echo $round; ?><BR>
Asset ID =<?php echo $asset; ?><BR>
Asset Name = <?php echo $asset_name; ?><BR>
Market Clearing Row = <?php echo $clearing; ?><BR>
Market Clearing Quantity = <?php echo ClearQuantity($clearing, $a_bids); ?><BR>
Market Clearing Price = <?php echo $a_bids[$clearing]['price']; ?><BR>
<strong>Market Table</strong>
<table width="75%" border="1">
  <tr>
    <td>Row ID</td>
    <td>Price</td>
    <td>QS</td>
    <td>NS</td>
    <td>QD</td>
    <td>ND</td>
    <td>ED</td>
    <td>NT</td>
  </tr>
  <?php 
for($count = 0; $count < count($a_bids); $count++){ ?><tr>
    <td><?php echo $count ?></td>
    <td><?php echo $a_bids[$count]['price'] ?></td>
    <td><?php echo $a_bids[$count]['QS'] ?></td>
    <td><?php echo $a_bids[$count]['NS'] ?></td>
    <td><?php echo $a_bids[$count]['QD'] ?></td>
    <td><?php echo $a_bids[$count]['ND'] ?></td>
    <td><?php echo $a_bids[$count]['ED'] ?></td>
    <td><?php echo $a_bids[$count]['NT'] ?></td>
  </tr><?php }; ?>
</table>

<?php

echo "<BR>";
echo "<BR>";
echo "<strong>Orders Table</strong>";
?>

<table width="75%" border="1">
  <tr>
    <td>Row ID</td>
    <td>order_id</td>
    <td>quantity</td>
    <td>price</td>
    <td>type</td>
    <td>executed</td>
    
  </tr>
  <?php 
for($count = 0; $count < count($a_orders); $count++){ ?><tr>
    <td><?php echo $count ?></td>
    <td><?php echo $a_orders[$count]['order_id'] ?></td>
    <td><?php echo $a_orders[$count]['quantity'] ?></td>
    <td><?php echo $a_orders[$count]['price'] ?></td>
    <td><?php echo $a_orders[$count]['type'] ?></td>
    <td><?php echo $a_orders[$count]['executed'] ?></td>
  </tr><?php } ?>
</table>

<?php
//$count = 0;
//do{
//var_dump($a_orders[$count]);
//echo "<BR>";
//$count += 1;
//}while($count < count($a_orders));

echo "<BR>";
echo "<BR>";
echo "<BR>";
*/

mysql_free_result($Get_bids);

mysql_free_result($get_offer);

} while ($row_assets = mysql_fetch_assoc($assets)); 

mysql_free_result($assets);

header("Location: execute_orders.php?round=$round&rnum=$rnum");
?>