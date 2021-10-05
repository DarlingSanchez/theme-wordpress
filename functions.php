<?php

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

function assets(){
    wp_register_style('bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css','','5.0.2','all');
    wp_register_style('poppins','https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;600&display=swap','','1.0','all');

    wp_enqueue_style( 'estilos', get_stylesheet_uri(), array('bootstrap','poppins'), '1.0', 'all');

    wp_register_script( 'popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js', '', '2.9.2', true );

    wp_enqueue_script('bootstraps','https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js',array('jquery','popper'),'5.0.2',true);

}

add_action( 'wp_enqueue_scripts', 'assets');

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