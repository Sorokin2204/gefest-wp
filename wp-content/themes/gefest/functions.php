<?php
/**
 * gefest functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package gefest
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function gefest_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on gefest, use a find and replace
		* to change 'gefest' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'gefest', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-main' => esc_html__( 'Primary', 'gefest' ),
			'menu-mobile' => esc_html__( 'Mobile', 'gefest' ),
			'menu-side' => esc_html__( 'Side', 'gefest' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'gefest_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'gefest_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gefest_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'gefest_content_width', 640 );
}
add_action( 'after_setup_theme', 'gefest_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function gefest_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'gefest' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'gefest' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'gefest_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function gefest_scripts() {
	wp_enqueue_style( 'gefest-style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_enqueue_style( 'gefest-menu', get_template_directory_uri() . '/css/menu.css', array(), _S_VERSION );
	wp_style_add_data( 'gefest-style', 'rtl', 'replace' );

	// wp_enqueue_script( 'gefest-navigation', get_template_directory_uri() . '/js/navigation.js', '', '', true );
	wp_enqueue_script( 'gefest-main', get_template_directory_uri() . '/js/app.min.js','', '', true);
	wp_enqueue_script( 'gefest-custom', get_template_directory_uri() . '/js/custom.js','', '', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gefest_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// add svg
function cc_mime_types_svg($mimes_svg) {
    $mimes_svg['svg'] = 'image/svg+xml';
    return $mimes_svg;
}
add_filter('upload_mimes', 'cc_mime_types_svg');

// add webp
function cc_mime_types_webp($mimes_webp) {
    $mimes_webp['webp'] = 'image/webp+xml';
    return $mimes_webp;
}
add_filter('upload_mimes', 'cc_mime_types_webp');


// documents CPT
//add_action( 'init', 'register_post_types' );
//
//function register_post_types(){
//
//    register_post_type( 'documents', [
//        'label'  => null,
//        'labels' => [
//            'name'               => 'Документы', // основное название для типа записи
//            'singular_name'      => 'Документ', // название для одной записи этого типа
//            'add_new'            => 'Добавить Документ', // для добавления новой записи
//            'add_new_item'       => 'Добавление Документа', // заголовка у вновь создаваемой записи в админ-панели.
//            'edit_item'          => 'Редактирование Документа', // для редактирования типа записи
//            'new_item'           => 'Новый Документ', // текст новой записи
//            'view_item'          => 'Смотреть Документ', // для просмотра записи этого типа.
//            'search_items'       => 'Искать Документы', // для поиска по этим типам записи
//            'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
//            'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
//            'menu_name'          => 'Документы', // название меню
//        ],
//        'description'         => '',
//        'public'              => true,
//        'show_in_menu'        => true,
//        'menu_icon'           => 'dashicons-media-document',
//        'hierarchical'        => true,
//        'supports'            => [ 'title' ,'custom-fields', 'page-attributes','post-formats'],
//        'taxonomies'          => ['types'],
//        'has_archive'         => false,
//        'rewrite'             => true,
//        'query_var'           => true,
//    ] );
//
//}

// taxonomy for Documents
//function crunchify_create_the_attaction_taxonomy() {
//    register_taxonomy(
//        'types',
//        'documents',
//        array(
//            'hierarchical' => true,
//            'label' => 'Категории',
//            'query_var' => true,
//            'has_archive' => true
//        )
//    );
//}
//add_action( 'init', 'crunchify_create_the_attaction_taxonomy');

function mytheme_add_woocommerce_support()
{
	add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');


add_action('admin_head', 'bbloomer_max_one_product_category', 9999);

function bbloomer_max_one_product_category()
{
	$screen = get_current_screen();
	$screen_id = $screen ? $screen->id : '';
	if (('add' === $screen->action && 'product' === $_GET['post_type']) || in_array($screen_id, array('product', 'edit-product'))) {
		wc_enqueue_js("
         $('#product_cat-all input:checkbox').change(function () {
            var max = 1;
            var count = $('#product_cat-all input:checked').length;
            if (count > max) {
               $(this).prop('checked', '');
               alert('Можно выбрать только одну категорию');
            }
         });
      ");
	}
}