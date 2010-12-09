<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

$section = 'mytiki';
require_once ('tiki-setup.php');
include_once ('lib/userprefs/userprefslib.php');
include_once ('lib/imagegals/imagegallib.php');
$access->check_feature('feature_userPreferences');
$access->check_user($user);
$auto_query_args = array('view_user');
if (!isset($_REQUEST["showall"])) $_REQUEST["showall"] = 'n';
$smarty->assign('showall', $_REQUEST["showall"]);
$userwatch = $user;
if (isset($_REQUEST["view_user"])) {
	if ($_REQUEST["view_user"] <> $user) {
		if ($tiki_p_admin == 'y') {
			$userwatch = $_REQUEST["view_user"];
		} else {
			$smarty->assign('errortype', 401);
			$smarty->assign('msg', tra("You do not have permission to view other users data"));
			$smarty->display("error.tpl");
			die;
		}
	} else {
		$userwatch = $user;
	}
}
$smarty->assign('userwatch', $userwatch);
// Upload avatar is processed here
if (isset($_FILES['userfile1']) && is_uploaded_file($_FILES['userfile1']['tmp_name'])) {
	check_ticket('pick-avatar');
	$type = $_FILES['userfile1']['type'];
	$size = $_FILES['userfile1']['size'];
	$name = $_FILES['userfile1']['name'];
	$fp = fopen($_FILES['userfile1']['tmp_name'], "rb");
	$data = fread($fp, filesize($_FILES['userfile1']['tmp_name']));
	fclose($fp);
	
	// Get image parameters
	list($iwidth, $iheight, $itype, $iattr) = getimagesize($_FILES['userfile1']['tmp_name']);	
	$itype = image_type_to_mime_type($itype);
	// Store full-size file gallery image if that is required
	if ($prefs["user_store_file_gallery_picture"] == 'y') {
		$fgImageId = $userprefslib->set_file_gallery_image($userwatch, $name, $size, $itype, $data);
	}
	// Store small avatar
	if (($iwidth == 45 and $iheight <= 45) || ($iwidth <= 45 and $iheight == 45)) {
		$userprefslib->set_user_avatar($userwatch, 'u', '', $name, $size, $itype, $data);
	} else {
		if (function_exists("ImageCreateFromString") && (!strstr($type, "gif"))) {
			$img = imagecreatefromstring($data);
			$size_x = imagesx($img);
			$size_y = imagesy($img);
			if ($size_x > $size_y) $tscale = ((int)$size_x / 45);
			else $tscale = ((int)$size_y / 45);
			$tw = ((int)($size_x / $tscale));
			$ty = ((int)($size_y / $tscale));
			if ($tw > $size_x) $tw = $size_x;
			if ($ty > $size_y) $ty = $size_y;
			if (chkgd2()) {
				$t = imagecreatetruecolor($tw, $ty);
				imagecopyresampled($t, $img, 0, 0, 0, 0, $tw, $ty, $size_x, $size_y);
			} else {
				$t = imagecreate($tw, $ty);
				$imagegallib->ImageCopyResampleBicubic($t, $img, 0, 0, 0, 0, $tw, $ty, $size_x, $size_y);
			}
			// CHECK IF THIS TEMP IS WRITEABLE OR CHANGE THE PATH TO A WRITEABLE DIRECTORY
			$tmpfname = tempnam($prefs['tmpDir'], "TMPIMG");
			imagejpeg($t, $tmpfname);
			// Now read the information
			$fp = fopen($tmpfname, "rb");
			$t_data = fread($fp, filesize($tmpfname));
			fclose($fp);
			unlink($tmpfname);
			$t_type = 'image/jpeg';
			$userprefslib->set_user_avatar($userwatch, 'u', '', $name, $size, $t_type, $t_data);
		} else {
			$userprefslib->set_user_avatar($userwatch, 'u', '', $name, $size, $type, $data);
		}
	}
	/* redirect to prevent re-submit on page reload */
	header('Location: tiki-pick_avatar.php');
	exit;
}
// Get full user picture if it is set
if ($prefs["user_store_file_gallery_picture"] == 'y' && $user_picture_id = $userprefslib->get_user_picture_id($userwatch) ) {
	$smarty->assign('user_picture_id', $user_picture_id);	
}

if (isset($_REQUEST["uselib"])) {
	check_ticket('pick-avatar');
	$userprefslib->set_user_avatar($userwatch, 'l', $_REQUEST["avatar"], '', '', '', '');
}
if (isset($_REQUEST["reset"])) {
	check_ticket('pick-avatar');
	$userprefslib->set_user_avatar($userwatch, '0', '', '', '', '', '');
	$userprefslib->remove_file_gallery_image($userwatch);
}
$avatars = array();
$h = opendir("img/avatars/");
while ($file = readdir($h)) {
	if ($file != '.' && $file != '..' && $file != 'index.php' && substr($file, 0, 1) != "." && $file != "CVS" && $file != "README") {
		$avatars[] = 'img/avatars/' . $file;
	}
}
closedir($h);
$smarty->assign_by_ref('avatars', $avatars);
$smarty->assign('numav', count($avatars));
$smarty->assign('yours', rand(0, count($avatars)));
include_once ('tiki-mytiki_shared.php');
$avatar = $tikilib->get_user_avatar($userwatch);
$smarty->assign('avatar', $avatar);
ask_ticket('pick-avatar');
$smarty->assign('mid', 'tiki-pick_avatar.tpl');
$smarty->display("tiki.tpl");
