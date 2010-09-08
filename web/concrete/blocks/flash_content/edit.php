<?
	defined('C5_EXECUTE') or die("Access Denied.");
	$al = Loader::helper('concrete/asset_library');
	$bf = null;
	if ($controller->getFileID() > 0) { 
		$bf = $controller->getFileObject();
	}
?>
<h2><?=t('Flash File')?></h2>
<?=$al->file('ccm-b-file', 'fID', t('Choose File'), $bf);?>

<br/><br/>
<h2><?=t('Quality')?></h2>
<select name="quality">
	<option value="low" <?=($quality == "low"?"selected=\"selected\"":"")?>>low</option>
    <option value="autolow" <?=($quality == "autolow"?"selected=\"selected\"":"")?>>autolow</option>
    <option value="autohigh" <?=($quality == "autohigh"?"selected=\"selected\"":"")?>>autohigh</option>
    <option value="medium" <?=($quality == "medium"?"selected=\"selected\"":"")?>>medium</option>
    <option value="high" <?=($quality == "high"?"selected=\"selected\"":"")?>>high</option>
    <option value="best" <?=($quality == "best"?"selected=\"selected\"":"")?>>best</option>
</select><br /><br />

<h2><?=t('Minimum Flash Player Version')?></h2>
<input type="text" name="minVersion" value="<?=$minVersion?>" /><br /><br />