<?php get_header(); 

$plant_types = require get_theme_file_path('assets/data/plant-types.php');
$plants_by_slug = [];
foreach ($plant_types as $it) { $plants_by_slug[$it['slug']] = $it; };

?>

<main class="result-test container">
    <?php 
    $gen = get_query_var('gen');
    $plant = get_query_var('plant');
    // echo('<pre>');
    // print_r($plant);
    // print_r($plants_by_slug);
    // echo('</pre>');
    ?>
    <?php if (array_key_exists($plant, $plants_by_slug)):?>
        <div class="test__result">
            <h1 class="test__result-name h1">Поздравляем! <br>
            Вы <?php echo $plants_by_slug[$plant]['name']?>!
            </h1>
            <img class="test__result-image" src="<?php echo $plants_by_slug[$plant]['image'][$gen]?>" alt = "<?php echo $plants_by_slug[$plant]['name']?>">
            <p class="test__result-descr"><?php echo $plants_by_slug[$plant]['result']?></p>
            <?php $test_link = site_url().'/test-kakoe-ty-rastenie';?>
            <div class="test__result-share">
                <a class="take-test button button--green" href='<?php echo $test_link?>' target = "_blank">Пройти тест</a>
                <div class = "test-share">
                    <span id="copyShareIcon"></span> 
                    <button id="copyShareBtn" type="button" data-url="<?php echo $test_link?>">Поделись тестом</button>
                    <div class="test__result-socials">
                        <a class="social-media__button button social-media__button-telegram" href="https://telegram.me/share/url?url=<?php echo $test_link?>&text=Пройди тест - Какое ты растение?" target = "_blank">
                            <?php echo plnt_icon('telegram', 'social-media__button-icon'); ?>
                        </a>
                        <a class="social-media__button button social-media__button-whatsapp" href="https://wa.me/?text=<?php echo urlencode("Пройди тест - Какое ты растение? - " . $test_link); ?>" data-action="share/whatsapp/share" target="_blank" rel="noopener" title="WhatsApp">
                            <?php echo plnt_icon('whatsapp', 'social-media__button-icon'); ?>
                        </a>
                        <a class="social-media__button button social-media__button-ok" href="https://connect.ok.ru/offer?url=<?php echo $test_link?>&title=Пройди тест - Какое ты растение?" target = "_blank">
                            <svg class="social-media__button-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M19.339 23.255a15.57 15.57 0 0 0 4.801-1.989a2.42 2.42 0 0 0-2.577-4.094a10.504 10.504 0 0 1-11.125 0a2.42 2.42 0 0 0-3.333.749v.005a2.413 2.413 0 0 0 .756 3.333l.004.005a15.421 15.421 0 0 0 4.792 1.985l-4.62 4.619a2.394 2.394 0 0 0-.036 3.381l.041.041c.459.473 1.079.708 1.699.708s1.239-.235 1.697-.708l4.563-4.537l4.536 4.543c.964.921 2.495.9 3.423-.063a2.418 2.418 0 0 0 0-3.36zM16 16.516a8.265 8.265 0 0 0 8.26-8.256C24.26 3.708 20.552 0 16 0S7.74 3.708 7.74 8.26A8.27 8.27 0 0 0 16 16.521zm0-11.672a3.418 3.418 0 0 1 3.416 3.416A3.424 3.424 0 0 1 16 11.683a3.43 3.43 0 0 1-3.421-3.423A3.433 3.433 0 0 1 16 4.839z"/></svg>
                        </a>
                        <a class="social-media__button button social-media__button-vk" href="https://vk.com/share.php?url=<?php echo $test_link?>" target = "_blank">
                            <svg class="social-media__button-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path d="M21.579 6.855c.14-.465 0-.806-.662-.806h-2.193c-.558 0-.813.295-.953.619c0 0-1.115 2.719-2.695 4.482c-.51.513-.743.675-1.021.675c-.139 0-.341-.162-.341-.627V6.855c0-.558-.161-.806-.626-.806H9.642c-.348 0-.558.258-.558.504c0 .528.79.65.871 2.138v3.228c0 .707-.127.836-.407.836c-.743 0-2.551-2.729-3.624-5.853c-.209-.607-.42-.852-.98-.852H2.752c-.627 0-.752.295-.752.619c0 .582.743 3.462 3.461 7.271c1.812 2.601 4.363 4.011 6.687 4.011c1.393 0 1.565-.313 1.565-.853v-1.966c0-.626.133-.752.574-.752c.324 0 .882.164 2.183 1.417c1.486 1.486 1.732 2.153 2.567 2.153h2.192c.626 0 .939-.313.759-.931c-.197-.615-.907-1.51-1.849-2.569c-.512-.604-1.277-1.254-1.51-1.579c-.325-.419-.231-.604 0-.976c.001.001 2.672-3.761 2.95-5.04"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class = "test__result-upsells">
                <?php
                // get_template_part('template-parts/products/products-plants-test',null,
                // array( // массив с параметрами
                //     'cat_slug' => $plant,
                // ));

                get_template_part('template-parts/products/product-slider', null, [
                  'queryArgs' => [
                    'tax_query' => [
                      [
                          'taxonomy' => 'product_cat',
                          'field'    => 'slug',
                          'terms'    => $plant,
                      ],
                    ],
                  ],
                ]);
            ?>
            </div>
        </div>
    <?php else:?>
        <div>
            <section class="error-404 not-found">		
                <div class="page-content">
                    <img width="800" height="286" src="<?php echo get_template_directory_uri()?>/images/404.png" alt="Такой страницы нет">
                    <p>Упс! Похоже что-то пошло не так…</p>
                    <p>Давайте начнем сначала!</p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button" role="button">На главную</a>
                </div><!-- .page-content -->
            </section><!-- .error-404 -->
        </div>
    <?php endif;?>

    </main>

<?php get_footer();?>
