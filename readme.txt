=== Plugin Name ===
Contributors: philnelson
Version: 1.02
Plugin URI: http://philnelson.name/projects/le-petite-url/
Tags: permalink, urls, mobile
Requires at least: 2.5
Tested up to: 2.7.1
Stable tag: trunk
Author: Phil Nelson
Author URI: http://philnelson.name

le petite url is a personal URL shortener, allowing the user to provide simple, easy-to-remember and easy-to-say URLs for their blog posts and pages.

== Description ==

le petite url is a personal URL shortener. Using your own Wordpress (2.5+) installation, le petite url allows the user to create shortened, unique, permalinks to their content using a combination of lowercase, uppercase, and numeric characters, which originate from their own domain name. By default le petite url generates a 5-character combination of lowercase letters only, for ease of use in entering on a mobile device or handset.

With version 1.02 le petite url now supports rel="short_url" to aid in shortened URL auto-detection.

== Installation ==

Installing le petite url is a breeze.

1. Upload the `le-petite-url` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php the_petite_url_link(); ?>` in your template where you'd like the shortened URL link to show up.