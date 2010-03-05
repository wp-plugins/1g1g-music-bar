<?php
/*  Copyright 2010  cynic  (email : 1g1g.founder@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Plugin Name:  1g1g music bar widget
Plugin URI:   http://www.1g1g.com/
Description:  A widget of 1g1g music bar.
Version:      0.1
Author:       cynic
Author URI:   http://www.1g1g.com/	
*/

	function music_bar_1g1g_widget($args, $widget_args = 1) {
		
		extract( $args, EXTR_SKIP );
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		$options = get_option('music_bar_1g1g_widget');
		if ( !isset($options[$number]) ) 
			return;

		$title = $options[$number]['title'];
		$bgcolor = $options[$number]['bgcolor'];
		$textcolor = $options[$number]['textcolor'];
		$framecolor = $options[$number]['framecolor'];
			
		echo $before_widget; 
		if ( ! defined( 'WP_CONTENT_URL' ) )
      		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
		if ( ! defined( 'WP_CONTENT_DIR' ) )
		    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
		if ( ! defined( 'WP_PLUGIN_URL' ) )
		    define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
		if ( ! defined( 'WP_PLUGIN_DIR' ) )
      		define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
      	$mbpath = WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)); ?>
		<table id="controller_1g1g" cellspacing="4px" cellpadding="0" border="0" style="table-layout:fixed;width:100%;height:24px;cursor:pointer;background-color:<?php echo $bgcolor; ?>;border:solid 1px <?php echo $framecolor; ?>;"> 
			<tr>
				<td id="playPauseBtn_1g1g" style="width:16px;height:16px;background-image:url('<?php echo $mbpath; ?>/1g1gasset/play-pause-next.gif');cursor:pointer;background-position:0px 0px;overflow:hidden;">
				</td>
				<td id="nextBtn_1g1g" style="width:16px;height:16px;background-image:url('<?php echo $mbpath; ?>/1g1gasset/play-pause-next.gif');background-position:0px -64px;cursor:pointer;overflow:hidden;">
				</td>
				<td valign="middle" id="displayText_1g1g" style="font-size:12px; line-height:12px; color: <?php echo $textcolor; ?>;white-space:nowrap;overflow:hidden;">
					<?php echo $title; ?>
				</td>
			</tr>
		</table>
		<script type="text/javascript" src="<?php echo $mbpath.'/1g1gasset/controller.js'; ?>"></script>
								
	<?php echo $after_widget;
	
	}
	
	
	function music_bar_1g1g_widget_control($widget_args) {
	
		global $wp_registered_widgets;
		static $updated = false;
	
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );			
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		$options = get_option('music_bar_1g1g_widget');
		
		if ( !is_array($options) )	
			$options = array();
	
		if ( !$updated && !empty($_POST['sidebar']) ) {
		
			$sidebar = (string) $_POST['sidebar'];	
			$sidebars_widgets = wp_get_sidebars_widgets();
			
			if ( isset($sidebars_widgets[$sidebar]) )
				$this_sidebar =& $sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();
	
			foreach ( (array) $this_sidebar as $_widget_id ) {
				if ( 'music_bar_1g1g_widget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
					if ( !in_array( "music_bar_1g1g_widget-$widget_number", $_POST['widget-id'] ) ) 
						unset($options[$widget_number]);
				}
			}
	
			foreach ( (array) $_POST['music_bar_1g1g_widget'] as $widget_number => $music_bar_1g1g_widget ) {
				$title = strip_tags(stripslashes($music_bar_1g1g_widget['title_value']));
				$bgcolor = $music_bar_1g1g_widget['bgcolor_value'];
				$framecolor = $music_bar_1g1g_widget['framecolor_value'];
				$textcolor = $music_bar_1g1g_widget['textcolor_value'];
				$options[$widget_number] = compact( 'title', 'textcolor', 'bgcolor', 'framecolor' );
			}
	
			update_option('music_bar_1g1g_widget', $options);
			$updated = true;
		}
	
		if ( -1 == $number ) {
			$title = '开始听歌';
			$textcolor = '#ffffff';
			$bgcolor = '#5a82b5';
			$framecolor = '#444444';
			$number = '%i%';
			
		} else { 
			$title = attribute_escape($options[$number]['title']);
			$textcolor = $options[$number]['textcolor'];
			$bgcolor = $options[$number]['bgcolor'];
			$framecolor = $options[$number]['framecolor'];
		}
		
	?>
	<table cellspacing="10px" cellpadding="0" border="0" >
	<tr>
		<td align="right" >初始文字</td>
		<td ><input id="title_value_<?php echo $number; ?>" name="music_bar_1g1g_widget[<?php echo $number; ?>][title_value]" type="text" value="<?=$title?>" /></td>
	</tr>
	<tr>
		<td align="right" >文字颜色</td>
		<td ><input id="textcolor_value_<?php echo $number; ?>" name="music_bar_1g1g_widget[<?php echo $number; ?>][textcolor_value]" type="text" value="<?=$textcolor?>" /></td>
	</tr>
	<tr>
		<td align="right" >背景颜色</td>
		<td ><input id="bgcolor_value_<?php echo $number; ?>" name="music_bar_1g1g_widget[<?php echo $number; ?>][bgcolor_value]" type="text" value="<?=$bgcolor?>" /></td>
	</tr>
	<tr>
		<td align="right" >边框颜色</td>
		<td ><input id="framecolor_value_<?php echo $number; ?>" name="music_bar_1g1g_widget[<?php echo $number; ?>][framecolor_value]" type="text" value="<?=$framecolor?>" /></td>
	</tr>
	</table>
    <input type="hidden" name="music_bar_1g1g_widget[<?php echo $number; ?>][submit]" value="1" />
    
	<?php
	}
	
	
	function music_bar_1g1g_widget_register() {
		if ( !$options = get_option('music_bar_1g1g_widget') )
			$options = array();
		$widget_ops = array('classname' => 'music_bar_1g1g_widget', 'description' => __('Music bar widget from 1g1g (亦歌音乐栏)'));
		$control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'music_bar_1g1g_widget');
		$name = __('Music bar from 1g1g (亦歌音乐栏)');
	
		$id = false;
		
		foreach ( (array) array_keys($options) as $o ) {
				
			$id = "music_bar_1g1g_widget-$o";
			wp_register_sidebar_widget($id, $name, 'music_bar_1g1g_widget', $widget_ops, array( 'number' => $o ));
			wp_register_widget_control($id, $name, 'music_bar_1g1g_widget_control', $control_ops, array( 'number' => $o ));
		}
		
		if ( !$id ) {
			wp_register_sidebar_widget( 'music_bar_1g1g_widget-1', $name, 'music_bar_1g1g_widget', $widget_ops, array( 'number' => -1 ) );
			wp_register_widget_control( 'music_bar_1g1g_widget-1', $name, 'music_bar_1g1g_widget_control', $control_ops, array( 'number' => -1 ) );
		}
	}

add_action('init', music_bar_1g1g_widget_register, 1);

?>