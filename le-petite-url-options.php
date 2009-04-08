<?php
$le_petite_url_permalink_prefix = get_option('le_petite_url_permalink_prefix');
$le_petite_url_permalink_custom = get_option('le_petite_url_permalink_custom');
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
<h2>le petite url Settings</h2>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<h3>General Settings</h3>
<table class="form-table">
	<tr valign="top">
		<th><label for="le_petite_url_link_text">link text: </label></th>
		<td>
			<input name="le_petite_url_link_text" id="le_petite_url_link_text" type="text" value="<?php echo get_option('le_petite_url_link_text'); ?>" class="regular-text" />
		</td>
	</tr>
	<tr valign="top">
		<th><label for="le_petite_url_length">petite URL length: </label></th>
		<td>
			<input name="le_petite_url_length" id="le_petite_url_length" type="text" value="<?php echo get_option('le_petite_url_length'); ?>" class="regular-text" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">URL generation settings</th>
		<td><fieldset><legend class="hidden">URL generation settings</legend>
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
		</fieldset></td>
	</tr>
</table>
	<h3>Prefix settings</h3>
<table class="form-table">
	<tr>
		<th><label><input name="le_petite_url_permalink_prefix" type="radio" value="" <? if($le_petite_url_permalink_prefix == "") { echo 'checked="checked"'; } ?> class="tog"  /> Default (No Prefix)</label></th>
		<td><code><?=bloginfo('url')?>/<?php echo le_petite_url_generate_string(5) ?></code></td>
	</tr>
	<tr>
		<th>
			<label><input name="le_petite_url_permalink_prefix" id="custom_selection" type="radio" value="custom" class="tog" <? if($le_petite_url_permalink_prefix != "") { echo 'checked="checked"'; } ?>/>Custom Prefix</label>
		</th>
		<td>
			<input name="le_petite_url_permalink_custom" id="le_petite_url_permalink_custom" type="text" value="<?php echo $le_petite_url_permalink_custom; ?>" class="regular-text code" />
		</td>
	</tr>
</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="le_petite_url_permalink_prefix,le_petite_url_permalink_custom,le_petite_url_link_text,le_petite_url_use_lowercase,le_petite_url_use_uppercase,le_petite_url_use_numbers,le_petite_url_length" />
<p class="submit">
	<input type="submit" name="submit" class="button-primary" value="Save Changes" />
</p>
</form>
</div>