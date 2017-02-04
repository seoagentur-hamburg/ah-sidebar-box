<?php
/*
Plugin Name: AH Sidebar Box
Plugin URI: https://andreas-hecht.com/ah-sidebar-box/
Description: Creates a new and simple to use widget that adds a sidebar box with recent posts, last comments, categories, popular posts, a tag cloud and the archives to the sidebar.
Version: 1.1.0
Author:      Andreas Hecht
Author URI:  https://andreas-hecht.com
License:     GPL2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  ah_sidebar
Domain Path: /languages
*/

/**
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Registers our Widget.
 */
add_action( 'widgets_init', function(){
	register_widget( 'AH_Sidebar_Box' );
});



/**
 * Include the required files
 */ 
require_once dirname( __FILE__ ) . '/includes/recent-comments.php';
require_once dirname( __FILE__ ) . '/includes/get-first-image.php';



/**
 * Loads the Textdomain for the german translation
 */
function ah_sidebar_box_load_plugin_textdomain() {
    load_plugin_textdomain( 'ah_sidebar', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'ah_sidebar_box_load_plugin_textdomain' );


/**
 * Create the widget
 */ 
if ( !class_exists( 'AH_Sidebar_Box' ) ) {

class AH_Sidebar_Box extends WP_Widget {

/**
 * Register widget with WordPress.
 */
	public function __construct() {
		parent::__construct(
	 		'ah_sidebar_box', // Base ID
			'AH Sidebar Box', // Name
			array( 'description' => __( 'Adds a Sidebar Box with recent posts, last comments, categories, popular posts, a tag cloud and the archives to the sidebar. No Options.', 'ah_sidebar' ), ) // Args
		);

     
/**
 * Registers Stylesheets and JavaScript
*/
    if ( !function_exists( 'ah_register_script_style' ) ) {

		function ah_register_script_style() {

			wp_register_script( 'ah_sidebar_box', plugins_url( '/public/js/aside-script.js', __FILE__ ), array( 'jquery' ) );
			wp_register_style( 'ah_sidebar_box', plugins_url('/public/css/aside-style.css', __FILE__) );
            wp_enqueue_style('font-awesome', '//opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css');
			wp_enqueue_script('ah_sidebar_box');
			wp_enqueue_style('ah_sidebar_box');
}

		// Adding the javascript and css only if widget in use
			if ( is_active_widget( false, false, $this->id_base, true ) ) {

				add_action( 'wp_enqueue_scripts', 'ah_register_script_style' );	
			}
	   }
    }


    /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

?>
<div id="sidebarbox">
		<ul id="tabMenu">
			<li class="famous selected" title="<?php  _e(' Famous Posts', 'ah_sidebar');?>"><i class="fa fa-heart" aria-hidden="true"></i></li>
			<li class="commentz" title="<?php  _e(' Last Comments', 'ah_sidebar');?>"><i class="fa fa-comments" aria-hidden="true"></i></li>
			<li class="posts" title="<?php  _e(' Recent Posts', 'ah_sidebar');?>"><i class="fa fa-clock-o" aria-hidden="true"></i></li>
			<li class="category" title="<?php  _e(' Categories', 'ah_sidebar');?>"><i class="fa fa-folder-open" aria-hidden="true"></i></li>
			<li class="random" title="<?php  _e(' Tag Cloud', 'ah_sidebar');?>"><i class="fa fa-tags" aria-hidden="true"></i></li>
            <li class="archiveslist" title="<?php  _e(' Archives', 'ah_sidebar');?>"><i class="fa fa-archive" aria-hidden="true"></i></li>
		</ul>
		<div class="boxBody">
	  	<div id="famous" class="show">
				<h5 class="tabmenu_header">
				<?php  _e(' Famous Posts', 'ah_sidebar');?>
				</h5>
				<ul id="popular-comments">
					<?php
							$pop = new WP_Query('showposts=5&ignore_sticky_posts=1&orderby=comment_count');	
							while ($pop->have_posts()) : $pop->the_post(); ?>
					<li>
						<a href="<?php the_permalink();?>" title="<?php the_title();?>">
							<?php evolution_post_thumbnails(); ?>
						</a>
						<span>
							<a href="<?php the_permalink();?>" title="<?php the_title();?>">
							<?php the_title();?>
							</a>
						</span>
						<p>
							Posted by
							<strong>
							<?php the_author() ?>
							</strong> on the
							<?php the_time('F jS, Y') ?> with
							<?php comments_popup_link('No Comments;', '1 Comment', '% Comments');?>
						</p>
					</li>
					<?php endwhile;?>
					<?php wp_reset_query(); ?>
				</ul>
			</div>		
			<div id="commentzz">
				<h5 class="tabmenu_header">
				<?php  _e(' Last Comments', 'ah_sidebar');?>
				</h5>
				<ul class="wet_recent_comments">
					<?php evolution_recent_comments();?>
				</ul>
			</div>
			<div id="posts">
				<h5 class="tabmenu_header">
				<?php  _e(' Recent Posts', 'ah_sidebar');?>
				</h5>
				<ul class="recent_articles">
					<?php
							$evo = new WP_Query('showposts=5&ignore_sticky_posts=1');	
							while ($evo->have_posts()) : $evo->the_post(); ?>
						<li class="clearfix">
						<a href="<?php the_permalink();?>" title="<?php the_title();?>">
							<?php evolution_post_thumbnails(); ?>
						</a>
						<span class="title-link">
							<a href="<?php the_permalink();?>" title="<?php the_title();?>">
							<?php the_title();?>
							</a>
						</span>
						<p><?php echo substr(get_the_excerpt(), 0,65); ?></p>
							<?php endwhile;?>
					<?php wp_reset_query(); ?>	
				</li>
				</ul>
			</div>
			<div id="category">
				<h5 class="tabmenu_header">
				<?php  _e(' Categories', 'ah_sidebar');?>
				</h5>
				<ul class="category_list">
					<?php wp_list_categories('show_count=1&title_li=&hierarchical = 0');?>
				</ul>
			</div>
			<div id="random">
				<h5 class="tabmenu_header">
				<?php  _e(' Tag Cloud', 'ah_sidebar');?>
				</h5>
				<?php if (function_exists('wp_tag_cloud')) { ?>
				<span id="sidebar-tagcloud">
					<?php wp_tag_cloud('smallest=10&largest=18');?>
				</span>
				<?php }?>
			</div>
            <div id="archiveslist">
				<h5 class="tabmenu_header">
				<?php  _e(' Monthly Archives', 'ah_sidebar');?>
				</h5>
            <ul>
            <?php wp_get_archives('monthly&show_post_count=1', '', 'html', '', '', TRUE); ?>
            </ul>
			</div>
		</div><!-- end div.boxBody -->
		<div class="boxBottom">
		</div>
	</div><!-- end div.box -->
<?php
		echo $after_widget;
	}

   /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}


    /**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );

		$title = strip_tags($instance['title']);
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'ah_sidebar'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	   }
    }
}