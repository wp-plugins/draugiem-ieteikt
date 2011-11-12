<?php
/*
Plugin Name: Ieteikt Draugiem
Plugin URI: http://www.themer.me/ieteikt-draugiem
Description: Plugins pievieno katram rakstam "Ieteikt Draugiem" pogu. English: This plugin automatically adds a "Suggest to your Friends" button from Latvian portal draugiem.lv (similiar to Facebook "Like" button).
Version: 1.2.2
Author URI: http://www.themer.me
*/

function ieteikt_init()
{
add_meta_box("ieteikt", "Ieteikt Draugiem", "ieteikt_post_display", 'page', 'side', 'high');
add_meta_box("ieteikt", "Ieteikt Draugiem", "ieteikt_post_display", 'post', 'side', 'high');
}


function ieteikt($content) {
global $post;
$url = urlencode(get_permalink($post->ID));
$title = get_the_title($post->ID);
$blogName = get_bloginfo('name');
$display = get_post_meta($post->ID, 'ieteikt_draugiem', true);
$override = get_option('ieteikt-draugiem');

if($override == 1 && $display == 0) $display = 1;
if($display == 1) return $content.'<iframe height="20" width="84" frameborder="0" src="http://www.draugiem.lv/say/ext/like.php?title='.urlencode($title).'&amp;url='.$url.'&amp;titlePrefix='.urlencode($blogName).'"></iframe>';


}


add_filter('the_content', 'ieteikt');


/*--------------------------------------------------------------------------*/
/*	Single Post:
/*--------------------------------------------------------------------------*/

function ieteikt_post_display()
{
	wp_nonce_field(plugin_basename(__FILE__), 'ieteikt_check');
	$ieteikt = get_post_meta($post_id, 'ieteikt_draugiem', true);
	if(isset($ieteikt) && !empty($ieteikt) && $ieteikt == 0) $ieteikt = "";
	else $ieteikt = 'checked="checked"';
	?>
	<br />
	<input type="checkbox" echo <?php echo $ieteikt; ?> name="ieteikt_draugiem"/>]
	<label for="ieteikt_draugiem"> Rādīt <em>"Ieteikt Draugiem"</em> pogu ?<label>
	
	<br />
	<?php
}

function ieteikt_post_save($post_id)
{

	# Nonce and Autosave verification 
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!wp_verify_nonce($_POST['ieteikt_check'], plugin_basename(__FILE__))) return;


	if($_POST['post_type'] == 'page' || $_POST['post_type'] == 'post')
	{
		if(!current_user_can('edit_page', $post_id) && !current_user_can('edit_post', $post_id)) return;
	}
	else
	{
		return;
	}

	
	$checked = ($_POST['ieteikt_draugiem']) ? 1 : 0;
	update_post_meta($post_id, 'ieteikt_draugiem', $checked);
}

add_action('save_post', 'ieteikt_post_save');
add_action('add_meta_boxes', 'ieteikt_init');

/*--------------------------------------------------------------------------*/
/*	Admin Panel Settings
/*--------------------------------------------------------------------------*/

add_option('ieteikt-draugiem', 0);

if(is_admin())
{
		add_action('admin_menu', 'ieteikt_admin_page');
		function ieteikt_admin_page()
		{
		add_plugins_page('Ieteikt Draugiem', "Ieteikt Draugiem", 'manage_options', 'ieteikt-draugiem', 'ieteikt_admin_options_page');
		}
	function ieteikt_admin_options_page()
	{		

		if(isset($_POST['_ieteikt-global'])){
			if(!wp_verify_nonce($_POST['_ieteikt-global'], 'ieteikt-draugiem')){
			 wp_die("Kluda ar Wordpress, megini velreiz, vai ari mekle @Methemer twitterii...");
			}
			else {
			$ieteikt = (isset($_POST['ieteikt_global'])) ? 1 : 0;
			update_option('ieteikt-draugiem', $ieteikt);
			}
		}
	?>
		<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2> Ieteikt Draugiem </h2><br /><br />
		Gribi iespējas ? Pagaidām ir tikai šis ķeksis:<br />
		<form action="" method="post">			
		<?php
		$options = get_option('ieteikt-draugiem');
		if($options == 1)
		{
			$checked = 'checked="checked"';
		}
		else $checked = NULL;

		?>
		<form action="" method="action">

		<?php wp_nonce_field('ieteikt-draugiem', '_ieteikt-global'); ?>
		
		<input type="checkbox" name="ieteikt_global" <?php echo $checked ?> />
		<label for="ieteikt_global">Rādīt"Ieteikt Draugiem" visur,arī tur, kur neesi atzīmējis "rādīt ieteikt pogu" ?</label>
		<br /><br />
		<input class="button-primary" name="Submit" type="submit" value="Aidā" />

		</form>
		<br /><br /><hr /><br /><br />
		<em>p.s. Vēl tev ir iespēja sekot man Twitterī <a href="http://twitter.com/#!/Methemer">@Methemer</a></em>
		</div>
	<?php
	}


}
/*
# Ieteikt Settings:
	* Include in All Posts
	* Include in All Pages
	* Add my link (Ieteikt Draugiem / http://www.themer.me / Methemer )
	* Ieteikt Draugiem - Ieteikt Pluginu (tweet, facebook, draugiem ieteikt)
	* Say Thank you Link:
	* Click to tell me if you use it
	* [ieteikt-draugiem] shortcode


*/
?>
