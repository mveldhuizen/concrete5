<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo APP_CHARSET?>">
<?php 
$html = Loader::helper('html');
$v = View::getInstance();
$v->disableEditing();

// Required JavaScript

$v->addHeaderItem($html->javascript('jquery.js'));
$v->addHeaderItem($html->javascript('ccm.dialog.js'));
$v->addHeaderItem($html->javascript('ccm.base.js'));
$v->addHeaderItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/i18n_js"></script>'); 

$v->addHeaderItem($html->javascript('jquery.rating.js'));
$v->addHeaderItem($html->javascript('jquery.form.js'));
$v->addHeaderItem($html->javascript('ccm.ui.js'));
$v->addHeaderItem($html->javascript('ccm.filemanager.js'));
$v->addHeaderItem($html->javascript('ccm.themes.js'));
$v->addHeaderItem($html->javascript('jquery.ui.js'));
$v->addHeaderItem($html->javascript('jquery.colorpicker.js'));
$v->addHeaderItem($html->javascript('ccm.popup_login.js'));

if (LANGUAGE != 'en') {
	$v->addHeaderItem($html->javascript('i18n/ui.datepicker-<?php echo LANGUAGE?>.js'));
}

// Require CSS
$v->addHeaderItem($html->css('ccm.dashboard.css'));
$v->addHeaderItem($html->css('ccm.colorpicker.css'));
$v->addHeaderItem($html->css('ccm.menus.css'));
$v->addHeaderItem($html->css('ccm.forms.css'));
$v->addHeaderItem($html->css('ccm.filemanager.css'));
$v->addHeaderItem($html->css('ccm.calendar.css'));
$v->addHeaderItem($html->css('ccm.dialog.css'));
$v->addHeaderItem($html->css('jquery.rating.css'));
$v->addHeaderItem($html->css('jquery.ui.css'));
$v->addHeaderItem($html->css('ccm.popup_login.css'));

require(DIR_FILES_ELEMENTS_CORE . '/header_required.php'); 

?>

<script type="text/javascript">
<?php 
$valt = Loader::helper('validation/token');
print "var CCM_SECURITY_TOKEN = '" . $valt->generate() . "';";
?>

</script>

<script type="text/javascript">
$(function() {
	$("div.message").show('highlight', {
		color: '#ffffff'
	});
	
	ccm_setupDashboardHeaderMenu();
});
</script>
</head>
<body>

<div id="ccm-dashboard-page">

<div id="ccm-dashboard-header">
<a href="<?php echo $this->url('/dashboard/')?>"><img src="<?php echo ASSETS_URL_IMAGES?>/logo_menu.png" height="49" width="49" alt="Concrete5" /></a>
</div>

<?php  
Loader::block('autonav');
$supportHelper=Loader::helper('concrete/support'); 
$nh = Loader::helper('navigation');
$dashboard = Page::getByPath("/dashboard");
$nav = AutonavBlockController::getChildPages($dashboard);
?>

<div id="ccm-system-nav-wrapper1">
<div id="ccm-system-nav-wrapper2">
<ul id="ccm-system-nav">
<li><a id="ccm-nav-return" href="<?php echo $this->url('/')?>"><?php echo t('Return to Website')?></a></li>
<li><a id="ccm-nav-dashboard-help" href="<?php echo MENU_HELP_URL?>"  helpwaiting="<?php echo (ConcreteSupportHelper::hasNewHelpResponse())?1:0 ?>"><?php echo t('Help')?></a></li>
<li class="ccm-last"><a id="ccm-nav-logout" href="<?php echo $this->url('/login/', 'logout')?>"><?php echo t('Sign Out')?></a></li>
</ul>
</div>
</div>

<div id="ccm-dashboard-nav">
<ul>
<?php 
foreach($nav as $n2) { 
	$cp = new Permissions($n2);
	if ($cp->canRead()) { 
		if ($c->getCollectionPath() == $n2->getCollectionPath() || (strpos($c->getCollectionPath(), $n2->getCollectionPath()) == 0) && strpos($c->getCollectionPath(), $n2->getCollectionPath()) !== false) {
			$isActive = true;
		} else {
			$isActive = false;
		}
?>
	<li <?php  if ($isActive) { ?> class="ccm-nav-active" <?php  } ?>><a href="<?php echo $nh->getLinkToCollection($n2, false, true)?>"><?php echo t($n2->getCollectionName())?> <span><?php echo t($n2->getCollectionDescription())?></span></a></li>
<?php  }

}?>
</ul>
</div>

<?php  if (isset($subnav)) { ?>

<div id="ccm-dashboard-subnav">
<ul><?php  foreach($subnav as $item) { ?><li <?php  if (isset($item[2]) && $item[2] == true) { ?> class="nav-selected" <?php  } ?>><a href="<?php echo $item[0]?>"><?php echo $item[1]?></a></li><?php  } ?></ul>
<br/><div class="ccm-spacer">&nbsp;</div>
</div>
<?php  } else if ($c->getCollectionID() != $dashboard->getCollectionID()) {
	// we auto-gen the subnav 
	// if we're right under the dashboard, we get items beneath us. If not we get items at our same level
	$pcs = $nh->getTrailToCollection($c);
	$pcs = array_reverse($pcs);
	if (count($pcs) == 2) {
		$parent = $c;
	} else {
		$parent = $pcs[2];
	}
	
	$subpages = AutonavBlockController::getChildPages($parent);
	$subpagesP = array();
	foreach($subpages as $sc) {
		$cp = new Permissions($sc);
		if ($cp->canRead()) { 
			$subpagesP[] = $sc;
		}
	}
	
	if (count($subpagesP) > 0) { 
	?>	
		<div id="ccm-dashboard-subnav">
		<ul><?php  foreach($subpagesP as $sc) { ?><li <?php  if ($sc->getCollectionID() == $c->getCollectionID()) { ?> class="nav-selected" <?php  } ?>><a href="<?php echo $nh->getLinkToCollection($sc, false, true)?>"><?php echo t($sc->getCollectionName())?></a></li><?php  } ?></ul>
		<br/><div class="ccm-spacer">&nbsp;</div>
		</div>
	
	
	<?php 
		}
} ?>

<?php 
	if (isset($latest_version)){ 
		print Loader::element('dashboard/notification_update', array('latest_version' => $latest_version));
	}
?>

<?php  if(strlen(APP_VERSION)){ ?>
<div id="ccm-dashboard-version">
	<?php echo  t('Version') ?>: <?php echo APP_VERSION ?>
</div>
<?php  } ?>

<div id="ccm-dashboard-content">

	<div style="margin:0px; padding:0px; width:100%; ">
	<?php  if (isset($error)) { ?>
		<?php  
		if ($error instanceof Exception) {
			$_error[] = $error->getMessage();
		} else if ($error instanceof ValidationErrorHelper) { 
			$_error = $error->getList();
		} else {
			$_error = $error;
		}
			?>
			<div class="message error">
			<strong><?php echo t('The following errors occurred when attempting to process your request:')?></strong>
			<ul>
			<?php  foreach($_error as $e) { ?><li><?php echo $e?></li><?php  } ?>
			</ul>
			</div>
		<?php  
	}
	
	if (isset($message)) { ?>
		<div class="message success"><?php echo $message?></div>
	<?php  } ?>
	
	<?php  print $innerContent; ?>
	</div>
	
	<div class="ccm-spacer">&nbsp;</div>

	</div>

</div>

</body>
</html>