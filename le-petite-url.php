<?php
/*
Plugin Name: Le Petite URL
Plugin URI: http://philnelson.name/projects/le-petite-url
Description: A personal URL shortener.
Version: 1.0
Author: Phil Nelson
Author URI: http://philnelson.name

Copyright 2009  Phil Nelson  (email : software@extrafuture.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_option("ef_la_petite_url_version", "1.0");

global $wpdb;

$url_table = $wpdb->prefix . "le_petitte_urls";

register_activation_hook(__FILE__, "test_function");
//add_action ( 'init', 'check_for_petite_url', [priority], [accepted_args] );

if(!function_exists('check_for_petite_url'))
{
	function check_for_petite_url()
	{
	
	}
}

if(!function_exists('petite_url'))
{
	function petite_url($post)
	{
	
	}
}

function test_function()
{
	print("hello");
}

if(!function_exists('le_petite_install'))
{
	function le_petite_install()
	{
		global $wpdb;
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
		{
			$sql = "CREATE TABLE  `" . $table_name . "` (
					`post_id` INT NOT NULL ,
					`petite_url` VARCHAR( 255 ) NOT NULL ,
					PRIMARY KEY (  `post_id` )
					) ENGINE = MYISAM";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		else
		{
			echo "yes";
		}
	}
}

?>