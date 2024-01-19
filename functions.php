<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );


// Create on sale post type

function create_sale_items_post_type() {
    $labels = array(
        'name'               => __( 'Sale Items', 'text-domain' ),
        'singular_name'      => __( 'Sale Item', 'text-domain' ),
        'add_new'            => __( 'Add New Sale Item', 'text-domain' ),
        'add_new_item'       => __( 'Add New Sale Item', 'text-domain' ),
        'edit_item'          => __( 'Edit Sale Item', 'text-domain' ),
        'new_item'           => __( 'New Sale Item', 'text-domain' ),
        'view_item'          => __( 'View Sale Item', 'text-domain' ),
        'search_items'       => __( 'Search Sale Items', 'text-domain' ),
        'not_found'          => __( 'No sale items found', 'text-domain' ),
        'not_found_in_trash' => __( 'No sale items found in trash', 'text-domain' ),
        'parent_item_colon'  => __( 'Parent Sale Item:', 'text-domain' ),
        'menu_name'          => __( 'Sale Items', 'text-domain' ),
    );

    $args = array(
        'labels'              => $labels,
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'sale-items' ),
    );

    register_post_type( 'sale_item', $args );
}

add_action( 'init', 'create_sale_items_post_type' );

function add_sale_item_meta_boxes() {
    add_meta_box(
        'green_sale_text_meta_box',
        'Green Sale Text',
        'render_green_sale_text_meta_box',
        'sale_item',
        'normal',
        'default'
    );

    add_meta_box(
        'red_sale_text_meta_box',
        'Red Sale Text',
        'render_red_sale_text_meta_box',
        'sale_item',
        'normal',
        'default'
    );
}

function render_green_sale_text_meta_box( $post ) {
    $green_sale_text = get_post_meta( $post->ID, '_green_sale_text', true );
    ?>
    <label for="green_sale_text">Green Sale Text:</label>
    <input type="text" id="green_sale_text" name="green_sale_text" value="<?php echo esc_attr( $green_sale_text ); ?>" style="width: 100%;">
    <?php
}

function render_red_sale_text_meta_box( $post ) {
    $red_sale_text = get_post_meta( $post->ID, '_red_sale_text', true );
    ?>
    <label for="red_sale_text">Red Sale Text:</label>
    <input type="text" id="red_sale_text" name="red_sale_text" value="<?php echo esc_attr( $red_sale_text ); ?>" style="width: 100%;">
    <?php
}

function save_sale_item_meta( $post_id ) {
    if ( isset( $_POST['green_sale_text'] ) ) {
        update_post_meta( $post_id, '_green_sale_text', sanitize_text_field( $_POST['green_sale_text'] ) );
    }

    if ( isset( $_POST['red_sale_text'] ) ) {
        update_post_meta( $post_id, '_red_sale_text', sanitize_text_field( $_POST['red_sale_text'] ) );
    }
}

add_action( 'add_meta_boxes_sale_item', 'add_sale_item_meta_boxes' );
add_action( 'save_post', 'save_sale_item_meta' );


// code for sale items with images on left and right
// this will only show four sale and events items from newedt to oldest

function sale_item_loop_shortcode() {
    $args = array(
        'post_type'      => 'sale_item',
        'posts_per_page' => 4, // Display all sale items
    );

    $sale_items = new WP_Query( $args );

    if ( $sale_items->have_posts() ) {
        $output = '<div class="sale-item-loop">';

        $count = 0;

        while ( $sale_items->have_posts() ) {
            $sale_items->the_post();
            $count++;

            $output .= '<div class="responsive-two-column-grid ' . ( $count % 2 === 0 ? 'image-right' : '' ) . '">';
            $output .=  '<div>';
            $output .= '<div class="sale-item-image">' . get_the_post_thumbnail() . '</div>';
            $output .=  '</div>';
            $output .=  '<div>';
            $output .= '<h2>' . get_the_title() . '</h2>';
            $output .= '<div class="sale-item-description">' . get_the_content() . '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</div>';

        // Reset post data
        wp_reset_postdata();

        return $output;
    } else {
        return 'No sale items found.';
    }
}

add_shortcode( 'saleItemLoop', 'sale_item_loop_shortcode' );
