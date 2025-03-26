// Custom Post Type für Kerb-Events
function bkv_register_kerb_event_post_type() {
    $labels = array(
        'name' => 'Kerb-Events',
        'singular_name' => 'Kerb-Event',
        // Weitere Labels...
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'kerb-programm'),
    );
    
    register_post_type('kerb_event', $args);
    
    // Taxonomie für Kerb-Tage
    register_taxonomy(
        'kerb_day',
        'kerb_event',
        array(
            'label' => 'Tag',
            'rewrite' => array('slug' => 'kerb-tag'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'bkv_register_kerb_event_post_type');

// Theme-Unterstützung
function bkv_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Navigation registrieren
    register_nav_menus(array(
        'primary' => 'Hauptmenü',
        'footer' => 'Footer-Menü',
    ));
}
add_action('after_setup_theme', 'bkv_theme_setup');

// Assets einbinden
function bkv_enqueue_scripts() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Audiowide&family=Montserrat:wght@400;600;700&display=swap');
    wp_enqueue_style('bkv-style', get_stylesheet_uri(), array(), '1.0.0');
    
    wp_enqueue_script('bkv-main', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'bkv_enqueue_scripts');

// Shortcode für das Programm
function bkv_kerb_program_shortcode($atts) {
    $atts = shortcode_atts(array(
        'year' => date('Y'),
    ), $atts);
    
    ob_start();
    include(get_template_directory() . '/template-parts/kerb-program.php');
    return ob_get_clean();
}
add_shortcode('kerb_program', 'bkv_kerb_program_shortcode');
