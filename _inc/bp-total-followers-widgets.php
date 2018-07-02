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
class Total_Followers_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array('classname' => 'Total_Followers_Widget', 'description' => 'Display the total number of followers a displayed user has on their profile as a widget.' );
		$this->WP_Widget('Total_Followers_Widget', 'BuddyPress Total Followers', $widget_ops);
	}
	function widget($args, $instance) {
		// PART 1: Extracting the arguments + getting the values
		extract($args, EXTR_SKIP);
		$title = empty( $instance['title'] ) ? ' ' : apply_filters('widget_title', $instance['title']);
		$subject_single = __( 'person' , 'Total_Followers_Widget' );
		$subject_plural = __( 'people' , 'Total_Followers_Widget' );
		// Before widget code, if any
		echo (isset($before_widget)?$before_widget:'');
		// PART 2: The title and the text output
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
			echo '<p/>' . $count['followers'] . '&nbsp' . $subject_plural . '<p/>';
		}
		// After widget code, if any
		echo (isset($after_widget)?$after_widget:'');
	}
	
	public function form( $instance ) {
		// PART 1: Extract the data from the instance variable
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		// PART 2-3: Display the fields
		?>
<!-- PART 2: Widget Title field START -->
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">Title:
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
			   name="<?php echo $this->get_field_name('title'); ?>" type="text"
               value="<?php echo attribute_escape($title); ?>" />
	</label>
</p>
<!-- Widget Title field END -->
<!-- PART 3: Widget Text field START -->

<!-- Widget Text field END -->
<?php
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['text'] = $new_instance['text'];
		return $instance;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Total_Followers_Widget");') );
