<?php get_header(); ?>

<main class="container">
    <?php 
        if(have_posts()){
            while(have_posts()){
                the_post();
                ?>
                <h1 class="my-3">
                    <?php the_title(); ?> !!                
                </h1>
                <?php the_content(); ?>
                <?php
            }
        }
    ?>
    <div class="Title my-5">
        <h2 class="text-center">PRODUCTOS</h2>
        <div class="row">
            <div class="col-12">
                <select class="form-control" name="categorias-productos" id="categorias-productos">
                    <option value="">Todas las Categorías</option>
                    <?php
                        $terms = get_terms('categoria-productos', array('hide_empty' => true));
                        foreach($terms as $term){
                            echo '<option value="' . $term->slug .'">' . $term->name . '</option>';
                        }
                    ?>
                </select>
            </div>
        <?php
        
        $args =array(
            'post_type'=> 'producto',
            'post_per_page' => -1, //Trae todos los productos   
            'order'      => 'ASC',
            'orderby' => 'title'
        ); ?>
        </div>
        </div>
        <div class="lista-productos my-5">
            <div class="row">
        <?php

        $productos = new WP_Query($args);
        if($productos->have_posts()){
            while($productos->have_posts()){
                $productos-> the_post();
                ?>
                <div class="col-md-4 col-12 my-3 text-center">
                    <figure>
                        <?php the_post_thumbnail('large'); ?>
                    </figure>
                    <h4 class="my-2">
                        <a href="<?php the_permalink() ?>">
                            <?php the_title() ?>
                        </a>
                    </h4>
                </div>

            <?php }
        }
        ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>