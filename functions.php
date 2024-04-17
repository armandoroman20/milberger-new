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

function enqueue_owl_carousel() {
    // Register Owl Carousel CSS
    wp_enqueue_style( 'owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', array(), '2.3.4' );

    // Register Owl Carousel JS
    wp_enqueue_script( 'owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_owl_carousel' );


// Custom shortcode to retrieve post content and limit it to a certain number of words
function custom_post_content_shortcode( $atts ) {
    // Get the current post object
    global $post;
    
    // Retrieve the post content
    $post_content = $post->post_content;
    
    // Limit the content to 25 words
    $limited_content = wp_trim_words( $post_content, 25, '...' ); // Adjust the number of words as needed
    
    return $limited_content;
}
add_shortcode( 'custom_post_content', 'custom_post_content_shortcode' );





// Create Antique roses post type used for loop

function create_antique_roses_post_type() {
    $labels = array(
        'name'               => __( 'Antique Roses', 'text-domain' ),
        'singular_name'      => __( 'Antique Rose', 'text-domain' ),
        'add_new'            => __( 'Add New Antique Rose', 'text-domain' ),
        'add_new_item'       => __( 'Add New Antique Rose', 'text-domain' ),
        'edit_item'          => __( 'Edit Antique Rose', 'text-domain' ),
        'new_item'           => __( 'New Antique Rose', 'text-domain' ),
        'view_item'          => __( 'View Antique Rose', 'text-domain' ),
        'search_items'       => __( 'Search Antique Rose', 'text-domain' ),
        'not_found'          => __( 'No antique roses found', 'text-domain' ),
        'not_found_in_trash' => __( 'No antique roses found in trash', 'text-domain' ),
        'parent_item_colon'  => __( 'Parent Antique Rose:', 'text-domain' ),
        'menu_name'          => __( 'Antique Roses', 'text-domain' ),
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
        'rewrite'             => array( 'slug' => 'antique-roses' ),
    );

    register_post_type( 'antique_rose', $args );
}

add_action( 'init', 'create_antique_roses_post_type' );

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
		'publicly_queryable' => true,
    );

    register_post_type( 'sale_item', $args );
}

add_action( 'init', 'create_sale_items_post_type' );



// this creates boxes for the green and red text in the sale items custom post type
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


// shortcode for the green and red sale text

function display_green_sale_text_shortcode() {
    global $post;

    // Retrieve the value of the green sale text
    $green_sale_text = get_post_meta( $post->ID, '_green_sale_text', true );

    // Output the value using the shortcode
    return '<div class="green-sale-text">' . esc_html( $green_sale_text ) . '</div>';
}

function display_red_sale_text_shortcode() {
    global $post;

    // Retrieve the value of the red sale text
    $red_sale_text = get_post_meta( $post->ID, '_red_sale_text', true );

    // Output the value using the shortcode
    return '<div class="red-sale-text">' . esc_html( $red_sale_text ) . '</div>';
}

// Register the shortcodes
add_shortcode( 'green_sale_text', 'display_green_sale_text_shortcode' );
add_shortcode( 'red_sale_text', 'display_red_sale_text_shortcode' );


// code for sale items with images on left and right
// this will only show four sale and events items from newedt to oldest

function sale_item_loop_shortcode() {
    ob_start(); // Start output buffering

    $args = array(
        'post_type'      => 'sale_item',
        'posts_per_page' => 20, // Display all sale items
    );

    $sale_items = new WP_Query( $args );

    if ( $sale_items->have_posts() ) {
        ?>
        <div class="sale-item-loop">
        <?php

        $count = 0;

        while ( $sale_items->have_posts() ) {
            $sale_items->the_post();
            $count++;

            ?>
            <div class="responsive-two-column-grid <?php echo ( $count % 2 === 0 ? 'even' : 'odd' ) . ( $count % 2 === 0 ? ' image-right' : '' ); ?>">
                <div>
                    <div class="sale-item-image"><?php echo get_the_post_thumbnail(); ?></div>
                </div>
                <div class="content-area">
                    <h2 class="small-caps sales-loop-heading"><?php echo get_the_title(); ?></h2>
                    <div class="sale-item-description sales-loop-content"><?php echo get_the_content(); ?></div>
                    <?php
                    // Display green sale text
                    $green_sale_text = get_post_meta( get_the_ID(), '_green_sale_text', true );
                    echo '<p class="green-sale-text">' . esc_html( $green_sale_text ) . '</p>';

                    // Display red sale text
                    $red_sale_text = get_post_meta( get_the_ID(), '_red_sale_text', true );
                    echo '<p class="red-sale-text">' . esc_html( $red_sale_text ) . '</p>';
                    ?>
                </div>
            </div>
            <?php
        }

        ?>
        </div>
        <?php

        // Reset post data
        wp_reset_postdata();
    } else {
        echo 'No sale items found.';
    }

    return ob_get_clean(); // Return the buffered output
}

add_shortcode( 'saleItemLoop', 'sale_item_loop_shortcode' );

function short_sale_item_loop_shortcode() {
    ob_start(); // Start output buffering

    $args = array(
        'post_type'      => 'sale_item',
        'posts_per_page' => 4, // Display 4
    );

    $sale_items = new WP_Query( $args );

    if ( $sale_items->have_posts() ) {
        ?>
        <div class="sale-item-loop">
        <?php

        $count = 0;

        while ( $sale_items->have_posts() ) {
            $sale_items->the_post();
            $count++;

            ?>
            <div class="responsive-two-column-grid <?php echo ( $count % 2 === 0 ? 'even' : 'odd' ) . ( $count % 2 === 0 ? ' image-right' : '' ); ?>">
                <div>
                    <div class="sale-item-image"><?php echo get_the_post_thumbnail(); ?></div>
                </div>
                <div class="content-area">
                    <h2 class="small-caps sales-loop-heading"><?php echo get_the_title(); ?></h2>
                    <div class="sale-item-description sales-loop-content"><?php echo get_the_content(); ?></div>
                    <?php
                    // Display green sale text
                    $green_sale_text = get_post_meta( get_the_ID(), '_green_sale_text', true );
                    echo '<p class="green-sale-text">' . esc_html( $green_sale_text ) . '</p>';

                    // Display red sale text
                    $red_sale_text = get_post_meta( get_the_ID(), '_red_sale_text', true );
                    echo '<p class="red-sale-text">' . esc_html( $red_sale_text ) . '</p>';
                    ?>
                </div>
            </div>
            <?php
        }

        ?>
        </div>
        <?php

        // Reset post data
        wp_reset_postdata();
    } else {
        echo 'No sale items found.';
    }

    return ob_get_clean(); // Return the buffered output
}

add_shortcode( 'shortSaleItemLoop', 'short_sale_item_loop_shortcode' );

function custom_events_loop() {
    ob_start(); // Start output buffering

    $args = array(
        'post_type'      => 'tribe_events',
        'posts_per_page' => 4,
    );

    $events_query = new WP_Query( $args );

    // Default image URL
    $default_image_url = 'https://milbergers.local/wp-content/uploads/2024/01/Persimmons.jpeg';

    if ( $events_query->have_posts() ) {
        ?>
<div class="owl-carousel">
    <?php
    while ( $events_query->have_posts() ) {
        $events_query->the_post();

        // Get event date, month, start time, and content
        $event_date = tribe_get_start_date( null, false, 'j' ); // Day of the month (1-31)
        $event_month = tribe_get_start_date( null, false, 'M' ); // Month abbreviation (Jan, Feb, etc.)
        $event_time = tribe_get_start_date( null, false, 'g:i a' ); // Start time in 12-hour format (e.g., 7:00 PM)
        $event_content = get_the_content(); // Get event content

        // Limit content to 25 words
        $event_content_short = wp_trim_words( $event_content, 25, '...' );

        ?>
    <div class="item single-event-container">
        <div class="owl-nav">
            <button class="owl-prev"></button>
            <button class="owl-next"></button>
        </div>
        <div class="box event-info">
            <p class="event-month"> <?php echo $event_month; ?></p>
            <p class="event-day"><?php echo $event_date; ?></p>
            <h1 class="event-title"><?php echo get_the_title(); ?></h1>
            <div class="event-content">
                <?php echo $event_content_short; ?>
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="read-more">View Event</a>
            </div>
            <p class="event-time"><?php echo $event_time; ?></p>
        </div>
        <div class="box event-image" style="background-image: url('<?php echo esc_url( get_post_thumbnail_id() ? get_the_post_thumbnail_url() : $default_image_url ); ?>');">
            <div class="event-item-image">
            </div>
        </div>
    </div>
    <?php
    }
    ?>
</div>

        <?php

        wp_reset_postdata();
    } else {
        echo 'No events found.';
    }

    return ob_get_clean(); // Return the buffered output
}
add_shortcode( 'eventsLoop', 'custom_events_loop' );




// ON HOLD probably a 2.0 update

// create newsletter post type 

// create media upload CPT for newsletter post type

//Registering the Custom Post Type
// function register_newsletter_post_type() {
	
// 	    $labels = array(
//         'name'               => __( 'Newsletters', 'text-domain' ),
//         'singular_name'      => __( 'Newsletter', 'text-domain' ),
//         'add_new'            => __( 'Add New Newsletter', 'text-domain' ),
//         'add_new_item'       => __( 'Add New Newsletter', 'text-domain' ),
//         'edit_item'          => __( 'Edit Newsletter', 'text-domain' ),
//         'new_item'           => __( 'New Newsletter', 'text-domain' ),
//         'view_item'          => __( 'View Newsletter', 'text-domain' ),
//         'search_items'       => __( 'Search Newsletters', 'text-domain' ),
//         'not_found'          => __( 'No newsletters found', 'text-domain' ),
//         'not_found_in_trash' => __( 'No newsletters found in trash', 'text-domain' ),
//         'parent_item_colon'  => __( 'Parent Newsletter:', 'text-domain' ),
//         'menu_name'          => __( 'Newsletters', 'text-domain' ),
//     );
	
//     $args = array(
//         'labels'              => $labels,
//         'hierarchical'        => false,
//         'public'              => true,
//         'show_ui'             => true,
//         'show_in_menu'        => true,
//         'show_in_nav_menus'   => true,
//         'supports'            => array( 'title', 'thumbnail', ),
//         'has_archive'         => true,
//         'rewrite'             => array( 'slug' => 'news-letters' ),
//     );
//     register_post_type( 'newsletter', $args );
// }
// add_action( 'init', 'register_newsletter_post_type' );

// // Creating the meta box that will display field
// function newsletter_pdf_meta_box() {
//     add_meta_box(
//         'newsletter-pdf-meta-box',
//         'Upload PDF',
//         'newsletter_pdf_meta_box_callback',
//         'newsletter',
//         'normal',
//         'high'
//     );
// }
// add_action( 'add_meta_boxes', 'newsletter_pdf_meta_box' );

// // adding upload field
// function newsletter_pdf_meta_box_callback( $post ) {
//     wp_nonce_field( 'newsletter_pdf_meta_box', 'newsletter_pdf_meta_box_nonce' );
//     echo '<label for="newsletter-pdf-file">Upload PDF:</label>';
//     echo '<input type="file" id="newsletter-pdf-file" name="newsletter_pdf_file" />';
// }

// // function to handle the upload
// function save_newsletter_pdf( $post_id ) {
//     if ( ! isset( $_POST['newsletter_pdf_file'] ) ) {
//         return;
//     }

//     if ( ! wp_verify_nonce( $_POST['newsletter_pdf_meta_box_nonce'], 'newsletter_pdf_meta_box' ) ) {
//         return;
//     }

//     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
//         return;
//     }

//     $file = $_FILES['newsletter_pdf_file'];
//     $upload_overrides = array( 'test_form' => false );
//     $movefile = wp_handle_upload( $file, $upload_overrides );

//     if ( $movefile && ! isset( $movefile['error'] ) ) {
//         update_post_meta( $post_id, 'newsletter_pdf', $movefile['url'] );
//     }
// }
// add_action( 'save_post_newsletter', 'save_newsletter_pdf' );


