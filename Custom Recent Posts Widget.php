<?php
/*
Plugin Name: Custom Recent Posts Widget
Plugin URI: https://example.com
Description: This plugin adds a custom widget to display recent posts.
Version: 1.0
Author: SK
Author URI: https://yourwebsite.com
*/




add_action("admin_menu",  "Custom_Recent_Posts_Widget");

  function Custom_Recent_Posts_Widget(){

    add_menu_page('custom_posts', 'Custom Post', 'administrator', 'Custom-Recent-Posts-Widget', 'Custom_Recent_Posts_Widget_Callback', 'dashicons-welcome-widgets-menus', '5' );

  }

  function Custom_Recent_Posts_Widget_Callback(){

    // This is the callback function to display content on the menu page.
    echo '<div class="wrap">';
    echo '<h2>My Custom Widget Post Page</h2>';
    echo '<p>This is Custom widget post page.</p>';
    echo '<p>Visit <a href="">Example Website</a></p>';
    echo '</div>';
  }


// Define widget class
class Custom_Recent_Posts_Widget extends WP_Widget {
    
    // Constructor
    public function __construct() {
        parent::__construct(
            'custom_recent_posts_widget', // Base ID
            'Custom Recent Posts Widget', // Widget name
            array( 'description' => 'A custom widget to display recent posts.' ) // Widget description
        );
    }

    // Widget output
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        $num_posts = ! empty( $instance['num_posts'] ) ? $instance['num_posts'] : 5;
        $recent_posts = wp_get_recent_posts( array( 'numberposts' => $num_posts ) );
        echo '<ul>';
        foreach( $recent_posts as $post ) {
            echo '<li><a href="' . get_permalink( $post['ID'] ) . '">' . get_the_title( $post['ID'] ) . '</a></li>';
        }
        echo '</ul>';
        echo $args['after_widget'];

        //var_dump($args);
    }

     

    // Widget form
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Recent Posts';
        $num_posts = ! empty( $instance['num_posts'] ) ? $instance['num_posts'] : 5;

        $selected_category = ! empty( $instance['category'] ) ? $instance['category'] : 0;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'num_posts' ); ?>">Number of posts to display:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $num_posts ); ?>">
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'category' ); ?>">Category:</label>
        <select class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
            <option value="0">All Categories</option>
            <?php
            $categories = get_categories();
            foreach ($categories as $category) {
                $selected = ($category->term_id == $selected_category) ? 'selected' : '';
                echo '<option value="' . $category->term_id . '" ' . $selected . '>' . $category->name . '</option>';
            }
            ?>
        </select>
    </p>
        <?php
    }

    // Widget update
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['num_posts'] = ( ! empty( $new_instance['num_posts'] ) ) ? absint( $new_instance['num_posts'] ) : 5;
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? $new_instance['category'] : 0;
        return $instance;
    }
}

// Register widget
function register_custom_recent_posts_widget() {
    register_widget( 'Custom_Recent_Posts_Widget' );
}
add_action( 'widgets_init', 'register_custom_recent_posts_widget' );
