<?php
/**
 * BuddyPress Follow Button
 *
 * @package BP-Follow
 * @subpackage Widgets
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
/**
 * Add a "BuddyPress Follow Button" widget to user's posts
 *
 * @subpackage Widgets
 */
 
 class BP_Follow_Button_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'BP_Follow_Button_Widget', 
			__( 'BuddyPress Follow Button', 'widget_bp_follow_button' ), array( 'description' => __( 'Display the follow button if on a singular post', 'BP_Follow_Button_Widget' ), ) 
		);
	}
	
	public function widget( $args, $instance ) {
		$user_id = get_the_author_meta('ID' );
		//Hide the widget if the currently logged-in author is on his or her own posts
 		if ( bp_loggedin_user_id() === $user_id ) {
			return false;
		}
		//Show the widget if the author of the currently displayed post has the member type Brand, Famous Person, Organization, Millionaire's Digest, or Government
		if ( ! bp_has_member_type( $user_id, 'brand' ) && ( ! bp_has_member_type( $user_id, 'famous-person' ) && ( ! bp_has_member_type( $user_id, 'organization' ) && ( ! bp_has_member_type( $user_id, 'millionaires-digest' ) && ( ! bp_has_member_type( $user_id, 'government' ) ) ) ) ) ) {
			return;
		}
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			echo millionairesdigest_follow_get_add_follow_button( $args );
			echo $args['after_widget'];
		}
	
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<?php
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}
function bp_follow_button_load_widget() {
	register_widget( 'BP_Follow_Button_Widget' );
}
add_action( 'widgets_init', 'bp_follow_button_load_widget' );
