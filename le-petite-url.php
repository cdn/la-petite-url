<?php
/*
Plugin Name: le petite url
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
global $wpdb;
global $petite_table;

$petite_table = "le_petite_urls";

add_option("le_petite_url_version", "1.0");
add_option("le_petite_url_use_mobile_style", "yes");
add_option("le_petite_url_link_text", "petite url");
add_option("le_petite_url_permalink_prefix", "");
add_option("le_petite_url_permalink_custom", "/a/");
add_option("le_petite_url_use_lowercase", "yes");
add_option("le_petite_url_use_uppercase", "no");
add_option("le_petite_url_use_numbers", "no");
add_option("le_petite_url_length", "5");

function le_petite_url_check_url($the_petite)
{
	global $wpdb;
	global $petite_table;

	//echo $the_petite;
	$post_query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."$petite_table WHERE petite_url = '".$the_petite."'");
	//echo "SELECT * FROM ".$wpdb->prefix."$petite_table WHERE petite_url = '".$the_petite."'";
	if(count($post_query) > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function le_petite_url_generate_string()
{
	$n = get_option('le_petite_url_length');
	$le_petite_url_chars = "";

	if(get_option('le_petite_url_use_lowercase') == "yes")
	{
		$le_petite_url_chars = $le_petite_url_chars . "abcdefghijklmnopqrstuvwxyz";
	}
	if(get_option('le_petite_url_use_uppercase') == "yes")
	{
		$le_petite_url_chars = $le_petite_url_chars . "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	}
	if(get_option('le_petite_url_use_numbers') == "yes")
	{
		$le_petite_url_chars = $le_petite_url_chars . "0123456789";
	}
	
	for ($s = '', $i = 0, $z = strlen($a = $le_petite_url_chars)-1; $i != $n; $x = rand(0,$z), $s .= $a{$x}, $i++);
	return $s;
}

function le_petite_url_make_url($post)
{
	global $wpdb;
	global $petite_table;
	
	try {
	$post_parent = $wpdb->get_var("SELECT post_parent FROM ".$wpdb->posts." WHERE ID = ".$post."");
	} catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
	
	$post_query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."$petite_table WHERE post_id = ".$post."");
	
	if(count($post_query) == 0)
	{
		$good_url = "no";
		while($good_url == "no")
		{
			$string = le_petite_url_generate_string();
			$post_query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."$petite_table WHERE petite_url = ".$string."");
			if(count($post_query) == 0)
			{
				$good_url = "yes";
				try {
					$wpdb->query("INSERT INTO ".$wpdb->prefix. $petite_table ." VALUES($post_parent,'".mysql_real_escape_string($string)."')");
				}
				catch(Exception $e)
				{
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			}
		}
	}
}

function le_petite_url_do_redirect()
{
	global $wpdb;
	global $petite_table;
	
	$request = $_SERVER['REQUEST_URI'];
	$the_petite = trim($request);
	$the_petite = trim($the_petite,"/");
	
	$le_petite_url_split = spliti('/',$the_petite);
	
	$le_petite_url_use = count($le_petite_url_split) - 1;
	
	if(le_petite_url_check_url($le_petite_url_split[$le_petite_url_use]))
	{
		$post_id = $wpdb->get_var("SELECT post_id FROM $wpdb->prefix".$petite_table." WHERE petite_url = '".$le_petite_url_split[$le_petite_url_use]."'");
		
		$expires = date('D, d M Y G:i:s T',strtotime("+1 week"));

		header("Expires: ".$expires);
		header('Location: '.get_permalink($post_id), true, 302);
	}
	else
	{
		// do stuff like normal
	}
}

function le_petite_url_install()
{
	global $wpdb;
	global $petite_table;
	$url_table = $wpdb->prefix . $petite_table;
	if($wpdb->get_var("SHOW TABLES LIKE '$url_table'") != $url_table) 
	{
		$sql = "CREATE TABLE  `" . $url_table . "` (
				`post_id` INT NOT NULL ,
				`petite_url` VARCHAR( 255 ) NOT NULL ,
				PRIMARY KEY (  `post_id` )
				);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

}

function le_petite_url_sidebar() {

    if( function_exists( 'add_meta_box' )) {
  		add_meta_box( 'le_petite_url_box', __( 'le petite url', 'le_petite_url_textdomain' ), 'le_petite_url_generate_sidebar', 'post', 'side' );
  		add_meta_box( 'le_petite_url_box', __( 'le petite url', 'le_petite_url_textdomain' ), 'le_petite_url_generate_sidebar', 'page', 'side' );
	}
	else
	{
		add_action('dbx_post_sidebar', 'le_petite_url_generate_sidebar' );
    	add_action('dbx_page_sidebar', 'le_petite_url_generate_sidebar' );
	}
}

function le_petite_url_generate_sidebar()
{
	global $wp_query;
	global $wpdb;
	global $petite_table;
	$blogurl = get_bloginfo('siteurl');
	$url_table = $wpdb->prefix . $petite_table;
	$post_id = $wpdb->escape($_GET['post']);
	
	$le_petite_url_permalink_prefix = get_option('le_petite_url_permalink_prefix');
	$petite_url = $wpdb->get_var("SELECT petite_url FROM ".$url_table." WHERE post_id = ".$post_id."");
	if($petite_url != "")
	{
		$le_petite_url_permalink = $blogurl;
		if(get_option('le_petite_url_permalink_prefix') != "")
		{
			$le_petite_url_permalink = $le_petite_url_permalink . get_option('le_petite_url_permalink_custom');
		}
		else
		{
			$le_petite_url_permalink = $le_petite_url_permalink . "/";
		}
		$le_petite_url_permalink = $le_petite_url_permalink . $petite_url;
		echo "<p>This post's petite url is: <code><a href='".$le_petite_url_permalink."'>".$petite_url."</a></code>";
		
	}
	else
	{
		echo "<p>This post doesn't seem to have a petite url. To generate one, save the post. The petite url will then appear right where this message is.</p>";
	}
}

function the_petite_url()
{
	global $wp_query;
	global $wpdb;
	global $petite_table;
	$blogurl = get_bloginfo('siteurl');
	$url_table = $wpdb->prefix . $petite_table;
	$post_id = $wp_query->post->ID;
	
	$petite_url = $wpdb->get_var("SELECT petite_url FROM ".$url_table." WHERE post_id = ".$post_id."");
	if($petite_url != "")
	{
		echo $petite_url;
	}
}

function the_petite_url_link()
{
	global $wp_query;
	global $wpdb;
	global $petite_table;
	
	$blogurl = get_bloginfo('siteurl');
	$url_table = $wpdb->prefix . $petite_table;
	$post_id = $wp_query->post->ID;
	$anchor_text = get_option('le_petite_url_link_text');

	$petite_url = $wpdb->get_var("SELECT petite_url FROM ".$url_table." WHERE post_id = ".$post_id."");
	if($petite_url != "")
	{
		$le_petite_url_permalink = $blogurl;
		if(get_option('le_petite_url_permalink_prefix') != "")
		{
			$le_petite_url_permalink = $le_petite_url_permalink . get_option('le_petite_url_permalink_custom');
		}
		else
		{
			$le_petite_url_permalink = $le_petite_url_permalink . "/";
		}
		$le_petite_url_permalink = $le_petite_url_permalink . $petite_url;
		
		echo '<a href="'.$le_petite_url_permalink.'" class="le_petite_url" rel="nofollow" title="shortened permalink">'.htmlspecialchars($anchor_text, ENT_QUOTES, 'UTF-8').'</a>';
	}
}

function le_petite_url_admin_panel()
{
	add_options_page('le petite url Options', 'le petite url', 8, 'le-petite-url/le-petite-url-options.php', 'le_petite_url_settings');
}

function le_petite_url_settings()
{
	require_once('le-petite-url-options.php');
}

register_activation_hook(__FILE__, "le_petite_url_install");

add_action('template_redirect','le_petite_url_do_redirect');
add_action('save_post','le_petite_url_make_url');
add_action('admin_menu', 'le_petite_url_sidebar');
add_action('admin_menu', 'le_petite_url_admin_panel');

?>