<?php get_header() ?>
<main class="container">
    <?php
        if(have_posts()){
            while(have_posts()){
                the_post(); 
                $taxonomy = get_the_terms( get_the_ID(), 'categoria-productos');
            ?>
                <h1 class="my-3">
                   Este producto es <?php the_title() ?>
                </h1>
                <div class="row my-5">
                    <div class="col-md-6 col-12">
                        <?php the_post_thumbnail( 'large') ?>
                    </div>
                    <div class="col-md-6 col-12">
                        <?php echo do_shortcode('[contact-form-7 id="56" title="Contactanos"]') ?>
                    </div>
                    <div class="col-md-6 col-12">
                        <?php the_content() ?>
                    </div>
                </div>
                <?php
                    $args =array(
                        'post_type' => 'producto',
                        'posts_per_page' => 2,
                        'order' => 'ASC',
                        'orderby' => 'title',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'categoria-productos',
                                'field' => 'slug',
                                'terms' => $taxonomy[0]->slug,
                            ),
                        ),
                    );
                    $producto = new WP_Query($args);
                    if($producto->have_posts()){ ?>
                        <div class="row text-center justift-content-productos-relacionados">                        
                            
                            <div class="col-12">
                                <h3>Productos Relacionados</h3>
                            </div>
                        <?php
                        while($producto->have_posts()){
                            $producto->the_post(); ?>
                                <div class="col-2 my-3 text-center">
                                    <?php the_post_thumbnail( 'thumbnail' ) ?>
                                    <h4>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h4>
                                </div>
                        <?php } ?>
                        </div>
                        <?php
                    }
                ?>
                <div class="col-12">
                    <?php get_template_part('template-parts/post','navigation'); ?>
                </div>
                
           <?php }
        }
    ?>
</main>

<?php get_footer() ?>