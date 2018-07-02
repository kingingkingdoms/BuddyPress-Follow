<?php
/**
 * BP Total Followers Widget
 *
 * @package BP-Follow
 * @subpackage Widgets
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
/**
 * Add a "BuddyPress Total Followers" widget for the displayed user
 *
 * @subpackage Widgets
 */
/* BuddyPress Total Followers Count Widget */
class Total_Follower_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array('classname' => 'Total_Follower_Widget', 'description' => 'Display the total number of followers a displayed user has on their profile as a widget.' );
		$this->WP_Widget('Total_Follower_Widget', 'BuddyPress Total Followers', $widget_ops);
	}
	function widget($args, $instance) {
		// PART 1: Extracting the arguments + getting the values
		extract($args, EXTR_SKIP);
		
		$title = empty( $instance['title'] ) ? ' ' : apply_filters('widget_title', $instance['title']);
		$subject_single = __( 'person' , 'Total_Follower_Widget' );
		$subject_plural = __( 'people' , 'Total_Follower_Widget' );
		$number = bpfw_format_count( $count );
		if ( ! function_exists( 'bpfw_format_count' ) ) {
			function bpfw_format_count( $number ) {
				$precision = 2;
				if ( $number >= 1000 && $number < 1000000 ) {
					$formatted = number_format( $number / 1000, $precision ) . 'K';
				} else
				if( $number >= 1000000 && $number < 1000000000 ) {
					$formatted = number_format( $number / 1000000, $precision ) . 'M';
				} else
				if ( $number >= 1000000000 ) {
					$formatted = number_format( $number / 1000000000, $precision ) . 'B';
				} else {
					$formatted = $number; //Number is less than 1000
				}
				$formatted = str_replace( '.00', '', $formatted );
				return $formatted;
			}
		}
		echo (isset($before_widget)?$before_widget:'');
		
		//PART 2: The title and output
		if (!empty($title) )
			echo $before_title . $title . $after_title;
			$count = bp_follow_total_follow_counts( array(
				'user_id' =>bp_displayed_user_id() ) );
		if( $count['followers'] == 0 ) {
			echo '<p/>' . $count['followers'] . '&nbsp' . $subject_plural . '<p/>';
		}
		if( $count['followers'] == 1 ) {
			echo '<p/>' . $count['followers'] . '&nbsp' . $subject_single . '<p/>';
		}
		if( $count['followers'] > 1 ) {
			$number = bpfw_format_count( $count['followers'] );
			echo '<p/>' . $number . '&nbsp' . $subject_plural . '<p/>';
		}
		echo (isset($after_widget)?$after_widget:'');
	}
	
	//PART 3: The backend form and settings
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Total Followers' ) );
		$title = $instance['title'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
			   name="<?php echo $this->get_field_name('title'); ?>" type="text"
               value="<?php echo attribute_escape($title); ?>" />
			</label>
		</p>
	<?php
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['text'] = $new_instance['text'];
		return $instance;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Total_Follower_Widget");') );
