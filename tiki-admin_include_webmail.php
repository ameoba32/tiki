<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-admin_include_webmail.php,v 1.5 2004-03-27 21:23:52 mose Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  die("This script cannot be called directly");
}


include_once ("lib/webmail/htmlMimeMail.php");

if (isset($_REQUEST["webmail"])) {
	check_ticket('admin-inc-webmail');
	if (isset($_REQUEST["webmail_view_html"]) && $_REQUEST["webmail_view_html"] == "on") {
		$tikilib->set_preference("webmail_view_html", 'y');

		$smarty->assign('webmail_view_html', 'y');
	} else {
		$tikilib->set_preference("webmail_view_html", 'n');

		$smarty->assign('webmail_view_html', 'n');
	}

	$tikilib->set_preference('webmail_max_attachment', $_REQUEST["webmail_max_attachment"]);
	$smarty->assign('webmail_max_attachment', $_REQUEST["webmail_max_attachment"]);
}
ask_ticket('admin-inc-webmail');
?>
