<?php
/*
Plugin Name: Ieteikt Draugiem
Plugin URI: http://www.themer.me/ieteikt-draugiem
Description: Plugins pievieno katram rakstam "Ieteikt Draugiem" pogu. English: This plugin automatically adds a "Suggest to your Friends" button from Latvian portal draugiem.lv (similiar to Facebook "Like" button).
Version: 1.0
Author URI: http://www.themer.me
*/




function ieteikt($content) {
global $post;
$url = urlencode(get_permalink($post->ID));
$title = get_the_title($post->ID);
$blogName = get_bloginfo('name');

return $content.'<iframe height="20" width="84" frameborder="0" src="http://www.draugiem.lv/say/ext/like.php?title=.'.$title.'.&amp;url='.$url.'&amp;titlePrefix='.$blogName.'"></iframe>';
}


add_filter('the_content', 'ieteikt');

?>
