<?php
/**
 * BP Follow Wodgets
 *
 * @package BP-Follow
 * @subpackage Widgets
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
/**
 * Show a list of brand accounts that the displayed user has added to their feed
 *
 * @subpackage Widgets
 */
class BP_Brands_Following_Widget extends WP_Widget {
    function __construct() {
        // Set up optional widget args.
        $widget_ops = array(
            'classname'   => 'widget_bp_brands_following_widget widget buddypress',
            'description' => __( "Show a list of brand accounts that the displayed user has added to their feed.", 'buddypress-followers' )
        );
        // Set up the widget.
        parent::__construct(
            false,
            __( "(BP Follow) Brands I've Added to My Feeds", 'buddypress-followers' ),
            $widget_ops
        );
    }
        //Displays the widget.
        function widget( $args, $instance, $member_args ) {
        if ( empty( $instance['max_users'] ) ) {
            $instance['max_users'] = 16;
        }
        // If the displayed user hasn't added any brand accounts, then hide the widget.
        if ( ! $following = bp_get_following_ids( array( 'user_id' => bp_displayed_user_id() ) ) ) {
            return false;
        }
        // If the displayed user has added brand accounts to their feed, then show the profile photos of the brand accounts.
        if ( bp_has_members( array(
            'include'         => $following,
            'max'             => $instance['max_users'],
            'populate_extras' => false,
            'type'            => 'active',
            'member_type'     => 'brand'
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
        $instance['title']     = strip_tags( $new_instance['title'] );
        $instance['max_users'] = (int) $new_instance['max_users'];
        return $instance;
    }
    //Widget settings form.
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title'     => __( "Brand Accounts I've Added to My Feeds", 'buddypress-followers' ),
            'max_users' => 16
        ) );
    ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
        <p><label for="bp-follow-widget-users-max"><?php _e('Max members to show:', 'buddypress-followers'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_users' ); ?>" name="<?php echo $this->get_field_name( 'max_users' ); ?>" type="text" value="<?php echo esc_attr( (int) $instance['max_users'] ); ?>" style="width: 30%" /></label></p>
        <p><small><?php _e( 'Note: This widget will only be displayed if the displayed user has added at least one brand account to their feeds.', 'buddypress-followers' ); ?></small></p>
    <?php
    }
}
add_action( 'widgets_init', create_function( '', 'return register_widget("BP_Brands_Following_Widget");' ) );
 
 
/* Add a "Famous People I've Added to My Feed" Widget to All User's Profiles */
class BP_Famous_People_Following_Widget extends WP_Widget {
    function __construct() {
        // Set up optional widget args.
        $widget_ops = array(
            'classname'   => 'widget_bp_famous_people_following_widget widget buddypress',
            'description' => __( "Show a list of famous people accounts that the displayed user has added to their feed.", 'buddypress-followers' )
        );
        // Set up the widget.
        parent::__construct(
            false,
            __( "(BP Follow) Famous People I've Added to My Feeds", 'buddypress-followers' ),
            $widget_ops
        );
    }
        //Displays the widget.
        function widget( $args, $instance, $member_args ) {
        if ( empty( $instance['max_users'] ) ) {
            $instance['max_users'] = 16;
        }
        // If the displayed user hasn't added any famous people accounts, then hide the widget.
        if ( ! $following = bp_get_following_ids( array( 'user_id' => bp_displayed_user_id() ) ) ) {
            return false;
        }
        // If the displayed user has added famous people accounts to their feed, then show the profile photos of the famous people accounts.
        if ( bp_has_members( array(
            'include'         => $following,
            'max'             => $instance['max_users'],
            'populate_extras' => false,
            'type'            => 'active',
            'member_type'     => 'famous-person'
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
        $instance['title']     = strip_tags( $new_instance['title'] );
        $instance['max_users'] = (int) $new_instance['max_users'];
        return $instance;
    }
    //Widget settings form.
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title'     => __( "Famous People Accounts I've Added to My Feeds", 'buddypress-followers' ),
            'max_users' => 16
        ) );
    ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
        <p><label for="bp-follow-widget-users-max"><?php _e('Max members to show:', 'buddypress-followers'); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_users' ); ?>" name="<?php echo $this->get_field_name( 'max_users' ); ?>" type="text" value="<?php echo esc_attr( (int) $instance['max_users'] ); ?>" style="width: 30%" /></label></p>
        <p><small><?php _e( 'Note: This widget will only be displayed if the displayed user has added at least one famous person to their feeds.', 'buddypress-followers' ); ?></small></p>
    <?php
    }
}
add_action( 'widgets_init', create_function( '', 'return register_widget("BP_Famous_People_Following_Widget");' ) );
