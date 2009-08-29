<?php
/*
Plugin Name: la petite url
Plugin URI: http://extrafuture.com/projects/la-petite-url
Description: A personal URL shortener.
Version: 1.5
Author: Phil Nelson
Author URI: http://extrafuture.com

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

add_option("le_petite_url_version", "1.5");
add_option("le_petite_url_use_mobile_style", "yes");
add_option("le_petite_url_link_text", "petite url");
add_option("le_petite_url_permalink_prefix", "default");
add_option("le_petite_url_permalink_custom", "/a/");
add_option("le_petite_url_use_lowercase", "yes");
add_option("le_petite_url_use_uppercase", "no");
add_option("le_petite_url_use_numbers", "no");
add_option("le_petite_url_length", "5");
add_option("le_petite_use_short_url", "yes");
add_option("le_petite_url_registered", "no");
add_option("le_petite_url_registered_on", "0");
add_option("extra_future_site_id",md5(get_bloginfo('url')));
add_option("le_petite_use_shortlink", "yes");
add_option("le_petite_url_permalink_domain", "default");
add_option("le_petite_url_domain_custom", "");
add_option("le_petite_url_hide_godaddy","no");

function le_petite_url_check_url($the_petite)
{
	global $wpdb;
	global $petite_table;

	$post_query = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."$petite_table WHERE petite_url = '".$the_petite."'");
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
		exit;
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

function get_la_petite_url_permalink($post_id)
{

	$le_petite_url_permalink_domain = get_option('le_petite_url_permalink_domain');
	$le_petite_url_domain_custom = get_option('le_petite_url_domain_custom');

	if($le_petite_url_permalink_domain != "custom" )
	{
		$blogurl = get_bloginfo('siteurl');
	}
	else
	{
		$blogurl = 'http://'.$le_petite_url_domain_custom;
	}

        $petite_url = get_le_petite_url($post_id);

	if($petite_url != "")
	{
		$le_petite_url_permalink = $blogurl;
		if(get_option('le_petite_url_permalink_prefix') == "custom")
		{
			$le_petite_url_permalink .= get_option('le_petite_url_permalink_custom');
		}
		else
		{
			$le_petite_url_permalink .= "/";
		}
		$le_petite_url_permalink .= $petite_url;

                return $le_petite_url_permalink;
        }
}

function le_petite_url_generate_sidebar()
{
	global $wp_query;
	global $wpdb;
	global $petite_table;

	$url_table = $wpdb->prefix . $petite_table;
	$post_id = $wpdb->escape($_GET['post']);
	
	$petite_url = get_le_petite_url($post_id);
	if($petite_url != "")
	{
		$le_petite_url_permalink = get_la_petite_url_permalink($post_id);
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

	$post_id = $wp_query->post->ID;
	
	$petite_url = get_le_petite_url($post_id);
	if($petite_url != "")
	{
		echo $petite_url;
	}
}

function get_le_petite_url($post_id)
{
	global $wp_query;
	global $wpdb;
	global $petite_table;
	
	$url_table = $wpdb->prefix . $petite_table;

	$petite_url = $wpdb->get_var("SELECT petite_url FROM ".$url_table." WHERE post_id = ".$post_id."");
	if($petite_url != "")
	{
		return $petite_url;
	}
}

function the_petite_url_link()
{
	global $wp_query;
	global $wpdb;

	$post_id = $wp_query->post->ID;
	$anchor_text = get_option('le_petite_url_link_text');

	$petite_url = get_le_petite_url($post_id);
	if($petite_url != "")
	{
		$le_petite_url_permalink = get_la_petite_url_permalink($post_id);
		
		echo '<a href="'.$le_petite_url_permalink.'" class="le_petite_url" rel="nofollow" title="shortened permalink">'.htmlspecialchars($anchor_text, ENT_QUOTES, 'UTF-8').'</a>';
	}
}

function le_petite_url_admin_panel()
{
	add_options_page('la petite url Options', 'la petite url', 8, 'le-petite-url/la-petite-url-options.php', 'le_petite_url_settings');
	if ( current_user_can('edit_posts') && function_exists('add_submenu_page') ) {
		add_filter( 'plugin_action_links', 'le_petite_url_plugin_actions', 10, 2 );
	}
}

function le_petite_url_settings()
{
	require_once('la-petite-url-options.php');
}

function le_petite_url_short_url_header()
{
	if(is_page() || is_single()) {
		global $post;
	
		global $wp_query;
		global $wpdb;

		$post_id = $wp_query->post->ID;
		$anchor_text = get_option('le_petite_url_link_text');
	
		$petite_url = get_le_petite_url($post_id);
		if($petite_url != "")
		{
			$le_petite_url_permalink = get_la_petite_url_permalink($post_id);

			echo '<link rel="shorturl" href="'.$le_petite_url_permalink.'" />';
		}
	
	}
}

function le_petite_url_shortlink_header()
{
	if(is_page() || is_single()) {
		global $post;

		global $wp_query;
		global $wpdb;

		$post_id = $wp_query->post->ID;
		$anchor_text = get_option('le_petite_url_link_text');

		$petite_url = get_le_petite_url($post_id);
		if($petite_url != "")
		{
			$le_petite_url_permalink = get_la_petite_url_permalink($post_id);

			echo '<link rel="shortlink" href="'.$le_petite_url_permalink.'" />';
		}

	}
}

// Thanks to http://wpengineer.com/how-to-improve-wordpress-plugins/ for instructions on adding the Settings link

function le_petite_url_plugin_actions($links, $file)
{
	static $this_plugin;
 
	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);
 
	if( $file == $this_plugin )
	{
		$settings_link = '<a href="index.php?page=le-petite-url/la-petite-url-options.php">' . __('Settings') . '</a>';
		$links = array_merge( array($settings_link), $links); // before other links
	}
	return $links;
}

function le_petite_url_register()
{
	update_option('le_petite_url_registered', "yes");
	update_option('le_petite_url_registered_on', time());
}

function le_petite_url_hide_godaddy()
{
	update_option('le_petite_url_hide_godaddy', "yes");
}

// function adapted from http://www.webcheatsheet.com/PHP/get_current_page_url.php

function le_petite_url_current_page()
{
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

register_activation_hook(__FILE__, "le_petite_url_install");

add_action('template_redirect','le_petite_url_do_redirect');
add_action('save_post','le_petite_url_make_url');
add_action('admin_menu', 'le_petite_url_sidebar');
add_action('wp_head', 'le_petite_url_short_url_header');
if(is_admin()) 
{
	add_action('admin_menu', 'le_petite_url_admin_panel');
}

?>
