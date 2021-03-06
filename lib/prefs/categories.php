<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_categories_list() 
{
	return array(
		'categories_used_in_tpl' => array(
			'name' => tra('Categories used in templates (TPL)'),
			'description' => tra('Permits to show alternate content depending on category of current object'),
			'type' => 'flag',
			'perspective' => false,
			'help' => 'http://themes.tiki.org/Template+Tricks',
			'dependencies' => array(
				'feature_categories',
			),
			'default' => 'n',
		),
		'categories_cache_refresh_on_object_cat' => array(
			'name' => tra('Category cache gets cleared when an object is categorized/uncategorized'),
			'description' => tra('A cache is used to avoid having to fetch all categories from db every time; this clears the cache when an object is categorized to keep count up to date.'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_categories',
			),
			'default' => 'y',
		),
	);
}
