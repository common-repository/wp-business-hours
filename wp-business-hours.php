<?php /*
Plugin Name: WP Business Hours
Plugin URI:  http://www.powerfaq.com/business-hours/
Description: Plugin is to show Business hours, Admin can manage the business hours Weekly. widget and shortcode.
Author: Mejar Singh
Author URI: http://www.powerfaq.com
Version: 1.4
*/
 $wp_business_hours_css ="/**------ Outer div------ **/
div.bHours { float: left; margin-bottom: 5px; width: 100%; }
/**------ Inner Table------ **/
.bHours table { border:1px solid #CCCCCC; }
.bHours tr.grey { background-color: #F0F0F0;}
/** ------ day and time ------  **/
.bh_day { font-weight:bold; color:#333333;}
.bh_time { color:#444444; }
.bHours table td {  border-bottom:1px solid #CCCCCC; border-left:1px solid #CCCCCC; padding:2px;}";
function bHours_install() {
    global $wpdb;
	global $wp_business_hours_css;
	$default_hours = 'YToyOntzOjU6InN0YXJ0IjthOjc6e2k6MDtzOjc6Ijk6MDAgQU0iO2k6MTtzOjc6Ijk6MDAgQU0iO2k6MjtzOjc6Ijk6MDAgQU0iO2k6MztzOjc6Ijk6MDAgQU0iO2k6NDtzOjc6Ijk6MDAgQU0iO2k6NTtzOjY6IkNsb3NlZCI7aTo2O3M6NjoiQ2xvc2VkIjt9czozOiJlbmQiO2E6Nzp7aTowO3M6NzoiNTozMCBQTSI7aToxO3M6NzoiNTozMCBQTSI7aToyO3M6NzoiNTozMCBQTSI7aTozO3M6NzoiNTozMCBQTSI7aTo0O3M6NzoiNTozMCBQTSI7aTo1O3M6MDoiIjtpOjY7czowOiIiO319';
	
	add_option('wp_business_hours', $default_hours);
	add_option('wp_business_hours_css', $wp_business_hours_css);
}

$blogurl = get_option('siteurl');

add_action('admin_menu', 'pluginMenu', 1);
function pluginMenu() { // Add a new submenu under settings
	add_options_page('Business Hours', 'Business Hours', 'activate_plugins', 'wp-business-hours', 'wpbusinesHours');
}

register_activation_hook(__FILE__,'bHours_install');
add_shortcode('WPBUSINESSHOURS', 'show_business_hours');  /* [BUSINESSHOURS] */
function reDays() {
return $days = array("Monday","Tuesday", "Wednesday", "Thursday", "Friday","Saturday", "Sunday"); 
}
function show_business_hours() {
 $days = reDays();
 $arr = unserialize(base64_decode(get_option('wp_business_hours'))); 
  $wp_b_h_css = get_option('wp_business_hours_css');
 ?>
 <style type="text/css">
<?php echo $wp_b_h_css; ?>	 
</style>
<div class="bHours">
 <table cellspacing="0" cellpadding="4" width="100%">
<?php foreach($days as $key => $val) { ?> 
<tr <?php echo $key % 2 == 0?"":'class="grey"';?>>
	<td width="44%" class="bh_day"><?php echo $val;?></td>
	<td width="28%" class="bh_time"><?php echo $arr['start'][$key];?></td>
	<td class="bh_time"><?php echo $arr['end'][$key];?></td>
</tr><?php } ?>
</table></div>
 <?php }
function wpbusinesHours(){
    $days = reDays();
if($_POST) {
	if($_POST['wp_b_h_css']) { 
		if($_POST['bh-default']) {
			global $wp_business_hours_css;
			$businessCss = $wp_business_hours_css;
		} else {
			$businessCss = $_POST['wp_b_h_css'];
		}
		 update_option('wp_business_hours_css',  $businessCss);
	} else {
		$arryp['start'] =$_POST['start'];
		$arryp['end'] =$_POST['end'];
		update_option('wp_business_hours',  base64_encode(serialize($arryp)));
	}
		echo "<div class=\"alert\"><strong>Options updated</strong></div>";
}
$unsArr = get_option('wp_business_hours');
 $arr = unserialize(base64_decode($unsArr)); 
 ?>

 <style type="text/css">.bHours tr.grey { background-color: #F0F0F0;}
.bHours table td { width:33%; border-bottom:1px solid #CCCCCC; padding:2px;}
.bHours table { border:1px solid #CCCCCC;}
.bHours table th {background:#F0F0F0; font-weight:bold;border:1px solid #CCCCCC; }
</style>
<h2>Business Hours</h2>
<form name="business" method="post" action="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=wp-business-hours">
<table style="border:1px solid #CCCCCC;" cellspacing="0" cellpadding="4" style="border-color:#cccccc;">
<tr><td><strong>Days</strong></td><td><strong>Start</strong></td><td><strong>End</strong></td></tr>
<?php foreach($days as $key => $val) { ?>
<tr>
	<td><?php echo $val;?></td>
	<td><input type="text" name="start[<?php echo $key;?>]" value="<?php echo $arr['start'][$key];?>"></td>
	<td><input type="text" name="end[<?php echo $key;?>]" value="<?php echo $arr['end'][$key];?>"></td>
</tr>
<?php } ?>
<tr><td></td><td colspan="2"><input type="submit" name="submit" value="Update"> </td></tr>
</table>
</form>
<p>&nbsp;</p>
<h2>Manage CSS</h2>
<form name="businessCss" method="post" action="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=wp-business-hours">
<table style="border:1px solid #CCCCCC;" cellspacing="0" cellpadding="4" style="border-color:#cccccc;">
<tr><td><textarea rows="12" cols="100" name="wp_b_h_css"><?php echo get_option('wp_business_hours_css'); ?></textarea> </td>
<tr><td><input type="submit" name="bh-default" value="Reset">&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Update"> </td></tr>
</table>
</form>
<h2>== How To Use == </h2>
Use shortcode : <strong>[WPBUSINESSHOURS]</strong><br/>

Function : <strong>&lt;?php echo do_shortcode( '[WPBUSINESSHOURS]' ); ?&gt;</strong><br/>

Widget : <strong>Business Hours</strong>
<?php } 

 /****************** WIDGETS Business Hours   ****************/
add_action( 'widgets_init', 'bhours_widget' );
function bhours_widget() {
	register_widget( 'BHS_Widget' );
}

class BHS_Widget extends WP_Widget {

	function BHS_Widget() {
		$widget_ops = array( 'classname' => 'widget_text', 'description' => __('A widget that displays the Business Hours', 'timing') );
		$control_ops = array( 'id_base' => 'businessh-widget' );
		$this->WP_Widget( 'businessh-widget', __('Business Hours', 'timing'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;
		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		} else { echo $before_title ."Business Hours". $after_title; }
		echo '<div class="textwidget epad">';
		show_business_hours();
		echo "</div>";
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
	
	function form( $instance ) {
		$defaults = array( 'title' => __('', 'timing'), 'show_info' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'timing'); ?></label>
		<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"  />
<?php }
}?>