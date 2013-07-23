<?php
/**
 * Plugin Name: 7L Tab Widget
 * Description: Widget tab for top post ricent command and post
 * Version: 0.1
 * Author: dika
 * Author URI: http://tujuhlevel.com
 */


add_action( 'widgets_init', 'fu_7l_tab_widget' );
add_action( 'wp_enqueue_scripts', 'SL_add_stylesheet' );
add_action('init', 'load_scripts_sl_tab_widget');


//load color picer
function load_scripts_sl_tab_widget() {
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
}



//function add style
function  SL_add_stylesheet() {
        // Respects SSL, Style.css is relative to the current file
     wp_register_style( 'prefix-style', plugins_url('7l_tab_widget.css', __FILE__) );
     wp_enqueue_style( 'prefix-style' );
 }


function fu_7l_tab_widget() {
	register_widget( 'SL_Tab_Widget' );
}

class SL_Tab_Widget extends WP_Widget {

	function SL_Tab_Widget() {
		$widget_ops = array( 'classname' => 'sl-tab-widget', 'description' => __('show new post,show new comment and populer post ', 'tab-widget') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sl-tab-widget' );
		
		$this->WP_Widget( 'sl-tab-widget', __('7L Tab Widget', 'widget'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title_post = apply_filters('widget_title', $instance['title_post'] );
		$count_post = $instance['count_post'];
		$title_comment = apply_filters('widget_title', $instance['title_comment'] );
		$count_comment = $instance['count_comment'];
		$color_tab_bg = $instance['color_tab_bg'];
		$color_tab_bt = $instance['color_tab_bt'];
		$width_img = $instance['width_img'];
		$height_img = $instance['height_img'];
		$title_populer_post = $instance['title_populer_post'];
		$disebel_Imgae = $instance['disebel_Imgae'];
		$height_tab = $instance['height_tab'];
		$options['limit']	= $instance[ 'number' ];
		$options['range']	= $instance['timeline'];
		$options['post_type'] = $instance['post_type'];
		 
		?>
		
		<style>
		.tabs label {
				background: <?php echo $color_tab_bg; ?>;
				background: -moz-linear-gradient(top, <?php echo $color_tab_bg; ?> 0%, <?php echo $color_tab_bt; ?> 100%);
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#<?php echo $color_tab_bg; ?>), color-stop(100%,#4e8c8a));
				background: -webkit-linear-gradient(top, <?php echo $color_tab_bg; ?> 0%,<?php echo $color_tab_bt; ?> 100%);
				background: -o-linear-gradient(top, <?php echo $color_tab_bg; ?> 0%,<?php echo $color_tab_bt; ?> 100%);
				background: -ms-linear-gradient(top, <?php echo $color_tab_bg; ?> 0%,<?php echo $color_tab_bt; ?> 100%);
				background: linear-gradient(top, <?php echo $color_tab_bg; ?> 0%,<?php echo $color_tab_bt; ?> 100%);
		}
		
		.tabs input:hover + label {
			background: <?php echo $color_tab_bg; ?>;
		}
		.content-tab {
			height: <?php echo $height_tab; ?>px;
		}
		<?php if($disebel_Imgae == 1) { ?>
		.sl_title_div{
			width:100%;
		}
		<?php } ?>
		</style>
		
		<?php

		echo $before_widget;
		echo "<div class=\"tabs\">";
		echo ' <input id="tab-1" type="radio" name="radio-set" class="tab-selector-1" checked="checked" />';
		echo ' <label for="tab-1" class="tab-label-1">'.$title_post.'</label>';
		echo ' <input id="tab-2" type="radio" name="radio-set" class="tab-selector-2"  />';
		echo ' <label for="tab-2" class="tab-label-2">'.$title_comment .'</label>';
		 if(function_exists("wmp_get_popular")) {
		echo ' <input id="tab-3" type="radio" name="radio-set" class="tab-selector-3"  />';
		echo ' <label for="tab-3" class="tab-label-3">'.$title_populer_post .'</label>';
		}
		echo "<div class=\"clear-shadow\"></div>";
		echo '<div class="content-tab">';
		echo "<div  class=\"content-tab-1\">";
		echo "<ul>";
		$args = array( 'numberposts' => $count_post );
		$recent_posts = wp_get_recent_posts( $args );
		foreach( $recent_posts as $recent ){
			
			
			echo '<li>';
			if($disebel_Imgae != 1) {
			//var_dump($recent);
			$first_img = "";
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $recent['post_content'], $matc);
			//var_dump($matc);
			
			$first_img = $matc [1] [0];
			
			echo '<div class="sl_img_div">';
			
			if(!empty($first_img)){
			echo '<img width="'.$width_img.'" height="'.$height_img.'"  src="'.$first_img.'"  alt="'.esc_attr($recent["post_title"]).'" />' ;
			
			}elseif($img_post = get_the_post_thumbnail($recent["ID"], array($width_img,$height_img) )) 
			echo $img_post ;
			else
			echo '<img width="'.$width_img.'" height="'.$height_img.'"  src="'.plugins_url( 'img/default-thumb.gif' , __FILE__ ).'"  alt="'.esc_attr($recent["post_title"]).'" />' ;
			
			echo '</div>';
			}
			
			echo '<div class="sl_title_div"><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a>';
			echo "<p>". get_the_time(get_option('date_format'),$recent["ID"])."</p>";
			//na_content_limit(30,"[...]");
			echo '</div></li> ';
		}
	   echo "</ul></div>";
	   
		echo "<div  class=\"content-tab-2\">";
		echo "<ul>";
		$args = array(
			'status' => 'approve',
			'number' => $count_commen
		);
		$comments = get_comments($args);
		foreach($comments as $comment) :
			echo '<li>';
			if($disebel_Imgae != 1) {
			echo '<div class="sl_img_div">'.get_avatar( $comment->comment_author_email, $width_img ).'</div>';
			}
			echo '<div class="sl_title_div">'.$comment->comment_author . '<br />' . $comment->comment_content .'</div>';
			echo '</li>';
		endforeach;
		echo "</ul> </div>";
	    if(function_exists("wmp_get_popular")) {
		echo "<div  class=\"content-tab-3\">";
	    $posts = wmp_get_popular( $options );
		
		if ( $defaults['title'] ) echo $before_title . $defaults['title'] . $after_title;
		echo '<ul>';
		global $post;
		foreach ( $posts as $post ):
			setup_postdata( $post );
			echo "<li>";
			if($disebel_Imgae != 1) {
			
			//var_dump($recent);
			$first_img = "";
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matc);
			//var_dump($matc);
			
			$first_img = $matc [1] [0];
			
			echo '<div class="sl_img_div">';
			
			if($attachments){
				echo '<img width="'.$width_img.'" height="'.$height_img.'"  src="'.$first_img.'"  alt="'.esc_attr($recent["post_title"]).'" />' ;
			}elseif($img_post = get_the_post_thumbnail($post->ID, array($width_img,$height_img) ))
			{
				echo $img_post ;
			}
			else
			{
				echo '<img src="'.plugins_url( 'img/default-thumb.gif' , __FILE__ ).'"  alt="'.esc_attr($recent["post_title"]).'" />' ;
			}
			
			echo '</div>';
			}
			echo '<div class="sl_title_div">';
			?>
			<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
			<?php
			echo "<p>". get_the_date()."</p>";
			//na_content_limit(30,"[...]");
			echo '</div></li>';
		endforeach;
		echo '</ul>';		
	   echo "</div>";
	   }
	   echo  "</div>"; //end content div class
	   echo  "</div>"; //end tab div class
		
		
		echo $after_widget;
	}

	//Update the widget 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title_post'] = strip_tags( $new_instance['title_post'] );
		//filter numori 
		if(is_numeric($new_instance['count_post']))
			$instance['count_post'] = $new_instance['count_post'];
		else
			$instance['count_post'] = $old_instance['count_post'];
			
		//Strip tags from title and name to remove HTML 
		$instance['title_comment'] = strip_tags( $new_instance['title_comment'] );
		//filter numori 
		if(is_numeric($new_instance['count_comment']))
			$instance['count_comment'] = $new_instance['count_comment'];
		else
			$instance['count_comment'] = $old_instance['count_comment'];
			
			
		if(is_numeric($new_instance['height_img']))
			$instance['height_img'] = $new_instance['height_img'];
		else
			$instance['height_img'] = $old_instance['height_img'];	
			
		if(is_numeric($new_instance['width_img']))
			$instance['width_img'] = $new_instance['width_img'];
		else
			$instance['width_img'] = $old_instance['width_img'];
			

		$instance['color_tab_bg'] = $new_instance['color_tab_bg'];
		$instance['color_tab_bt'] = $new_instance['color_tab_bt'];
		
		if ( is_numeric(  $new_instance[ 'number' ] ) )
			$instance['number'] = $new_instance[ 'number' ];
		else
			$instance['number'] = $old_instance[ 'number' ];
		
		if ( isset( $new_instance[ 'post_type' ] ) )
			$instance['post_type'] = $new_instance[ 'post_type' ];
		else
			$instance['post_type'] = $old_instance[ 'post_type' ];

		if ( isset( $new_instance[ 'timeline' ]) )
			$instance['timeline'] = $new_instance[ 'timeline' ];
		else
			$instance['timeline'] = $old_instance[ 'timeline' ];
			
		if ( isset( $new_instance[ 'disebel_Imgae' ]) )
			$instance['disebel_Imgae'] = $new_instance[ 'disebel_Imgae' ];
		else
			$instance['disebel_Imgae'] = 0;

		if ( isset( $new_instance[ 'title_populer_post' ]) )
			$instance['title_populer_post'] = $new_instance[ 'title_populer_post' ];
		else
			$instance['title_populer_post'] = $old_instance[ 'title_populer_post' ];


		if ( is_numeric($new_instance[ 'height_tab' ] ) )
			$instance['height_tab'] = $new_instance[ 'height_tab' ];
		else
			$instance['height_tab'] = $old_instance[ 'height_tab' ];

		
		

		return $instance;
	}

	
	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title_post' => __('New Post', 'tab-widget'),
								   'count_post' => 5 ,
								   'title_comment' => __('Comment', 'tab-widget') ,
								   'count_comment' => 5,
								   'color_tab_bg' => '#5ba4a4' ,
								   'color_tab_bt' => '#4e8c8a',
								   'width_img' => '100',
								   'height_img' => '100',
								   'number' => '5',
								   'post_type' => 'all',
								   'timeline' => 'all_time',
								   'title_populer_post' => 'Populer',
								   'height_tab' => '528', 
								   'disebel_Imgae' => '0', );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		
		<h3>Recent Post</h3>
		<p>
			<label for="<?php echo $this->get_field_id( 'title_post' ); ?>">Title Tag Post</label>
			<input id="<?php echo $this->get_field_id( 'title_post' ); ?>" name="<?php echo $this->get_field_name( 'title_post' ); ?>" value="<?php echo $instance['title_post']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count_post' ); ?>">Max Number Show Post</label>
			<select id="<?php echo $this->get_field_id( 'count_post' ); ?>" name="<?php echo $this->get_field_name( 'count_post' ); ?>" style="width:100%;"  >
			  <option <?php if($instance['count_post'] == 5 ) echo "selected"; ?>  selectid value="5">5</option>
			  <option <?php if($instance['count_post'] == 15 ) echo "selected"; ?> value="10">10</option>
			  <option <?php if($instance['count_post'] == 10 ) echo "selected"; ?> value="15">15</option>
			  <option <?php if($instance['count_post'] == 20 ) echo "selected"; ?> value="20">20</option>
			  <option <?php if($instance['count_post'] == 25 ) echo "selected"; ?> value="25">25</option>
			  <option <?php if($instance['count_post'] == 30 ) echo "selected"; ?> value="30">30</option>
			</select> 
		</p>

		<h3>Recent Comment</h3>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title_comment' ); ?>">Title Tag Comment</label>
			<input id="<?php echo $this->get_field_id( 'title_comment' ); ?>" name="<?php echo $this->get_field_name( 'title_comment' ); ?>" value="<?php echo $instance['title_comment']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count_comment' ); ?>">Max Number Show Commant</label>
			<select id="<?php echo $this->get_field_id( 'count_comment' ); ?>" name="<?php echo $this->get_field_name( 'count_comment' ); ?>" style="width:100%;"  >
			  <option <?php if($instance['count_comment'] == 5 ) echo "selected"; ?>  selectid value="5">5</option>
			  <option <?php if($instance['count_comment'] == 15 ) echo "selected"; ?> value="10">10</option>
			  <option <?php if($instance['count_comment'] == 10 ) echo "selected"; ?> value="15">15</option>
			  <option <?php if($instance['count_comment'] == 20 ) echo "selected"; ?> value="20">20</option>
			  <option <?php if($instance['count_comment'] == 25 ) echo "selected"; ?> value="25">25</option>
			  <option <?php if($instance['count_comment'] == 30 ) echo "selected"; ?> value="30">30</option>
			</select> 
		</p>
		
		<h3>Poluler Post</h3>
		<?php if(function_exists("wmp_get_popular")) { ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title_populer_post' ); ?>">Title Tag Populer</label><br />
			<input id="<?php echo $this->get_field_id( 'title_populer_post' ); ?>" name="<?php echo $this->get_field_name( 'title_populer_post' ); ?>" type="text" value="<?php echo $instance['title_populer_post']; ?>" style="width:100%;">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of posts to show:</label><br />
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $instance['number']; ?>" size="3">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>">Choose post type:</label><br />
			<select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
				<option value="all">All post types</option>
				<?php
				$post_types = get_post_types( array( 'public' => true ), 'names' );
				foreach ($post_types as $post_type ) {
					// Exclude attachments
					if ( $post_type == 'attachment' ) continue;
					$instance['post_type'] == $post_type ? $sel = " selected" : $sel = "";
					echo '<option value="' . $post_type . '"' . $sel . '>' . $post_type . '</option>';
				}
				?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'timeline' ); ?>">Timeline:</label><br />
			<select id="<?php echo $this->get_field_id( 'timeline' ); ?>" name="<?php echo $this->get_field_name( 'timeline' ); ?>">
				<option value="all_time"<?php if ( $instance['timeline'] == 'all_time' ) echo "selected"; ?>>All time</option>
				<option value="monthly"<?php if ( $instance['timeline'] == 'monthly' ) echo "selected"; ?>>Past month</option>
				<option value="weekly"<?php if ( $instance['timeline'] == 'weekly' ) echo "selected"; ?>>Past week</option>
				<option value="daily"<?php if ( $instance['timeline'] == 'daily' ) echo "selected"; ?>>Today</option>
			</select>
		</p>
		
		<?php } else { ?>
		<p>Please install plugin <a href="http://wordpress.org/plugins/wp-most-popular/" target="_blank">WP Most Popular</a> , to use this feature </p>
		<?php } ?>
		
		<h3>Tab Color Setting</h3>
			
						<label for="<?php echo $this->get_field_id( 'color_tab_bg' ); ?>">Gradasi Top Color</label>
						<input type="text" id="<?php echo $this->get_field_id( 'color_tab_bg' ); ?>" value="<?php echo $instance['color_tab_bg']; ?>" name="<?php echo $this->get_field_name( 'color_tab_bg' ); ?>" />
						<div class="cw-color-picker" rel="<?php echo $this->get_field_id('color_tab_bg'); ?>"></div>
						
						<label for="<?php echo $this->get_field_id( 'color_tab_bt' ); ?>">Gradasi Bottom Color</label>
						<input type="text" id="<?php echo $this->get_field_id( 'color_tab_bt' ); ?>" value="<?php echo $instance['color_tab_bt']; ?>" name="<?php echo $this->get_field_name( 'color_tab_bt' ); ?>" />
						<div class="cw-color-picker1" rel="<?php echo $this->get_field_id('color_tab_bt'); ?>"></div>
						
		<h3>Image Setting</h3>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'disebel_Imgae' ); ?>">Disebel Image</label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'disebel_Imgae' ); ?>" name="<?php echo $this->get_field_name( 'disebel_Imgae' ); ?>" value="1" <?php if($instance['disebel_Imgae'] == 1) echo 'checked';  ?>  />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'width_img' ); ?>">Width Image</label>
			<input id="<?php echo $this->get_field_id( 'width_img' ); ?>" name="<?php echo $this->get_field_name( 'width_img' ); ?>" value="<?php echo $instance['width_img']; ?>" style="width:50%;" /> px
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'height_img' ); ?>">height Image</label>
			<input id="<?php echo $this->get_field_id( 'height_img' ); ?>" name="<?php echo $this->get_field_name( 'height_img' ); ?>" value="<?php echo $instance['height_img']; ?>" style="width:50%;" /> px
		</p>
		
		<h3>Height tab content</h3>
		<p>
			<label for="<?php echo $this->get_field_id( 'height_tab' ); ?>">Height tab</label>
			<input id="<?php echo $this->get_field_id( 'height_tab' ); ?>" name="<?php echo $this->get_field_name( 'height_tab' ); ?>" value="<?php echo $instance['height_tab']; ?>" style="width:50%;" /> px
		</p>
		
		<script type="text/javascript">
		jQuery(document).ready(function()
		{
			// colorpicker field
			jQuery('.cw-color-picker').each(function(){
				var $this = jQuery(this),
					id = $this.attr('rel');
		 
				$this.farbtastic('#' + id);
			});
			// colorpicker field
			jQuery('.cw-color-picker1').each(function(){
				var $this = jQuery(this),
					id = $this.attr('rel');
		 
				$this.farbtastic('#' + id);
			});
		});
				
		

		</script>

	<?php
	}
}


function na_content_limit($max_char, $more_link_text = '&nbsp;', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content);

   if (strlen($_GET['p']) > 0) {
      echo "<p>";
      echo $content;
      echo "&nbsp;<a href='";
      the_permalink();
      echo "'>".__(" [..]")." &rarr;</a>";
      echo "</p>";
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        echo "<p>";
        echo $content;
        echo " ";
        echo "&nbsp;<a href='";
        the_permalink();
        echo "'>".$more_link_text."</a>";
        echo "</p>";
   }
   else {
      echo "<p>";
      echo $content;
      echo "&nbsp;<a href='";
      the_permalink();
      echo "'>".__(" [..]")." &rarr;</a>";
      echo "</p>";
   }
}

?>