<?php

use function PHPUnit\Framework\directoryExists;

$version = 5;

//including parent theme styles
add_action('wp_enqueue_scripts', 'twtw_child_styles');
function twtw_child_styles() {
  $parent = 'twentytwenty-style';
  wp_enqueue_style($parent, get_template_directory_uri().'/style.css');
  wp_enqueue_style('twentytwenty-child-style', get_stylesheet_uri(), array($parent));
}

add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script('twentytwenty-custom-js', get_stylesheet_directory_uri().'/assets/js/custom.js', array('jquery'), false, true);
  wp_localize_script('twentytwenty-custom-js', 'ajax', ['url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax_nonce'), 'api_nonce' => wp_create_nonce('wc_store_api')]);
});

//Task #3: Not sure if i understood task correctly
//default action before title
add_action('woocommerce_before_shop_loop_item_title', function () {
  echo '<strong>NEW!</strong>';
});

//replacing existing title output with new function and including "NEW!" right into h2 tag
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
add_action('woocommerce_shop_loop_item_title', function ($title) {
  echo '<h2 class="'.esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')).'"><strong>NEW!</strong> '.get_the_title().'</h2>';
});
//End task #3

//Task #4, value for get_field is not specified in task (post_id or option) 
add_action('wp_body_open', function () {
  global $version;
  if (function_exists('get_field')) {
    $current_version = get_field('currentversion', 'option');
    if (!empty($current_version)) {
      $version = $current_version;
    }
  }
  echo '<h2>'.sprintf( __('Welcome to version %s of Visionmate', 'visionmate'), $version).'</h2>';
});
//end task #4

//task #5
//moving post type init to class
// function booksPostType() {
//   register_post_type('books',
//     array(
//       'labels'        => array(
//       'name'          => __('Books', 'visionmate'),
//       'singular_name' => __('Book', 'visionmate')
//     ),
//       'public'      => true,
//       'has_archive' => false,
//       'rewrite'     => ['slug' => 'book'],
//     )
//   );
// }
// add_action( 'init', 'booksPostType' );

function getTwentyBooks() {
  $wp_query = new WP_Query([
    'post_type'   => 'books',
    'post_status' => 'publish',
    'order_by'    => 'post_date',
    'order'       => 'ASC',
    'meta_query'  => array(
        array(
            'key'     => 'genre',
            'value'   => ['learning', 'e-book'],
            'compare' => 'IN',
        )
    )
  ]);

  return $wp_query->posts;
}
//getting books and display titles, temporary disabled
// $books = getTwentyBooks();
// $titles = wp_list_pluck($books, 'post_title'); 
// foreach ($titles as $title) {
  // echo '<h5>'.$title.'</h5>';
// }

//ajax function
function getBooksAjax() {
  if (!wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce')) {
    echo json_encode(['result' => false, 'message' => 'Wrong nonce']);
    die();
  }
  $books = getTwentyBooks();
  $books_response = array_map(function ($item) {
    return [
      'name'    => $item->post_title, 
      'date'    => $item->post_date,
      'genre'   => $item->genre,
      'excerpt' => $item->excerpt
    ];
  }, $books);
 
  echo json_encode(['result' => true, 'books' => $books_response]);
  die();
}
add_action('wp_ajax_testAction', 'getBooksAjax');
add_action('wp_ajax_nopriv_testAction', 'getBooksAjax');
//end task #5

//task #6
class ProgrammaticalPost {

  public function go() {
    if (!$this->exist()) {
      return $this->create();
    }
    return false;
  }

  private function exist() {
    return (bool) get_page_by_title('Hello', OBJECT, 'post');
  }

  private function create() {
    return wp_insert_post([
      'post_title'   => 'Hello',
      'post_content' => 'World'
    ]);
  }

}

function createPost() {
  $progPost = new ProgrammaticalPost;
  $status = $progPost->go();
  if (!$status) {
    echo json_encode(['result' => false, 'message' => 'Post Already Exist']);
    die();
  }
  echo json_encode(['result' => true, 'message' => 'Post Successfully created']);
  die();
}
add_action('wp_ajax_createPost', 'createPost');
add_action('wp_ajax_nopriv_createPost', 'createPost');


function productToCart() {
  if ( !wp_verify_nonce($_REQUEST['nonce'], 'wc_store_api')) {
    echo json_encode(['result' => false, 'message' => 'Wrong nonce']);
    die();
  } 
  if (is_callable('WC')) {
    WC()->cart->add_to_cart(106); 
    echo json_encode(['result' => true, 'message' => 'Product Successfully added to cart']);
    die();
  } 
  echo json_encode(['result' => true, 'message' => 'Something went Wrong']);
  die();
}
add_action('wp_ajax_productToCart', 'productToCart');
add_action('wp_ajax_nopriv_productToCart', 'productToCart');

function createProduct() {
  if ( !wp_verify_nonce($_REQUEST['nonce'], 'ajax_nonce')) {
    echo json_encode(['result' => false, 'message' => 'Wrong nonce']);
    die();
  }
  $status = false;
  if (class_exists('WC_Product_Simple')) {
    $product = new WC_Product_Simple();
    $product->set_name('Hello ');
    $product->set_status('publish'); 
    $product->set_catalog_visibility('visible');
    $product->set_price(5);
    $product->set_regular_price(5);
    $status = $product->save();
  }
  if (!$status) {
    echo json_encode(['result' => true, 'message' => 'Product Already Exist']);
    die();
  }
  echo json_encode(['result' => true, 'message' => 'Product Successfully created']);
  die();
}
add_action('wp_ajax_createProduct', 'createProduct');
add_action('wp_ajax_nopriv_createProduct', 'createProduct');
//end task #6

//task #7
class NewRoles {

  public function __construct() {
    add_action('init', [$this, 'initRoles']);
  }

  public function initRoles() {
    $libraryRole = add_role('library_manager', 'Library manager', []);
    if (empty($libraryRole)) {
        $libraryRole = get_role('library_manager');
    }
    $libraryCaps = [
      'create_post'            => true, 
      'read_post'              => true, 
      'edit_post'              => true, 
      'delete_post'            => true, 
      'edit_posts'             => true, 
      'edit_others_posts'      => true, 
      'read_private_posts'     => true, 
      'read'                   => true,
      'delete_private_posts'   => true, 
      'delete_published_posts' => true, 
      'delete_others_posts'    => true, 
      'edit_private_posts'     => true, 
      'edit_published_posts'   => true, 
      'publish_posts'          => true,
      'manage_categories'      => true
    ];
    foreach ($libraryCaps as $cap => $value) {
      $libraryRole->add_cap( $cap, $value );
    }
  
    $bookerRole = add_role('book_editor', 'Book Editor', []);
    if (empty($bookerRole)) {
      $bookerRole = get_role('book_editor');
    }
    $bookerCaps =  [
      'create_post'            => true, 
      'read_post'              => true, 
      'edit_post'              => true, 
      'delete_post'            => true, 
      'edit_posts'             => true, 
      'edit_others_posts'      => false, 
      'read_private_posts'     => true, 
      'read'                   => true,
      'delete_private_posts'   => true, 
      'delete_published_posts' => true, 
      'delete_others_posts'    => false, 
      'edit_private_posts'     => true, 
      'edit_published_posts'   => true, 
      'publish_posts'          => true,
      'manage_categories'      => false
    ];;
    foreach ($bookerCaps as $cap => $value) {
      $bookerRole->add_cap( $cap, $value );
    }
  }
}
new NewRoles();

class BooksPostType {

  public function __construct()
  {
    add_action( 'init', [$this, 'initPostType']);
    add_action( 'init', [$this, 'initTaxonomy']);
    add_action( 'init', [$this, 'registerShortcode']);
  }

  public function initPostType() {
    $labels = array(
      'name'                  => _x('Books', 'Post type general name', 'visionmate'),
      'singular_name'         => _x('Book', 'Post type singular name', 'visionmate'),
      'menu_name'             => _x('Books', 'Admin Menu text', 'visionmate'),
      'name_admin_bar'        => _x('Book', 'Add New on Toolbar', 'visionmate'),
      'add_new'               => __('Add New', 'visionmate'),
      'add_new_item'          => __('Add New Book', 'visionmate'),
      'new_item'              => __('New Book', 'visionmate'),
      'edit_item'             => __('Edit Book', 'visionmate'),
      'view_item'             => __('View Book', 'visionmate'),
      'all_items'             => __('All Books', 'visionmate'),
      'search_items'          => __('Search Books', 'visionmate'),
      'parent_item_colon'     => __('Parent Books:', 'visionmate'),
      'not_found'             => __('No books found.', 'visionmate'),
      'not_found_in_trash'    => __('No books found in Trash.', 'visionmate'),
      'featured_image'        => _x('Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'visionmate'),
      'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'visionmate'),
      'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'visionmate'),
      'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'visionmate'),
      'archives'              => _x('Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'visionmate'),
      'insert_into_item'      => _x('Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'visionmate'),
      'uploaded_to_this_item' => _x('Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'visionmate'),
      'filter_items_list'     => _x('Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'visionmate'),
      'items_list_navigation' => _x('Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'visionmate'),
      'items_list'            => _x('Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'visionmate'),
    );
 
    register_post_type( 'books',
      array(
        'labels'      => $labels,
        'public'      => true,
        'has_archive' => false,
        'rewrite'     => array('slug' => 'library'),
      )
    );
  }

  public function initTaxonomy() {
    $labels = array(
        'name'              => _x('Genres', 'taxonomy general name', 'visionmate'),
        'singular_name'     => _x('Genre', 'taxonomy singular name', 'visionmate'),
        'search_items'      => __('Search Genres', 'visionmate'),
        'all_items'         => __('All Genre', 'visionmate'),
        'parent_item'       => __('Parent Genre', 'visionmate'),
        'parent_item_colon' => __('Parent Genre:', 'visionmate'),
        'edit_item'         => __('Edit Genre', 'visionmate'),
        'update_item'       => __('Update Genre', 'visionmate'),
        'add_new_item'      => __('Add New Genre', 'visionmate'),
        'new_item_name'     => __('New Genre Name', 'visionmate'),
        'menu_name'         => __('Genres', 'visionmate'),
     );
     $args = array(
        'hierarchical'      => true, 
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'book-genre'],
        'capabilities' => array(
          'manage_terms' => 'manage_categories',
          'edit_terms'   => 'manage_categories',
          'delete_terms' => 'manage_categories',
          'assign_terms' => 'edit_posts',
        )
     );
     register_taxonomy('genre', ['books'], $args);
  }
  public function registerShortcode() {
    add_shortcode('latest_books', function ($atts) {
      if (empty($atts['genre'])) {
        return false;
      }
      //default sort is post_date DESC
      $books = get_posts([
        'numberposts' => 5,
        'post_type'   => 'books',
        'post_status' => 'publish',
        'tax_query'   => [
          [
            'taxonomy' => 'genre',
            'field'    => 'term_id',
            'terms'    => $atts['genre']
          ]
        ]
      ]);

      $titles = wp_list_pluck($books, 'post_title');
      if (!empty($titles)) {
        $html = '<ul>';
        foreach($titles as $title) {
          $html .= '<li>'.$title.'</li>';
        }
        $html .= '</ul>';
      }
      return $html;
    });
  }
}
new BooksPostType();
//end task #7

//task #8
//ideally previous classes should be moved to classes folder too.
if (file_exists(get_stylesheet_directory().'/classes') && file_exists(get_stylesheet_directory().'/classes/interfaces')) {
  foreach (glob(get_stylesheet_directory()."/classes/interfaces/*.php") as $filename)
  {
      include $filename;
  }
  foreach (glob(get_stylesheet_directory()."/classes/*.php") as $filename)
  {
      include $filename;
  }
}
$car = new Visionmate\Car('First Car', 1000);
$ecar = new Visionmate\ElectricCar('Second', 5000, 'electric', 400, 300);
