<?php
//CREACION DE AREA DE MENU
function init_template(){
    add_theme_support( 'post-thumbnails');
    add_theme_support( 'title-tag');

    register_nav_menus( 
        array(
            'top_menu' => 'Menú Principal',
        )        
    );
}
add_action('after_setup_theme', 'init_template');

//AGREGAMOS NUESTROS ASSETS ESTILOS FUENTES SCRIPTS
function assets(){
    wp_register_style('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css','','5.0.2','all');
    wp_register_style('poppins','https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600&display=swap','','1.0','all');

    wp_enqueue_style( 'estilos', get_stylesheet_uri(), array('bootstrap','poppins'), '1.0', 'all');

    wp_register_script( 'popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js', '', '2.9.2', true );
    //wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', '', '3.5.1', true );
    
    wp_enqueue_script('bootstraps','https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js', array('jquery','popper'),'5.0.2',true);

    wp_enqueue_script( 'custom', get_template_directory_uri() . '/assets/js/custom.js', '', '1.0', true );

    wp_localize_script( 'custom', 'dsg', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'apiurl' => home_url('wp-json/dsg/v1/'),
    ) );
}
add_action( 'wp_enqueue_scripts', 'assets');

//CREACION DE UN AREA DE WIDGETS
function sidebar(){
    register_sidebar(
        array(
            'name' => 'Pie de Página',
            'id' => 'footer',
            'description' => 'Zona de widgets para pie de página',
            'before_title' => '<p>',
            'after_title' => '</p>',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' => '</div>',
        ),        
    );
    register_sidebar(
        array(
            'name' => 'Pie de Página 2',
            'id' => 'footer2',
            'description' => 'Zona de widgets para pie de página2',
            'before_title' => '<p>',
            'after_title' => '</p>',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' => '</div>',
        )
    );
}
add_action('widgets_init','sidebar');

//CREACION DE UN CUSTOM POST TYPE (PRODUCTOS)
function productos_type(){
    $labels = array(
        'name' => 'Productos',
        'singular_name' => 'Producto',
        'menu_name' => 'Productos',
    );
    $args =array(
        'label' => 'Productos',
        'descripion' => 'Productos de DSG',
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-cart',
        'can_export' => true,
        'publicly_queryable' => true,
        'rewrite' => true,
        'show_in_rest' => true,
    );
    register_post_type( 'producto', $args);    
}
add_action( 'init','productos_type' );

//CREACION DE UNA TAXONIMIA (CATEGORIA DE PRODUCTOS)
function dsgRegisterTax(){
    $args = array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Categorías de Productos',
            'singular_name' => 'Categoría de Productos',
        ),
        'show_in_nav_menu' => true,
        'show_admin_column' =>true,
        'rewrite' => array(
            'slug' => 'categoria-productos'
        ),
    );
    register_taxonomy( 'categoria-productos', array('producto'), $args );
}
add_action(  'init', 'dsgRegisterTax');

//FILTRO DE PRODUCTOS LLAMADOS DESDE AJAX DE JAVASCRIPT
function dsgFiltroProductos(){
    $args = array(
        'post_type' => 'producto',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'title',
    );
    if($_POST['categoria']){
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'categoria-productos',
                'field' => 'slug',
                'terms' => $_POST['categoria']
            )
            );
    }
    $productos = new WP_Query($args);
    if($productos->have_posts()){
        $return = array();
        while($productos->have_posts(  )){
            $productos->the_post();
            $return[] = array(
                'imagen' => get_the_post_thumbnail( get_the_ID(), 'large' ),
                'link' => get_the_permalink(),
                'titulo' => get_the_title( ),
            );
        }
        wp_send_json($return);
    }
}
add_action("wp_ajax_dsgFiltroProductos",'dsgFiltroProductos');
add_action("wp_ajax_nopriv_dsgFiltroProductos",'dsgFiltroProductos');
/*SE USAN DOS REGISTROS PARA TENER ACCESO TOTAL LOGGED O NOLOGGED
--------------------------------------------------------------------------------*/

//CREACION DE UNA REST API PARA MOSTRAR LAS NOVEDADES EN EL HOME DE NUESTRA PAGINA
add_action('rest_api_init','novedadesAPI');
function novedadesAPI(){
    register_rest_route(
        'dsg/v1',
        '/novedades/(?P<cantidad>\d+)',
        array(
            'method'=> 'GET',
            'callback' => 'pedidoNovedades',
        )
    );
}
function pedidoNovedades($data){
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $data['cantidad'],
        'order' => 'ASC',
        'orderby' => 'title',
    );
    
    $novedades = new WP_Query($args);
    if($novedades->have_posts()){
        $return = array();
        while($novedades->have_posts(  )){
            $novedades->the_post();
            $return[] = array(
                'imagen' => get_the_post_thumbnail( get_the_ID(), 'large' ),
                'link' => get_the_permalink(),
                'titulo' => get_the_title( ),
            );
        }
        return $return;
    }
}

//CREANDO UN NUEVO BLOQUE PERSONALIZADO DE GUTENBERG
function dsgRegisterBlock(){
    $assets = include_once get_template_directory( ) . '/blocks/build/index.asset.php';
    wp_register_script(
        'dsg-block',
        get_template_directory_uri() . '/blocks/build/index.js',
        $assets['dependencies'],
        $assets['version']
    );

    register_block_type(
        'dsg/basic',        
        ['editor_stript' => 'dsg-block']
    );
}
add_action('init','dsgRegisterBlock');