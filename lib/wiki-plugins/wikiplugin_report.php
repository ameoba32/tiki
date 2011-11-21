<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_report_info() {
	return array(
		'name' => tra('Report'),
		'documentation' => 'Report',
		'description' => tra('Build a report, and store it in a wiki page'),
		'prefs' => array( 'wikiplugin_report' ),
		'body' => tra('The wiki syntax report settings'),
		'icon' => 'pics/icons/mime/zip.png',
		'params' => array(
			'view' => array(
				'name' => tra('Report View'),
				'description' => tra('Report Plugin View'),
				'required' => true,
				'default' => 'sheet',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Sheet'), 'value' => 'sheet'), 
					array('text' => tra('Chart'), 'value' => 'chart')
				)
			),
			'name' => array(
				'name' => tra('Report Name'),
				'description' => tra('Report Plugin Name, sometimes used headings and reference'),
				'required' => true,
				'default' => 'Report Type',
			),
		),
	);
}

function wikiplugin_report( $data, $params ) {
	global $tikilib,$headerlib;
	static $report = 0;
	++$report;
	$i = $report;
	
	$params = array_merge(array(
		"view"=> "sheet",
		"name"=> ""
	), $params);
	
	extract ($params,EXTR_SKIP);
	
	if (!empty($data)) {
		$result = "";
		$report = Report_Builder::loadFromWikiSyntax($data);
		
		switch($view) {
			case 'sheet':
				TikiLib::lib("sheet")->setup_jquery_sheet();
				
				$headerlib->add_jq_onready("
					var width = $('#reportPlugin$i').parent().width(); 
					$('#reportPlugin$i')
						.width(width)
						.show()
						.sheet({
							editable: false,
							buildSheet: true
						});
					
				");
				
				$result .= "
					<style>
						#reportPlugin$i {
							display: none;
							width: inherit ! important;
						}
					</style>
					
					<div id='reportPlugin$i'>" 
						. $report->outputSheet($name) . 
					"</div>";
				break;
				
		}
	}
	
	return "~np~" . $result . "~/np~"; 
}
