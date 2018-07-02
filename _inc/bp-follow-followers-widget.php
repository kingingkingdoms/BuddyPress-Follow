<?php
/**
 * BP Followers Widget
 *
 * @package BP-Follow
 * @subpackage Widgets
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
/**
 * Add a "BuddyPress Followers Widget" widget for the displayed user
 *
 * @subpackage Widgets
 */
class BP_Followers_Widget extends WP_Widget {
	function __construct() {
		
		//Set up optional widget args.
		$widget_ops = array(
			'classname'   => 'widget_bp_followers_widget',
			'description' => __( "Show a list of followers that the displayed user has.", 'buddypress-followers' )
		);
		
		//Set up the widget.
		parent::__construct(
			false,
			__( "BuddyPress Followers Widget", 'buddypress-followers' ),
			$widget_ops
		);
	}
	
	//Displays the widget.
	function widget( $args, $instance, $member_args ) {
		if ( empty( $instance['max_users'] ) ) {
			$instance['max_users'] = 18;
		}
		
		//If the displayed user doesn't have any followers, then hide the widget.
		if ( ! $following = bp_get_follower_ids( array( 'user_id' => bp_displayed_user_id() ) ) ) {
			return false;
		}
		
		//If the displayed user has any followers, then show the widget.
		if ( bp_has_members( array(
			'include'         => $following,
			'max'             => $instance['max_users'],
			'populate_extras' => false,
			'type'            => 'active',
		) ) ) {
			do_action( 'bp_before_followers_widget' );
			echo $args['before_widget'];
			echo $args['before_title']
				. $instance['title']
				. $args['after_title'];
		?>
			<div class="avatar-block">
				<?php while ( bp_members() ) : bp_the_member(); ?>
				<div class="item-avatar">
					<a href="<?php bp_member_permalink() ?>" title="<?php bp_member_name() ?>"><?php bp_member_avatar() ?></a>
				</div>
				<?php endwhile; ?>
			</div>
			<?php echo $args['after_widget']; ?>
			<?php do_action( 'bp_after_followers_widget' ); ?>
		<?php
		}
	}
	
	//Callback to save widget settings.
	function update( $new_instance, $old_instance ) {
		$instance 			   = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['max_users'] = (int) $new_instance['max_users'];
		return $instance;
	}
	
	//Widget settings form.
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'     => __( "", 'buddypress-followers' ),
			'max_users' => 18
		) );
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p><label for="bp-follow-widget-users-max"><?php _e('Max members to show:', 'buddypress-followers'); ?>
			<br>
			<input class="widefat" id="<?php echo $this->get_field_id( 'max_users' ); ?>" name="<?php echo $this->get_field_name( 'max_users' ); ?>" type="text" value="<?php echo esc_attr( (int) $instance['max_users'] ); ?>" style="width: 30%" /></label>
		</p>
	<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget("BP_Followers_Widget");' ) );
