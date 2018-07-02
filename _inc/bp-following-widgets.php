<?php
/**
 * BP Following Widget
 *
 * @package BP-Follow
 * @subpackage Widgets
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
/**
 * Add a "BuddyPress Following Widget" widget for the displayed user
 *
 * @subpackage Widgets
 */

class BP_Following_Widget extends WP_Widget {
	function __construct() {
		//Set up optional widget args.
		$widget_ops = array(
			'classname'   => 'widget_bp_following_widget widget buddypress',
			'description' => __( "Show a list of people and/or accounts based on member type that the displayed user is following.", 'buddypress-followers' ) );
		
		//Set up the widget.
		parent::__construct(
			false,
			__( "BuddyPress Following Widget", 'buddypress-followers' ),
			$widget_ops
		);
	}
	
	//Displays the widget.
	function widget( $args, $instance, $member_args ) {
		$member_type = $instance['member_type'];
		bp_featured_members()->set( 'member_type', $member_type );
		
		if ( empty( $instance['max_users'] ) ) {
			$instance['max_users'] = 18;
		}
        
		//If the displayed user isn't following any accounts based on the defined member type, then hide the widget.
        if ( ! $following = bp_get_following_ids( array( 'user_id' => bp_displayed_user_id() ) ) ) {
            return false;
        }
		
		//If the displayed user is following accounts based on the defined member type, then show the widget.
		if ( bp_has_members( array(
			'include'         => $following,
			'max'             => $instance['max_users'],
			'populate_extras' => false,
			'type'            => 'active',
			'member_type'     => $member_type,
		) ) ) {
			do_action( 'bp_before_following_widget' );
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
			<?php do_action( 'bp_after_following_widget' ); ?>
		<?php
		}
	}
	
	//Callback to save widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['max_users']   = (int) $new_instance['max_users'];
		$instance['member_type'] = $new_instance['member_type'];
		return $instance;
	}
	
	//Widget settings form.
	function form( $instance ) {
		$member_types = bp_get_member_types( array(), 'objects' );
		$member_type = $instance['member_type'];
		$instance = wp_parse_args( (array) $instance, array(
			'title'     => __( "Brand Accounts I've Added to My Feeds", 'buddypress-followers' ),
			'max_users' => 18,
			'member_type' => ''
		) );

		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<?php if ( ! empty( $member_types ) ) : ?>
			<p>
				<label>
					<?php _e( 'Filter by Member Type:', 'buddypress-followers' ); ?>
					<br>
					<select id="<?php echo $this->get_field_id( 'member_type' ); ?>" name ="<?php echo $this->get_field_name( 'member_type' ); ?>">
						<option value="" <?php selected( $member_type, "" ); ?> ><?php _e( 'N/A', 'buddypress-followers' );?></option>
						<?php foreach ( $member_types as $member_type_obj ): ?>
						<option value="<?php echo esc_attr( $member_type_obj->name ); ?>" <?php selected( $member_type, $member_type_obj->name ); ?> ><?php echo esc_html( $member_type_obj->labels['singular_name'] ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<br/>
			</p>
		<?php endif;?>
			<p><label for="bp-follow-widget-users-max"><?php _e('Max members to show:', 'buddypress-followers'); ?>
				<br/>
				<input class="widefat" id="<?php echo $this->get_field_id( 'max_users' ); ?>" name="<?php echo $this->get_field_name( 'max_users' ); ?>" type="text" value="<?php echo esc_attr( (int) $instance['max_users'] ); ?>" style="width: 30%" /></label>
			</p>
		<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget("BP_Following_Widget");' ) );
