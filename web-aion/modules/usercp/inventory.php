<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="page-header-block usercp"></div>
<br /><br />

<h3>View Inventory</h3>

<?php
try {
	
	# load server database
	$sdb = Handler::loadDB('siel');
	
	# player data
	$playerData = $sdb->queryFetchSingle("SELECT * FROM `players` WHERE `account_id` = ? AND `name` = ?", array($_SESSION['userid'], $_GET['player']));
	if(!is_array($playerData)) throw new Exception('Your request could not be completed, please try again later. [2]');
	
	echo '<p>You are now viewing <strong>'.$playerData['name'].'\'s</strong> inventory. For the complete list of enchantable items you may <a href="'.module_url('usercp/enchantable/', true).'">click here</a>.</p>';
	echo '<br /><br />';
	
	# player inventory (non-equipped)
	$playerInventory = $sdb->queryFetch("SELECT * FROM `inventory` WHERE `item_owner` = ? AND `is_equiped` = ?", array($playerData['id'], 0));
	
	# player inventory (equipped)
	$playerInventoryEquipped = $sdb->queryFetch("SELECT * FROM `inventory` WHERE `item_owner` = ? AND `is_equiped` = ?", array($playerData['id'], 1));
	
	# EQUIPPED
	if(is_array($playerInventoryEquipped)) {
		
		echo '<h4>Equipped Items:</h4>';
		echo '<table class="inventory-table">';
		foreach($playerInventoryEquipped as $itemInfo) {
			$itemName = getItemName($itemInfo['item_id']);
			//if(!check($itemName)) continue;
			//if(!check($itemName)) $itemName = 'Unknown';
			
			if(!check($itemName)) {
				aioncmsBuildAionItemList($itemInfo['item_id']);
				$itemName = 'Unknown';
			}
			
			$itemEnchantment = ($itemInfo['enchant'] > 0 ? ' <span style="color:red;font-weight:bold;">+'.$itemInfo['enchant'].'</span>' : null);
			$itemTempering = ($itemInfo['tempering'] > 0 ? ' <span style="color:orange;font-weight:bold;">+'.$itemInfo['tempering'].'</span>' : null);
			
			echo '<tr>';
				echo '<td>'.number_format($itemInfo['item_count']).'x</td>';
				echo '<td><a href="'.aionDatabaseLink($itemInfo['item_id']).'" target="_blank">'.$itemName.'</a>'.$itemEnchantment.$itemTempering.'</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	
	$enchantableItems = config('enchantable_items', true);
	$enchantablePrice = config('enchant_price', true);
		
	# NON-EQUIPPED
	if(is_array($playerInventory)) {
		
		echo '<br /><br />';
		echo '<h4>Non-Equipped Items:</h4>';
		echo '<table class="inventory-table">';
		foreach($playerInventory as $itemInfo) {
			$itemName = getItemName($itemInfo['item_id']);
			//if(!check($itemName)) continue;
			//if(!check($itemName)) $itemName = 'Unknown';
			
			if(!check($itemName)) {
				aioncmsBuildAionItemList($itemInfo['item_id']);
				$itemName = 'Unknown';
			}
			
			$itemEnchantment = ($itemInfo['enchant'] > 0 ? ' <span style="color:red;font-weight:bold;">+'.$itemInfo['enchant'].'</span>' : null);
			$itemTempering = ($itemInfo['tempering'] > 0 ? ' <span style="color:orange;font-weight:bold;">+'.$itemInfo['tempering'].'</span>' : null);
			
			echo '<tr>';
				echo '<td>'.number_format($itemInfo['item_count']).'x</td>';
				echo '<td>';
					echo '<a href="'.aionDatabaseLink($itemInfo['item_id']).'" target="_blank">'.$itemName.'</a>'.$itemEnchantment.$itemTempering.'';
					
					if(array_key_exists($itemInfo['item_id'], $enchantableItems)) {
						if(array_key_exists($itemInfo['enchant'], $enchantablePrice)) {
							//echo ' <a href="'.module_url('usercp/enchant/server/'.$_GET['server'].'/player/'.$playerData['name'].'/item/'.$itemInfo['item_unique_id'].'/', true).'" class="btn btn-xs btn-primary">Enchant ('.$enchantablePrice[$itemInfo['enchant']].' credits)</a>';
							echo ' <a href="'.module_url('usercp/enchant/server/'.$_GET['server'].'/player/'.$playerData['name'].'/item/'.$itemInfo['item_unique_id'].'/', true).'" class="btn btn-xs btn-primary">Enchant</a>';
						}
						
						if($itemInfo['enchant'] == 20) {
							echo ' <a href="'.module_url('usercp/changeskill/server/'.$_GET['server'].'/player/'.$playerData['name'].'/item/'.$itemInfo['item_unique_id'].'/', true).'" class="btn btn-xs btn-default">Change Skill</a>';
						}
					}
					
					
					
				echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	
} catch(Exception $ex) {
	//message($ex->getMessage(), 'error');
	//redirect('usercp/characters/');
	die($ex->getMessage());
}
?>