<?php
/**
 * MyDemographics 1.0
 * Author: Will Pillar
 * Copyright 2010 Will Pillar. All Rights Reserved
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("admin_user_menu", "mydemographics_admin_nav");
$plugins->add_hook("admin_user_action_handler", "mydemographics_action_handler");

function mydemographics_info() {
    global $lang;

    $lang->load('mydemographics', false, true);

    $version = '1.1';
    $plugin = 'MyDemographics';
    $author = 'Will Pillar';
    $desc = $lang->mydemo_desc;
    return array(
            "name"		=> $plugin,
            "description"	=> $desc,
            "website"           => "http://mybb.willpillar.com",
            "author"		=> $author,
            "authorsite"	=> "http://willpillar.com",
            "version"		=> $version,
            "guid" 		=> "c67203b571849b7c376a1a3171cac5ab",
            "compatibility"     => "165"
    );
}

function mydemographics_admin_nav(&$sub_menu) {
    global $mybb, $lang;

    $lang->load("mydemographics", false, true);

    end($sub_menu);
    $key = (key($sub_menu))+10;

    if(!$key) {
        $key = '70';
    }

    $sub_menu[$key] = array('id' => 'mydemographics', 'title' => $lang->mydemo_title, 'link' => "index.php?module=user-mydemographics");

	return $sub_menu;
}

function mydemographics_action_handler(&$action) {
    $action['mydemographics'] = array('active' => 'mydemographics', 'file' => 'mydemographics.php');

	return $action;
}


