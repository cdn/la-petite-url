<?php
if($_GET['registered'] == "yes") { le_petite_url_register(); }
if($_GET['hide_godaddy'] == "yes") { le_petite_url_hide_godaddy(); }
$le_petite_url_permalink_prefix = get_option('le_petite_url_permalink_prefix');
$le_petite_url_permalink_custom = get_option('le_petite_url_permalink_custom');
$le_petite_url_permalink_domain = get_option('le_petite_url_permalink_domain');
$le_petite_url_domain_custom = get_option('le_petite_url_domain_custom');
$le_petite_url_hide_godaddy = get_option('le_petite_url_hide_godaddy');

if($le_petite_url_permalink_domain == "custom")
{
	$domain_prefix = 'http://'.$le_petite_url_domain_custom;
}
else
{
	$domain_prefix = get_bloginfo('url');
}

?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready( function($) {
	
	$('#collectList').hide();
	
	$("#collectListTrigger").click(function () {
		$("#collectList").toggle();
	});

});
//]]>
</script>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
<h2>le petite url Settings</h2>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<h3>General Settings</h3>
<table class="form-table">
	<tr valign="top">
		<th><label for="le_petite_url_link_text">Link Text: </label></th>
		<td>
			<input name="le_petite_url_link_text" id="le_petite_url_link_text" type="text" value="<?php echo get_option('le_petite_url_link_text'); ?>" class="regular-text" />
		</td>
	</tr>
	<tr valign="top">
		<th><label for="le_petite_url_length">Petite URL Length: </label></th>
		<td>
			<input name="le_petite_url_length" id="le_petite_url_length" type="text" value="<?php echo get_option('le_petite_url_length'); ?>" class="regular-text" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">URL generation settings</th>
		<td><fieldset><legend class="hidden">URL Generation Settings</legend>
		<label for="le_petite_url_use_lowercase">
		<input name="le_petite_url_use_lowercase" type="checkbox" id="le_petite_url_use_lowercase" value="yes" <?php if(get_option('le_petite_url_use_lowercase') == "yes") { echo 'checked="checked"'; } ?> />
		Use lowercase letters (<code>a-z</code>)</label>
		<br />
		<label for="le_petite_url_use_uppercase">
		<input name="le_petite_url_use_uppercase" type="checkbox" id="le_petite_url_use_uppercase" value="yes" <?php if(get_option('le_petite_url_use_uppercase') == "yes") { echo 'checked="checked"'; } ?> />
		Use uppercase letters (<code>A-Z</code>)</label>
		<br />
		<label for="le_petite_url_use_numbers">
		<input name="le_petite_url_use_numbers" type="checkbox" id="le_petite_url_use_numbers" value="yes" <?php if(get_option('le_petite_url_use_numbers') == "yes") { echo 'checked="checked"'; } ?> />
		Use numbers (<code>0-9</code>)</label>
		</fieldset></td>
	</tr>
	<tr valign="top">
		<th scope="row">Short URL Auto-Detection Settings</th>
		<td><fieldset><legend class="hidden">Short URL Auto-Detection Settings</legend>
		<label for="le_petite_use_short_url">
		<input name="le_petite_use_short_url" type="checkbox" id="le_petite_use_short_url" value="yes" <?php if(get_option('le_petite_use_short_url') == "yes") { echo 'checked="checked"'; } ?> />
		Use <a href="http://sites.google.com/a/snaplog.com/wiki/short_url" title="Learn about short_url: the short_url wiki">short_url</a></label>
		<br>
		<label for="le_petite_use_shortlink">
		<input name="le_petite_use_shortlink" type="checkbox" id="le_petite_use_shortlink" value="yes" <?php if(get_option('le_petite_use_shortlink') == "yes") { echo 'checked="checked"'; } ?> />
		Use <a href="http://microformats.org/wiki/rel-shortlink" title="Learn about rel=shortlink">shortlink</a></label>

		</fieldset></td>
	</tr>
</table>

<h3>Domain settings</h3>
<table class="form-table">
	<tr>
		<th><label><input name="le_petite_url_permalink_domain" type="radio" value="default" <?php if($le_petite_url_permalink_domain != "custom") { echo 'checked="checked"'; } ?> class="tog"  /> Default (Same Domain)</label></th>
		<td><code><?php echo bloginfo('url'); ?>/<?php echo le_petite_url_generate_string(5); ?></code></td>
	</tr>
	<tr>
		<th>
			<label><input name="le_petite_url_permalink_domain" type="radio" value="custom" class="tog" <?php if($le_petite_url_permalink_domain == "custom") { echo 'checked="checked"'; } ?>/>Custom Domain</label>
		</th>
		<td>
			http://<input name="le_petite_url_domain_custom" id="le_petite_url_domain_custom" style="width: 264px;" type="text" value="<?php echo $le_petite_url_domain_custom; ?>" class="regular-text code" />
		</td>
	</tr>
</table>

<?php if($le_petite_url_hide_godaddy != "yes") { ?>
<p><strong>Want a shorter domain?</strong> Register one with <a href="http://www.dpbolvw.net/click-3445809-10378406" target="_blank">www.GoDaddy.com </a>
<img src="http://www.awltovhc.com/image-3445809-10378406" width="1" height="1" border="0"/>, for which the developer of this plugin will get a small commission. <a style="font-size: 80%;" href="?page=le-petite-url%2Fla-petite-url-options.php&hide_godaddy=yes" title="hide this note">don't show this note again</a></p>
<?php } ?>

<h3>Prefix settings</h3>
<table class="form-table">
	<tr>
		<th><label><input name="le_petite_url_permalink_prefix" type="radio" value="default" <?php if($le_petite_url_permalink_prefix == "default") { echo 'checked="checked"'; } ?> class="tog"  /> Default (No Prefix)</label></th>
		<td><code><?php echo $domain_prefix; ?>/<?php echo le_petite_url_generate_string(5); ?></code></td>
	</tr>
	<tr>
		<th>
			<label><input name="le_petite_url_permalink_prefix" type="radio" value="custom" class="tog" <?php if($le_petite_url_permalink_prefix != "default") { echo 'checked="checked"'; } ?>/>Custom Prefix</label>
		</th>
		<td>
			<?php echo $domain_prefix; ?>/<input name="le_petite_url_permalink_custom" style="width: 196px;" id="le_petite_url_permalink_custom" type="text" value="<?php echo $le_petite_url_permalink_custom; ?>" class="regular-text code" />
		</td>
	</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="le_petite_url_permalink_prefix,le_petite_url_permalink_custom,le_petite_url_link_text,le_petite_url_use_lowercase,le_petite_url_use_uppercase,le_petite_url_use_numbers,le_petite_url_length,le_petite_url_permalink_domain,le_petite_url_domain_custom,le_petite_use_shortlink" />
<p class="submit">
	<input type="submit" name="submit" class="button-primary" value="Save Changes" />
</p>
</form>
<?php if(get_option('le_petite_url_registered') != "yes") { ?>
<form method="get" action="http://extrafuture.com/tybor/tybor.php">
<h3>Registration Settings</h3>
<table class="form-table">
	<tr valign="top">
		<td><p>Registering your plugin will help us make better software. EXTRA FUTURE <strong>does not collect any identifiable information</strong>, such as your name or blog URL. If you'd like to see exactly what we do collect, <a href="#collectList" id="collectListTrigger">you can see the list</a>.</p>
		<div id="collectList" style="margin-left: 15px;">
			<h4>Information Collected:</h4>
			<ul>
				<li>the PHP version</li>
				<li>a list of currently-enabled PHP extensions</li>
				<li>the Apache version</li>
				<li>The version of this plugin</li>
				<li>The current time</li>
			</ul>
		</div>
		</td>
	</tr>
</table>
<?php

$apache_version = "unknown";
if(function_exists(apache_get_version)) { $apache_version = apache_get_version(); }
$php_version = "unknown";
if(function_exists(phpversion)) { $php_version = phpversion(); }
$php_extensions = "unknown";
if(function_exists(get_loaded_extensions)) { $php_extensions = serialize(get_loaded_extensions()); }

?>
<input type="hidden" name="ext" value='<?php echo $php_extensions; ?>' />
<input type="hidden" name="app" value="la petite url" />
<input type="hidden" name="v" value="<?php echo get_option('le_petite_url_version'); ?>" />
<input type="hidden" name="id" value="<?php echo get_option('extra_future_site_id'); ?>" />
<input type="hidden" name="php" value='<?php echo $php_version; ?>' />
<input type="hidden" name="apache" value='<?php echo $apache_version; ?>' />
<input type="hidden" name="back_to" value='<?php echo le_petite_url_current_page(); ?>' />
<p class="submit">
	<input type="submit" name="submit" class="button-primary" value="Register" />
</p>
</form>
<?php } else { ?>
<p>You registered this plugin on <?php echo date('d F Y',get_option('le_petite_url_registered_on')); ?>. Thanks!</p>
<?php } ?>
</div>