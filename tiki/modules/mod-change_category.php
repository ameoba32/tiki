<?php
// $Header: /cvsroot/tikiwiki/tiki/modules/mod-change_category.php,v 1.2 2005-01-22 22:56:13 mose Exp $

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}
global $feature_categories;
// temporary limitation to wiki pages
// params : id (id of parent categ) and shy (show only if page is in categ)
if ($feature_categories == 'y' and isset($_REQUEST['page'])) {
	$id = 0;
	if (isset($module_params['id'])) {
		$id = $module_params['id'];
	}
	if (isset($module_params['shy'])) {
		$notshy = false;
	} else {
		$notshy = true;
	}

	global $categlib; if (!is_object($categlib)) require("lib/categories/categlib.php");
	global $logslib,$page;

	$cat_parent = $categlib->get_category_name($id);
	$cat_type = 'wiki page';
	$cat_objid = $page = $_REQUEST['page'];

	$categs = $categlib->get_child_categories($id);
	$num = count($categs);
	for ($i=0;$i<$num;$i++) {
		$categsid[] = $categs[$i]['categId'];
	}

	if (isset($_REQUEST["modcatid"]) and $_REQUEST["modcatid"] == $id) {
		$cats = $categlib->get_object_categories($cat_type, $cat_objid);
		$catObjectId = $categlib->is_categorized($cat_type, $_REQUEST['page']);
		foreach ($cats as $cs) {
			if (in_array($cs,$categsid)) {
				$query = "delete from `tiki_category_objects` where `catObjectId`=? and `categId`=?";
				$result = $tikilib->query($query,array((int) $catObjectId, (int) $cs));
			}
		}
		$categlib->categorize_page($_REQUEST['page'], $_REQUEST['modcatchange']);
		$logslib->add_log('step',"changed ".$_REQUEST['page']." from $cs to ".$_REQUEST['modcatchange']);
		header('Location: tiki-index.php?page='.urlencode($cat_objid));
		die;
	}

	$incategs = $categlib->get_object_categories($cat_type, $cat_objid);
	
	for ($i=0;$i<$num;$i++) {
		$cid = $categs[$i]['categId'];
		$modcatlist[$cid] = $categs[$i];
		if (in_array($cid,$incategs)) {
			$modcatlist[$cid]['incat'] = 'y';
			$notshy = true;
		} else {
			$modcatlist[$cid]['incat'] = 'n';
		}
	}

	$smarty->assign('showmodule',$notshy);
	$smarty->assign('page',$page);
	$smarty->assign('modname',$changecateg.$id);
	$smarty->assign('modcattitle',sprintf(tra('move %s in %s'),$page,$cat_parent));
	$smarty->assign('modcatlist',$modcatlist);
	$smarty->assign('modcatid',$id);
}
?>
