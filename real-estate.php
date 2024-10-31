<?php
/*
Plugin Name: Real Estate
Plugin URI: http://www.altijdzon.nl/real-estate-plugin/
Description: Real Estate Plugin (REP) is simple and functional, and complements <strong>RealEstate Listings</strong> theme (can be integrated with other themes as well). <a href="admin.php?page=real-estate/real-estate.php">Edit default plugin features here</a>. If you are having any problems or if you need some customization and new features added, read instructions at <strong><a href="http://www.altijdzon.nl/real-estate-plugin/">plugin homepage</a></strong> where you can find information on customization with themes and other plugins. As last resort, send an email to: dom.rep.3000_WITHOUT_THIS_ANTISPAM_@gmail.com
Author: dom-rep
Version: 1.9.4
Author URI: http://www.altijdzon.nl/
*/

/*
    Real Estate - plugin adds a property image gallery and information to Wordpress blog posts
    Copyright (C) 2009 altijdzon.nl (email: dom.rep.3000_WITHOUT_THIS_ANTISPAM_@gmail.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

global $rep_is_listing,$rep_private, $rep_listings_fields, $rep_listings_vars, $rep_listing_types, $rep_property_types, $rep_dir, $rep_post_hash, $rep_white_below_image, $rep_map_tags, $rep_agent_email,$maximages, $rep_footer_link, $rep_nf;

$rep_agent_email = get_option('rep_agent_email');
$rep_listing_types_list = get_option('rep_listing_types_list');
$rep_property_types_list = get_option('rep_property_types_list');
$rep_listings_fields_list = get_option('rep_listings_fields_list');
$rep_convert_currencies = get_option('rep_convert_currencies');
$rep_default_currency = get_option('rep_default_currency');


if(get_option('rep_footer_link'))$rep_footer_link = 1;
else $rep_footer_link = 0;

if(get_option('rep_nf'))$rep_nf = ' rel="nofollow" ';
else $rep_nf = '';


// create custom plugin settings menu
add_action('admin_menu', 'rep_create_menu');

function rep_create_menu() {

	//create new top-level menu
	add_menu_page('Real Estate Plugin Settings', 'Real Estate Settings', 'administrator', __FILE__, 'rep_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_rep_mysettings' );
}


function register_rep_mysettings() {
	//register our settings
	register_setting( 'rep-settings-group', 'rep_agent_email' );
	register_setting( 'rep-settings-group', 'rep_listing_types_list' );
	register_setting( 'rep-settings-group', 'rep_property_types_list' );
	register_setting( 'rep-settings-group', 'rep_listings_fields_list' );
	register_setting( 'rep-settings-group', 'rep_convert_currencies' );
	register_setting( 'rep-settings-group', 'rep_default_currency' );
	register_setting( 'rep-settings-group', 'rep_footer_link' );
	register_setting( 'rep-settings-group', 'rep_nf' );
}

function rep_settings_page() {
	global $rep_footer_link, $rep_nf;
	$rep_nf_checked = ' ';
	$rep_footer_link_checked = ' ';
	if(get_option('rep_nf'))$rep_nf_checked = ' checked="checked"';
	if(get_option('rep_footer_link'))$rep_footer_link_checked = ' checked="checked"';
?>
<div class="wrap">
<h2>Real Estate Plugin</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'rep-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">INSTRUCTIONS</th>
        <td><p>Here you need to define Listing Types (e.g. sold, for sale), Property Types (e.g. apartment, hotel), Listings Fields (e.g. city, price).</p></td>
        </tr>

        <tr valign="top">
        <th scope="row">Agent Email</th>
        <td><input type="text" name="rep_agent_email" value="<?php echo get_option('rep_agent_email'); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Listing Types (each in new line)</th>
        <td><textarea name="rep_listing_types_list" rows="5" cols="30" ><?php echo get_option('rep_listing_types_list'); ?></textarea></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Property Types (each in new line)</th>
        <td><textarea name="rep_property_types_list" rows="10" cols="30" ><?php echo get_option('rep_property_types_list'); ?></textarea></td>
        </tr>

        <tr valign="top">
        <th scope="row">Listings Fields (each in new line)</th>
        <td>
        <p>
        <b>The sequence of the fields defines in which order details are listed.</b> <br />
		<b>However, details MUST start with 'info', 'listing', and 'property' (keep those as top 3 lines below)</b>
		</p>
		<textarea name="rep_listings_fields_list" rows="10" cols="30" ><?php echo get_option('rep_listings_fields_list'); ?></textarea>
		</td>
        </tr>

        <tr valign="top">
        <th scope="row">Convert Currencies</th>
        <td>
        <p>
		<b>1 to show euro and dollar, 0 to show default</b>
		</p>
		<input type="text" name="rep_convert_currencies" value="<?php echo get_option('rep_convert_currencies'); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Default Currency</th>
        <td>
		<input type="text" name="rep_default_currency" value="<?php echo get_option('rep_default_currency'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Spread the word:</th>
        <td><input type="checkbox" name="rep_footer_link" id="rep_footer_link" value="1" <?php echo $rep_footer_link_checked; ?> onclick="document.getElementById('showlink').style.visibility = this.checked ? 'visible' : 'hidden'" />

<?php
	if(get_option('rep_footer_link')){$rep_footer_link = 1;$visible = '';}
	else {$rep_footer_link = 0;$visible = ' style="visibility:hidden;"';}
	echo '<div id="showlink"'.$visible.'>';
?>
        Thank you for being kind and telling your readers that your real estate listings are powered by wordpress plugin. 
<br />
As more and more people use this plugin, the more effort will be put into its further development. 
	</div>
	</td>
        </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php }


$maximages = 20; //maximum number of images in the gallery. 20 is quite enough!
$rep_white_below_image = 0; //If you don't want white space below images (used when shorter image is above default taller one), switch this to 0. 


$rep_listing_types = array_filter(array_unique(array_map('trim',explode('
',$rep_listing_types_list))));
$rep_property_types = array_filter(array_unique(array_map('trim',explode('
',$rep_property_types_list))));
//HERE USE all small letters and underscores _ instead of spaces
function rep_replace_su($text){return str_replace(' ','_',$text);}
function rep_replace_sd($text){return str_replace(' ','-',$text);}
$rep_listings_fields = array_filter(array_unique(array_map('rep_replace_su',array_map('trim',explode('
',strtolower($rep_listings_fields_list))))));



$rep_map_tags = array(); 
//foreach($rep_property_types as $rpt)$rep_map_tags[$rpt] = array($rpt);


//http://codex.wordpress.org/Using_Custom_Fields
$rep_upload_dir = wp_upload_dir();
$rep_dir = trailingslashit($rep_upload_dir['basedir']).'re-listings';

//http://codex.wordpress.org/Plugin_API
//http://codex.wordpress.org/Function_Reference
if(!defined('REP_CWD'))define('REP_CWD', dirname(__FILE__));
include_once(REP_CWD."/resizeimage.inc.php");
include_once(REP_CWD."/contact-form.php");

if(!defined('WP_CONTENT_URL'))define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
$plugin_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));

if(!defined('WP_CONTENT_DIR'))define('WP_CONTENT_DIR', ABSPATH.'wp-content');
$plugin_path = WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__));

$rep_private = '';
for($i=0;$i<20;$i++){$j=$i+1;array_push($rep_listings_fields,'image_'.$j);array_push($rep_listings_fields,'image_alt_'.$j);}
$rep_listings_vars = array();
foreach($rep_listings_fields as $lf) $rep_listings_vars[$lf] = '';

function rep_is_listing(){
	if(isset($_GET['post']))return get_post_meta((int)$_GET['post'], 'rep_islisting', $single = true);
	elseif(isset($_GET['type'])&&$_GET['type']=='listing')return 1;
	else return (int)$_POST["islisting"];
}
$rep_is_listing = rep_is_listing();


function rep_br2nl($string){return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);}

function rep_custom_fields(){
	global $post, $plugin_url, $rep_is_listing,$rep_private, $rep_listing_types, $rep_property_types, $rep_listings_fields, $rep_listings_vars, $rep_dir, $rep_post_hash,$maximages;
	$rep_private = get_post_meta($post->ID, "rep_private", true);
	foreach($rep_listings_fields as $lf) $rep_listings_vars[$lf] = get_post_meta($post->ID, "rep_".$lf, true);
	$rep_post_hash = get_post_meta($post->ID, 'rep_post_hash', true);
	if($rep_post_hash!='');else $rep_post_hash = strtolower(substr(md5(time()),0,5)).'_';
	if($rep_is_listing==1)$checked = ' checked="checked"';
	else $visible = ' style="visibility:hidden;"';
	$rep_output = "<h2>Listing Options</h2><noscript><b>Please <ins><a href=\"http://www.quackit.com/javascript/tutorial/how_to_enable_javascript.cfm\" target=\"_blank\">enable JavaScript</a></ins> to use the property listings option.</b></noscript>
	<script type=\"text/javascript\">
	var form = document.getElementById('post');
	form.enctype = \"multipart/form-data\"; //FireFox, Opera, et al
	form.encoding = \"multipart/form-data\"; //IE5.5
	form.setAttribute('enctype', 'multipart/form-data'); //required for IE6 (is interpreted into \"encType\")
";
	$rep_output .=	'</script>';
	$rep_output .=	'<p><input type="checkbox" name="islisting" id="islisting" value="1" '.$checked.' onclick="document.getElementById(\'showlisting\').style.visibility = this.checked ? \'visible\' : \'hidden\'">listing?
<input type="hidden" name="rep_post_hash" id="rep_post_hash" value="'.$rep_post_hash.'" />
</p>
	';
$rep_output .= '<div id="showlisting"'.$visible.'>';
	$fldstrt = "<div class=\"postbox\"><div class=\"inside\"><p><strong>";
	$fldstop = "</p></div></div>";
	$tabindex = 400;
	$rep_output .= $fldstrt."Private:</strong><br /><textarea name=\"rep_private\" id=\"rep_private\" rows=\"10\" cols=\"60\" tabindex=\"$tabindex\" />$rep_private</textarea>$fldstop";
	foreach($rep_listings_vars as $key => $val){$tabindex++;
		if($key=='listing'||$key=='property'){
			if($key=='listing')$rep_types = $rep_listing_types;
			else $rep_types = $rep_property_types;//'rep_'.$key.'types';
			$rep_output .= $fldstrt.ucwords(str_replace('_', ' ', $key)).":</strong><br />";
			$cbvals = explode(',',$val);
			foreach($rep_types as $type){
				if(@in_array($type,$cbvals))$rep_output .= str_replace("value=\"$type\"", "value=\"$type\" checked=\"checked\"", "<input type=\"radio\" name=\"{$key}[]\" value=\"$type\"  tabindex=\"$tabindex\" />$type");
				else $rep_output .= "<input type=\"radio\" name=\"{$key}[]\" value=\"$type\"  tabindex=\"$tabindex\" />$type";
				$rep_output .= "<br />";
			}
			$rep_output .= "$fldstop";
		}
		elseif($key=='info') $rep_output .= $fldstrt.ucwords(str_replace('_', ' ', $key)).":</strong><br /><textarea name=\"$key\" id=\"$key\" rows=\"10\" cols=\"60\" tabindex=\"$tabindex\" />$val</textarea>$fldstop";
		elseif(!stristr($key, 'image')) $rep_output .= $fldstrt.ucwords(str_replace('_', ' ', $key)).":</strong><br /><input type=\"text\" name=\"$key\" id=\"$key\" value=\"$val\"  size=\"12\"  tabindex=\"$tabindex\" />$fldstop";
	}
	for($i=1;$i<21;$i++){$tabindex++;
		$imagekey = "image_$i";$image = $rep_listings_vars[$imagekey];
		$imagealtkey = "image_alt_$i";$imagealt = $rep_listings_vars[$imagealtkey];
		$rep_output .= $fldstrt.ucwords(str_replace('_', ' ', $imagekey)).":</strong><br />";
$imurl = WP_CONTENT_URL.'/uploads/re-listings';
		if($image)$rep_output .= "<img src=\"$imurl/thumb_$image\" /><input type=\"checkbox\" value=\"$image\" name=\"del_$imagekey\" id=\"del_$imagekey\" tabindex=\"$tabindex\">delete<br />";$tabindex++;
$rep_output .= "		<input type=\"file\" name=\"$imagekey\" id=\"$imagekey\"  tabindex=\"$tabindex\" />
				<input type=\"text\" name=\"$imagealtkey\" id=\"$imagealtkey\" value=\"$imagealt\"  size=\"12\"  tabindex=\"$tabindex\" />description";
		$rep_output .= $fldstop;
	}
$rep_output .= '<p><strong>DON\'T FORGET TO SAVE OR PUBLISH YOUR LISTING AFTER SELECTING PHOTOS FOR UPLOAD!</strong></p></div>';
	echo $rep_output;
}
add_filter('edit_form_advanced', 'rep_custom_fields');//if($rep_is_listing==1)

// http://www.samburdge.co.uk/wordpress/wp-plugins-and-custom-fields
function rep_add_custom_fields($id){
	global $post,$plugin_url,$rep_is_listing,$rep_private,$rep_listings_fields,$rep_listings_vars, $rep_dir, $rep_post_hash, $rep_upload_dir,$maximages;
	foreach($rep_listings_fields as $k)$rep_listings_vars[$k] = trim(stripslashes($_POST[$k]));

	if($rep_is_listing==1) {
		delete_post_meta($id, 'rep_islisting');
		add_post_meta($id, 'rep_islisting', 1);
		delete_post_meta($id, 'rep_post_hash');
		$rep_post_hash = trim(stripslashes($_POST['rep_post_hash']));
		add_post_meta($id, 'rep_post_hash', $rep_post_hash);
		delete_post_meta($id, "rep_private");
		add_post_meta($id, "rep_private", $_POST['rep_private']);
		foreach($rep_listings_vars as $k => $v){
			if(!stristr($k, 'image')){
				delete_post_meta($id, "rep_".$k);
				if($v){
					if($k=='listing'||$k=='property'){if(is_array($_POST[$k]))$cbvals = @implode(',',$_POST[$k]);else $cbvals = $v;}
					else $cbvals = $v;
					add_post_meta($id, "rep_".$k, $cbvals);
				}
			}
		}
		if (!current_user_can('upload_files'))
			wp_die(__('You do not have permission to upload files.'));
		for($i=1;$i<21;$i++){
			$imagekey = "image_$i";$image = $_FILES[$imagekey]["name"];
			$imagealtkey = "image_alt_$i";$imagealt = $rep_listings_vars[$imagealtkey];
			$deleteimage = "del_$imagekey";
				delete_post_meta($id, "rep_".$imagealtkey);
				if($imagealt!='')add_post_meta($id, "rep_".$imagealtkey, $imagealt);

			$ft=strtolower($image);
			preg_match("/(.*)\.(.*)/", $ft, $matches);
			$extension = $matches[2];
			$filetypes = array("jpg", "jpeg", "bmp", "gif", "png");

			$error = '';
			if(!file_exists($rep_dir) && @!mkdir($rep_dir, 0777))
				$error = sprintf(__("The userphoto upload content directory does not exist and could not be created. Please ensure that you have write permissions for the '%s' directory. Did you put slash at the beginning of the upload path in Misc. settings? It should be a path relative to the WordPress root directory. <code>wp_upload_dir()</code> returned:<br /> <code style='white-space:pre'>%s</code>", 'user-photo'), $rep_dir, print_r($rep_upload_dir, true));

			$oldimage = get_post_meta($id, "rep_".$imagekey, true);
			$oldimage = $_POST[$deleteimage];
			if(!$error&&in_array($extension, $filetypes)&&($_FILES[$imagekey]["error"]===0)){
				delete_post_meta($id, "rep_".$imagekey);
				//@unlink("$rep_dir/$oldimage");
				//@unlink("$rep_dir/thumb_$oldimage");
				$tmpimg = "$rep_post_hash$imagekey.$extension";
				$imagepath = "$rep_dir/$tmpimg";
				move_uploaded_file($_FILES[$imagekey]["tmp_name"],$imagepath);
				if(file_exists($imagepath))smart_resize_image($imagepath, 300, 300, true, "$rep_dir/thumb_$tmpimg", false, false );
				add_post_meta($id, "rep_".$imagekey, $tmpimg);
			}
			elseif(!$error&&$_POST[$deleteimage]!=''){
				delete_post_meta($id, "rep_".$imagekey);
				@unlink("$rep_dir/$oldimage");
				@unlink("$rep_dir/thumb_$oldimage");
			}
		}
	}
	elseif(isset($_POST["islisting"])&&$_POST['islisting']!=1) delete_post_meta($id, 'rep_islisting');
}
add_action('edit_post', 'rep_add_custom_fields');
add_action('publish_post', 'rep_add_custom_fields');
add_action('save_post', 'rep_add_custom_fields');
//add_action('edit_page_form', 'rep_add_custom_fields');

function rep_delete_custom_fields($id){
	global $post,$plugin_url,$rep_is_listing,$rep_private,$rep_listings_fields,$rep_listings_vars, $rep_dir, $rep_upload_dir;
	foreach($rep_listings_fields as $lf) $rep_listings_vars[$lf] = get_post_meta($post->ID, "rep_".$lf, true);
	if($rep_is_listing==1) {
		//delete_post_meta($id, 'rep_islisting');
		foreach($rep_listings_vars as $k => $v){
			if(!stristr($k, 'image')){
				//delete_post_meta($id, "rep_".$k);
			}
		}
		for($i=1;$i<21;$i++){
			$imagekey = "image_$i";$image = get_post_meta($post->ID, "rep_".$imagekey, true);
			$imagealtkey = "image_alt_$i";
			//delete_post_meta($id, "rep_".$imagealtkey);
			//$ft=strtolower($image);
			//delete_post_meta($id, "rep_".$imagekey);
			@unlink("$rep_dir/$image");
			@unlink("$rep_dir/thumb_$image");
		}
	}else ;//delete_post_meta($id, 'rep_islisting');
}
add_action('delete_post', 'rep_delete_custom_fields');

function rep_modify_columns($defaults){
	global $rep_is_listing,$rep_private;
	if($_GET['type']=='listing'||$rep_is_listing==1){
		unset($defaults['author']);
		$defaults['title'] = 'Title';
		$defaults['image'] = 'Image';
		$defaults['details'] = 'Listing Info';
	}
	else {
		$defaults['image'] = 'Image';
		$defaults['details'] = 'Listing Info';
	}
	return $defaults;
}
add_filter('manage_posts_columns', 'rep_modify_columns');

function rep_custom_column($column_name, $post_id){
	global $wpdb, $plugin_url, $rep_listing_types, $rep_property_types, $rep_listings_vars,$rep_listings_fields, $rep_dir, $rep_is_listing,$rep_private;
	$preview = 0;
	if($column_name=='details'){
		foreach($rep_listings_fields as $lf){
			if($preview<11&&get_post_meta($post_id, "rep_".$lf, true)!=''&&get_post_meta($post_id, "rep_islisting", true)==1)
				if($lf!='info')echo "<span style=\"font-size:80%;\"><strong>$lf:</strong> ".get_post_meta($post_id, "rep_".$lf, true)."</span><br />";
			$preview ++;
		}
	}
	if($column_name=='image'){
		$image = get_post_meta($post_id, 'rep_image_1', true);
		if($image){
			$imurl = WP_CONTENT_URL.'/uploads/re-listings';
			echo "<a href=\"$imurl/{$image}\"><img src=\"$imurl/thumb_$image\" /></a><br />";
		}
	}
}
add_action('manage_posts_custom_column', 'rep_custom_column', 10, 2);

//http://www.packtpub.com/article/managing-posts-with-wordpress-plugin
function rep_posts_join($join){
	global $wpdb;
	if($_GET['type']=='listing') $join .= " JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id AND ($wpdb->postmeta.meta_key = 'rep_islisting')";
	return $join;
}
function rep_posts_where($where){
	global $wpdb;
	if(isset($_GET['type'])&&$_GET['type']=='listing'){
		$where .= " AND ID IN (SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = 'rep_islisting') ";// AND post_type = 'post'
	}
	return $where;
}
function rep_restrict_manage_posts(){
	global $wp_version;
	if($wp_version>="2.5"){
	?>
	<select name='type' id='type' class='postform'>
		<option value="" <?php if($_GET['type']!='listing') echo 'selected="selected"' ?>><?php _e('View posts') ?></option>
		<option value="listing" <?php if(isset($_GET['type'])&&$_GET['type']=='listing') echo 'selected="selected"' ?>><?php _e('View listings only'); ?></option>
	</select>
<?php
	} 
}
function rep_edit_mods(){
	add_filter('posts_join', 'rep_posts_join');
	add_filter('posts_where', 'rep_posts_where');
	add_action('restrict_manage_posts', 'rep_restrict_manage_posts');
}
add_action('load-edit.php', 'rep_edit_mods');

function rep_stem($word){
	return substr($word,0,-1);//not quite stem but serves the purpose
}

function rep_find_tags($current){
	global $wpdb, $rep_property_types; $rep_map_tags;
	if(!isset($rep_map_tags[$current])){
		$rep_map_tags[$current] = array();
		$current_stem = rep_stem($current);
		$current_parts = array_map('rep_replace_sd',array_map('rep_stem',array_map('trim',explode(' and ',strtolower($current)))));
		$results = mysql_query("SELECT $wpdb->terms.slug, $wpdb->term_taxonomy.term_taxonomy_id FROM $wpdb->term_taxonomy, $wpdb->terms WHERE $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id AND $wpdb->term_taxonomy.taxonomy = 'post_tag' AND $wpdb->terms.slug LIKE '$current_stem%' LIMIT 1") or die(mysql_error());
		if($results&&mysql_num_rows($results)>0){
			while($row = mysql_fetch_assoc($results)){
				extract($row, EXTR_PREFIX_ALL, "p");
				array_push($rep_map_tags[$current],$p_slug);
			}
		}else{
			foreach($current_parts as $part){
				$results = mysql_query("SELECT $wpdb->terms.slug, $wpdb->term_taxonomy.term_taxonomy_id FROM $wpdb->term_taxonomy, $wpdb->terms WHERE $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id AND $wpdb->term_taxonomy.taxonomy = 'post_tag' AND ($wpdb->terms.slug LIKE '$part' OR $wpdb->terms.slug LIKE '{$part}_') LIMIT 1 ") or die(mysql_error());
				while($row = mysql_fetch_assoc($results)){
					extract($row, EXTR_PREFIX_ALL, "p");
					array_push($rep_map_tags[$current],$p_slug);
				}
			}
		}
	}
	return $rep_map_tags[$current];
}

if($rep_convert_currencies){
	@include(REP_CWD.'/currency-converter/currency-converter.php');
}
function rep_shorten($str){
	$l = 24;
	if(strlen($str)>$l){
		$str = substr($str,0,$l);
		$str = substr($str,0,strrpos($str,' '));
		//$str .= "...";
	}
	return $str;
}
function rep_theme_modification($content){
	global $wpdb, $post, $user_level, $rep_is_listing,$rep_private, $rep_listing_types, $rep_property_types, $rep_listings_fields, $rep_listings_vars, $rep_dir, $rep_post_hash,$rep_convert_currencies,$rep_default_currency;
	$postid = $post->ID;
	$imurl = WP_CONTENT_URL.'/uploads/re-listings';
	if(get_post_meta($postid, 'rep_islisting', $single = true)==1&&is_single()){
$modification = <<<EOT
$content
			<div class="replisting">
				<div id="repgallery">
EOT;
		foreach($rep_listings_fields as $lf) $rep_listings_vars[$lf] = get_post_meta($postid, "rep_".$lf, true);
		for($i=1;$i<21;$i++){
			$imagekey = "image_$i";$image = $rep_listings_vars[$imagekey];
			$imagealtkey = "image_alt_$i";$imagealt = $rep_listings_vars[$imagealtkey];
			if($i==1&&$image) $modification .= "					<p class=\"repmainp\">
						<img src=\"$imurl/thumb_$image\" id=\"repmainimg\" alt=\"$imagealt\" />
					</p>
					<ul>
";
			if($image) $modification .= "
						<li>
							<a class=\"rep_elements\" href=\"$imurl/$image\" rel=\"lightbox.rep\" title=\"$imagealt\"><img src=\"$imurl/thumb_$image\" alt=\"$imagealt\" title=\"$imagealt\" /></a>
						</li>
";
		}
$modification .= <<<EOT
					</ul>
				</div>
				<div class="fixed"></div>
			</div>
			<div class="repfeatures">
EOT;
		$fldstrt = "
				<div class=\"repfeature\">
					<p class=\"feature\"><span class=\"reptype\">";
		$fldstop = "
					</p>
				</div>";
		$rep_ref = get_post_meta($postid, "rep_ref", true);
		if($rep_ref&&$rep_ref>0);else $rep_ref = $postid;
		$price = $rep_listings_vars['price'];$price2 = '';
		if($rep_convert_currencies){
			if(stristr($rep_listings_vars['price'],'€')||stristr($rep_listings_vars['price'],'EURO')){
				$wp_re_rate = wp_re_cur_con('EUR-USD');
				$price = trim(str_ireplace(array('€','EURO',',','.'),'',$rep_listings_vars['price']));
				if($wp_re_rate>0)$price2 = "($ ".number_format(str_replace(array('$',',','.'),'',round($wp_re_rate*$price)), 0, '.', ',').")";else $price2 = '';
				$price = "€ ".number_format(str_replace(array('$',',','.'),'',$price), 0, '.', ',');
			}else{
				$wp_re_rate = wp_re_cur_con('USD-EUR');
				$price = trim(str_ireplace(array('$','USD',',','.'),'',$rep_listings_vars['price']));
				if($wp_re_rate>0)$price2 = "(€ ".number_format(str_replace(array('$',',','.'),'',round($wp_re_rate*$price)), 0, '.', ',').")";else $price2 = '';
				$price = "$ ".number_format(str_replace(array('$',',','.'),'',$price), 0, '.', ',');
			}
		}else {
			$price = trim(str_ireplace(array($rep_default_currency,',','.'),'',$rep_listings_vars['price']));
			$price = "$rep_default_currency ".number_format(str_replace(array($rep_default_currency,',','.'),'',$price), 0, '.', ',');
		}
		$modification .= "$fldstrt Ref:</span> $rep_ref - <b>Price:</b> ".$price." $price2  -- <b>".strtoupper($rep_listings_vars['listing'])."</b> $fldstop";
		foreach($rep_listings_vars as $key => $val){
			if($val!=''&&!stristr($key, 'image')&&$key!='info'&&$key!='property'&&$key!='size_house'&&$key!='size_lot'&&$key!='price') $modification .= $fldstrt.ucwords(str_replace('_', ' ', $key)).":</span> $val $fldstop";
			elseif($val!=''&&$key=='price')$modification .= $fldstrt.ucwords(str_replace('_', ' ', $key)).":</span> $price $price2 $fldstop";
			elseif($val!=''&&$key=='size_house'){
				if(stristr($val,'m2'))$val = trim(str_ireplace('m2','',$val));
				elseif(stristr($val,'ft')){$val = trim(str_ireplace(array('sq. ft.','sq. ft','sq.ft.','sq.ft','sq ft','sqft','sft'),'',$val));$val = $val / 10.7639;}
				elseif(stristr($val,'acre')){$val = trim(str_ireplace(array('acres','acre'),$val));$val = $val / 0.000247105;}
				$modification .= $fldstrt.ucwords(str_replace('_', ' ', $key)).":</span> $val m2 (".round($val*10.7639)." sq ft) $fldstop";
			}
			elseif($val!=''&&$key=='size_lot'){
				if(stristr($val,'m2'))$val = trim(str_ireplace('m2','',$val));
				elseif(stristr($val,'ft')){$val = trim(str_ireplace(array('sq. ft.','sq. ft','sq.ft.','sq.ft','sq ft','sqft','sft'),'',$val));$val = $val / 10.7639;}
				elseif(stristr($val,'acre')){$val = trim(str_ireplace(array('acres','acre'),$val));$val = $val / 0.000247105;}
				$modification .= $fldstrt.ucwords(str_replace('_', ' ', $key)).":</span> $val m2 (".round($val*0.000247105,2)." acres) $fldstop";
			}
			elseif($val!=''&&$key=='info') $modification .= "
				<div class=\"repinfo\"><p>
					".nl2br($val)."</p>
				</div>
				<div class=\"fixed\"></div>
";
		}
		$modification .= rep_contact_form();
		$modification .= "
			</div>";
		return $modification;
	}elseif(get_post_meta($postid, 'rep_islisting', $single = true)==1){
		$image = get_post_meta($postid, 'rep_image_1', $single = true);
$modification = "
$content
			<div class=\"replisting\">
";
		$rep_link = get_permalink();
		$imagealt = get_post_meta($postid, "rep_image_alt_1", true);
$listing = get_post_meta($postid, "rep_listing", true);
$property = get_post_meta($postid, "rep_property", true);
$price = get_post_meta($postid, "rep_price", true);
if($rep_convert_currencies){
	if(stristr($price,'€')||stristr($price,'EURO'))$price = number_format(str_replace(array('€','EURO',',','.'),'',$price), 0, '.', ',').' €';
	else $price = "$ ".number_format(str_replace(array('$',',','.'),'',$price), 0, '.', ',');
}else{
	$price = "$rep_default_currency ".number_format(str_replace(array($rep_default_currency,',','.'),'',$price), 0, '.', ',');
}
$city = get_post_meta($postid, "rep_city", true);
		if($image) $modification .= "<div class=\"imgc\"><a href=\"".$rep_link."\"><img class=\"imgc\" src=\"$imurl/thumb_$image\" alt=\"".get_the_title($postid)."\" title=\"".get_the_title($postid)."\" /></a></div><div class=\"repinfos sltitle\">".rep_shorten(get_the_title($postid))."</div>";
$modification .= "        <div class=\"repinfos\">
                          <ul class=\"repinfos\"><li><a href=\"".get_option('siteurl')."/".get_option('tag_base')."/".strtolower(str_replace(array(' ','---','--'),'-',$city))."/\">$city</a>&nbsp;</li>";//<li>
$parts = rep_find_tags($property);$comma = '';
foreach($parts as $p){
$p = ucwords(str_replace('-',' ',$p));
	//$modification .= "$comma<a href=\"".get_option('siteurl')."/".get_option('tag_base')."/".strtolower(str_replace(' ','-',$p))."/\">$p</a> ";$comma = ', ';
}
$modification .= "<li class=\"repir\">$listing&nbsp; $price&nbsp;</li></ul>
                          </div>
			</div>
";//&nbsp;</li>
		return $modification;
	}else{
		return $content;
	}
}
add_filter('the_content','rep_theme_modification');
add_filter('the_excerpt','rep_theme_modification');

function rep_add_head_style(){
	global $post, $rep_white_below_image;
	$imurl = WP_CONTENT_URL.'/uploads/re-listings';
	$rep_firstimage = '';$imurl.'/thumb_'.get_post_meta($post->ID, 'rep_image_1', $single = true);
	$lightbox_folder = WP_CONTENT_URL.'/plugins/real-estate/fancybox';
if($rep_white_below_image) $imageclear = 'border-left:1px solid #fff;border-right:1px solid #fff;border-bottom:150px solid #fff;';
else $imageclear = '';

echo <<<EOT
<style type="text/css" media="screen">@import url( $lightbox_folder/jquery.fancybox-1.3.4.css );</style>
<style type="text/css">
#repgallery, #repgallery ul, #repgallery ul li, #repgallery ul li a, #repgallery ul li a img {margin:0px;padding:0px;border:0px;}
#repgallery {margin:0px;width:510px;clear:both;margin-left:5px;}
#repgallery p.repmainp {float:left;margin:0px;padding:0px;}
#repgallery ul {list-style:none;padding:0px;margin:0px;position:relative;display:inline; }
#repgallery ul li {list-style:none;display:inline;width:55px;height:55px;float:left;margin:0 5px 5px 0; }
#repgallery ul li a {display:block;width:53px;height:53px;text-decoration:none;border:1px solid #555;}
#repgallery ul li a img {display:block;width:53px;height:53px;border:0;}
#repmainimg {height:225px;width:295px;border:1px solid black;margin:0px;padding:0px;margin-right:5px;}
#repaware {font-size:70%;clear:both;text-align:center;margin-top:20px;position:relative;color:#ccc;}
#repaware a{color:#ccc;}

.sltitle {font-size:0.77em;color:#9a9790;margin:0px;padding:0px;letter-spacing:1px;}
.repfeatures {margin:10px;margin-top:75px;clear:both;}
.repinfos ul{list-style:none;margin:0px;padding:2px;font-size:10px;letter-spacing:1px;}
.repinfos ul li{list-style:none;margin:0px;padding:2px;font-size:10px;}
.post .replisting {padding:9px;height:170px;}
.post .replisting .imgc {height:115px;}
.post .replisting .imgc img{border: 1px solid #555;width:130px;}
.post .contentrep {
	overflow:hidden;
margin:8px;
width:150px;
background:#fff;
border: 1px solid #555;
display:block;
float:left;
}
li.repir {float:right;letter-spacing:0px;}

.reptype {font-weight:bold;}
.repinfo {margin-top:15px;margin-bottom:15px;}
.underline {text-decoration:underline;}

#lightbox{	position: absolute;	left: 0; width: 100%; z-index: 100; text-align: center; line-height: 0;}
#lightbox img{ width: auto; height: auto;}
#lightbox a img{ border: none; }

#outerImageContainer{ position: relative; background-color: #fff; width: 250px; height: 250px; margin: 0 auto; }
#imageContainer{ padding: 10px; }

#loading{ position: absolute; top: 40%; left: 0%; height: 25%; width: 100%; text-align: center; line-height: 0; }
#hoverNav{ position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 10; }
#imageContainer>#hoverNav{ left: 0;}
#hoverNav a{ outline: none;}

#prevLink, #nextLink{ width: 49%; height: 100%; /*  background-image: url(data:image/gif;base64,AAAA); Trick IE into showing hover */ display: block; }
#prevLink { left: 0; float: left;}
#nextLink { right: 0; float: right;}

#imageDataContainer{ font: 10px Verdana, Helvetica, sans-serif; background-color: #fff; margin: 0 auto; line-height: 1.4em; overflow: auto; width: 100%	; }

#imageData{	padding:0 10px; color: #666; }
#imageData #imageDetails{ width: 70%; float: left; text-align: left; }	
#imageData #caption{ font-weight: bold;	}
#imageData #numberDisplay{ display: block; clear: left; padding-bottom: 1.0em;	}			
#imageData #bottomNavClose{ width: 66px; float: right;  padding-bottom: 0.7em; outline: none;}	 	

#overlay{ position: absolute; top: 0; left: 0; z-index: 90; width: 100%; height: 500px; background-color: #555; }

p.tagnav{margin:2px;color:#555;background:#fff;padding:5px;}
a.tagnav{color:#555;}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="$lightbox_folder/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="$lightbox_folder/jquery.easing-1.4.pack.js"></script>
<script type="text/javascript" src="$lightbox_folder/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("a[rel=lightbox.rep]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});
		});
</script>
EOT;
}
add_action('wp_head', 'rep_add_head_style');
//add_action('wp_print_styles', 'rep_add_head_stylesheet');//needs local style because of main image in background
function rep_add_head_stylesheet(){
	$myStyleUrl = REP_CWD . "/style.css";
	$myStyleFile = REP_CWD . "/style.css";
	if(file_exists($myStyleFile)){
		wp_register_style('repStyleSheets', $myStyleUrl);
		wp_enqueue_style('repStyleSheets');
	}
}

function rep_add_footer() {
	global $rep_footer_link, $rep_nf;
	if($rep_footer_link)echo ("<div id=\"repaware\"><a $rep_nf href=\"http://www.altijdzon.nl/real-estate-plugin/\">Real Estate</a> @ <a $rep_nf href=\"http://wordpress.org/extend/plugins/real-estate/\">WordPress</a>.</div>");
}
if(function_exists('get_footer'))add_filter('get_footer', 'rep_add_footer',1);
else add_action('wp_footer', 'rep_add_footer',1);

?>
