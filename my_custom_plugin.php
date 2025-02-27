<?php
/**
 * Plugin Name: My Custom Post Type
 * Plugin URI: https://example.com
 * Description: Registers a custom post type called "Book" with a custom taxonomy "Genre".
 * Version: 1.0
 * Author: suryansh 
 * Author URI: https://example.com
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; 
}


function mcp_register_book_post_type() {
    $labels = array(
        'name'               => 'Books',
        'singular_name'      => 'Book',
        'menu_name'          => 'Books',
        //'add_new'            => 'Add New book ',
        'add_new_item'       => 'Add New Book',
        'edit_item'          => 'Edit Book',
        'all_items'          => 'All Books',
        'search_items'       => 'Search Books',
        'not_found'          => 'No books found.',
        'not_found_in_trash' => 'No books found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'books'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-book',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields', 'revisions'),
        'show_in_rest'       => true, // Enable Gutenberg support
    );

    register_post_type('book', $args);
}
add_action('init', 'mcp_register_book_post_type');

// Register Custom Taxonomy: Genre
function mcp_register_genre_taxonomy() {
    $labels = array(
        'name'              => 'Genres',
        'singular_name'     => 'Genre',
        'menu_name'         => 'Genres',
        'add_new_item'      => 'Add New Genre',
    );

    $args = array(
        'hierarchical'      => true, // Works like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'genre'),
        'show_in_rest'      => true,
    );

    register_taxonomy('genre', 'book', $args);
}
add_action('init', 'mcp_register_genre_taxonomy');

// Shortcode: Display Book List
function mcp_book_list_shortcode() {
    $query = new WP_Query(array('post_type' => 'book', 'posts_per_page' => 10));
    $output = '<div class="book-list">';

    while ($query->have_posts()) {
        $query->the_post();
        $output .= '<div class="book-item">';
        $output .= get_the_post_thumbnail(get_the_ID(), 'thumbnail');
        $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
        $output .= '<p>' . get_the_term_list(get_the_ID(), 'genre', 'Genre: ', ', ') . '</p>';
        $output .= '</div>';
    }

    wp_reset_postdata();
    return $output . '</div>';
}
add_shortcode('book_list', 'mcp_book_list_shortcode');

// Enqueue Styles for Shortcode
function mcp_enqueue_styles() {
    echo '<style>
        .book-list { display: flex; flex-wrap: wrap; gap: 20px; }
        .book-item { width: 200px; text-align: center; }
        .book-item img { max-width: 100px; }
    </style>';
}
add_action('wp_head', 'mcp_enqueue_styles');

// function add_book_list_meta_box() {
//     add_meta_box(
//         'book_list_meta_box',
//         'Book List Preview',
//         'book_list_meta_box_callback',
//         'page',
//         'normal',
//         'high'
//     );
// }

// front end form for user to allow access to add  delete and update books from frontend with featured image 


// Frontend form and book list
function book_frontend_form() {
    if (!is_user_logged_in()) {
        return '<p>You must be logged in to manage books.</p>';
    }
    wp_enqueue_style('book-plugin-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    ob_start();
    ?>
    

    <form id="book-form" enctype="multipart/form-data">
        <input type="hidden" id="book_id" name="book_id" value="">
        <label for="book_title">Title:</label>
        <input type="text" id="book_title" name="book_title" required>

        <label for="book_author">Author:</label>
        <input type="text" id="book_author" name="book_author" required>

        <label for="book_description">Description:</label>
        <textarea id="book_description" name="book_description" required></textarea>

        <label for="book_image">Featured Image:</label>
        <input type="file" id="book_image" name="book_image" accept="image/*">

        <button type="submit">Save Book</button>
    </form>

    <div id="book-message"></div>

    <h2 style="text-align:center;">My Books</h2>
    <div id="book-list"></div>

    <script>
    jQuery(document).ready(function($) {
        function loadBooks() {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: { action: 'get_user_books' },
                success: function(response) {
                    $('#book-list').html(response);
                }
            });
        }

        loadBooks();

        $('#book-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'save_book');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#book-message').html(response);
                    $('#book-form')[0].reset();
                    $('#book_id').val('');
                    loadBooks();
                }
            });
        });

        $(document).on('click', '.edit-book', function() {
            var bookId = $(this).data('id');
            $('#book_id').val(bookId);
            $('#book_title').val($(this).data('title'));
            $('#book_author').val($(this).data('author'));
            $('#book_description').val($(this).data('description'));
        });

        $(document).on('click', '.delete-book', function() {
            if (confirm('Are you sure you want to delete this book?')) {
                var bookId = $(this).data('id');
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: { action: 'delete_book', book_id: bookId },
                    success: function(response) {
                        $('#book-message').html(response);
                        loadBooks();
                    }
                });
            }
        });
    });
    </script>
    <?php
    

    return ob_get_clean();
}
add_shortcode('book_frontend_form', 'book_frontend_form');

// Handle Saving & Updating Books
function save_book() {
    if (!is_user_logged_in()) {
        echo '<p style="color:red;">You must be logged in.</p>';
        wp_die();
    }

    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
    $title = sanitize_text_field($_POST['book_title']);
    $author = sanitize_text_field($_POST['book_author']);
    $description = sanitize_textarea_field($_POST['book_description']);
    $user_id = get_current_user_id();
    $attachment_id = '';

    if (!empty($_FILES['book_image']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $uploaded_file = $_FILES['book_image'];
        $upload = wp_handle_upload($uploaded_file, ['test_form' => false]);

        if ($upload && !isset($upload['error'])) {
            $attachment = [
                'guid' => $upload['url'],
                'post_mime_type' => $upload['type'],
                'post_title' => sanitize_file_name($uploaded_file['name']),
                'post_content' => '',
                'post_status' => 'inherit'
            ];
            $attachment_id = wp_insert_attachment($attachment, $upload['file']);
            wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
        }
    }

    if ($book_id > 0) {
        wp_update_post(['ID' => $book_id, 'post_title' => $title, 'post_content' => $description]);
        update_post_meta($book_id, 'book_author', $author);
        if ($attachment_id) set_post_thumbnail($book_id, $attachment_id);
        echo '<p style="color:green;">Book updated successfully!</p>';
    } else {
        $new_post_id = wp_insert_post([
            'post_title' => $title,
            'post_content' => $description,
            'post_type' => 'book',
            'post_status' => 'publish',
            'post_author' => $user_id
        ]);
        update_post_meta($new_post_id, 'book_author', $author);
        if ($attachment_id) set_post_thumbnail($new_post_id, $attachment_id);
        echo '<p style="color:green;">Book added successfully!</p>';
    }

    wp_die();
}
add_action('wp_ajax_save_book', 'save_book');

// Get User Books
function get_user_books() {
    if (!is_user_logged_in()) {
        echo '<p style="color:red;">You must be logged in.</p>';
        wp_die();
    }

    $args = ['post_type' => 'book', 'post_status' => 'publish', 'author' => get_current_user_id(), 'posts_per_page' => -1];
    $books = get_posts($args);

    if ($books) {
        echo '<ul>';
        foreach ($books as $book) {
            $image_url = get_the_post_thumbnail_url($book->ID, 'thumbnail');
            echo '<li>';
            if ($image_url) echo '<img src="' . esc_url($image_url) . '" width="50">';
            echo '<strong>' . esc_html($book->post_title) . '</strong>';
            echo ' <button class="edit-book" data-id="' . $book->ID . '" data-title="' . esc_attr($book->post_title) . '" data-author="' . esc_attr(get_post_meta($book->ID, 'book_author', true)) . '" data-description="' . esc_textarea($book->post_content) . '">Edit</button>';
            echo ' <button class="delete-book" data-id="' . $book->ID . '">Delete</button>';
            echo '</li>';
        }
        echo '</ul>';
    } else echo '<p>No books found.</p>';

    wp_die();
}
add_action('wp_ajax_get_user_books', 'get_user_books');

// Delete Book
function delete_book() {
    if (is_user_logged_in() && get_post_field('post_author', $_POST['book_id']) == get_current_user_id()) {
        wp_delete_post($_POST['book_id'], true);
        echo '<p style="color:green;">Book deleted!</p>';
    }
    wp_die();
}
add_action('wp_ajax_delete_book', 'delete_book');
