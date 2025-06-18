<?php 
$close_icon = carbon_get_theme_option('close_icon');

get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
    <div class="page-peresadka service-page">
      <div class="service-page__first-screen">
        <h1 class="page-ukhod__entry-title entry-title">Услуги по пересадке комнатных растений</h1> 
        <img class="page-ukhod__photo" src="https://plantis.shop/wp-content/uploads/2022/01/16.10-2.jpg" alt="Профессиональный уход за растениями">               
        <div class="page-ukhod__intro">
          Вы хотите купить комнатное растение. И теперь перед вами стоит задача его пересадить. Но вы не знаете с чего начать?
        </div>
        <button class="page-ukhod__rasschet button page-popup-open-btn" name="Заявка на пересадку">Оставить заявку</button>
        <?php get_template_part('template-parts/social-media-btns');?>      
      </div>
			<div class="page-ukhod__services">
        <h2 class="page-ukhod__services-title">Это просто!</h2>
        <ul class="page-ukhod__services-list">
          <li>Мы абсолютно бесплатно пересадим вашего нового друга при одновременной покупке горшка и растения в нашем интернет-магазине;</li>
          <li>Поможем подобрать оптимальный размер горшка, и цветок приедет к вам сразу в хорошем грунте.</li>
          <li>У вас дома не будет никакой грязи, вам не нужно искать рецепт “идеального” грунта, ходить в гипермаркет за ним и смотреть видео-инструкции по пересадке. Мы всё сделаем сами!</li>
        </ul>
      </div>

      <div class="page-peresadka__service-item">
        <h2 class="page-peresadka__service-item-title">А можно пересаживать растение сразу после покупки?</h2>
        <p class="page-peresadka__service-item-descr">Конечно, иногда даже нужно! Практически все комнатные растения в нашем магазине продаются в пластиковых транспортировочных горшках. Это временное жилище, которое лучше заменить при первой возможности. Такой горшок не представляет эстетической ценности, а ограниченный объём замедлит развитие вашего нового друга.</p>
      </div>
      <div class="page-peresadka__service-item">
        <h2 class="page-peresadka__service-item-title">У вас уже есть горшок или кашпо?</h2>
        <div class="page-peresadka__service-item-descr">
          <p >Никаких проблем! Пересадка в ваш горшок, который вы приобрели не у нас, будет стоить:</p>
          <table class="info__table">
            <tbody>
            <tr>
            <td><b>Диаметр горшка/кашпо</b></td>
            <td><b>Стоимость, руб</b></td>
            </tr>
            <tr>
            <td>до 12 см</td>
            <td>200 рублей</td>
            </tr>
            <tr>
            <td>от 13 до 15 см</td>
            <td>400 рублей</td>
            </tr>
            <tr>
            <td>от 16 до 21 см</td>
            <td>600 рублей</td>
            </tr>
            <tr>
            <td>от 22 до 28 см</td>
            <td>1000 рублей</td>
            </tr>
            <tr>
            <td>от 29 до 40 см</td>
            <td>2000 рублей</td>
            </tr>
            </tbody>
          </table>
          <p>В стоимость включена работа, грунт и дренаж. Дополнительно ничего платить не нужно.</p>
      </div>
        </div>
        

   
      <div class="page-ukhod__advantages">
        <h2 class="page-ukhod__advantages-title">Почему уход за растениями стоит доверить нам?</h2>
        <ul class="page-ukhod__advantages-item">
          <li>Ваша задача — бизнес. Наша — забота о растениях. Уберём все хлопоты и контроль за уходом возьмем на себя.</li>
          <li>Поддерживаем имидж — ухоженные растения подчёркивают статус и заботу о деталях в вашем бизнесе.</li>
          <li>Привлечём внимание гостей и клиентов: живые, ухоженные растения создают атмосферу уюта и престижа.</li>
          <li>Создаём уют и атмосферу — здоровые растения повышают комфорт и впечатление ваших клиентов и сотрудников.</li>
          <li>Работаем с любыми объемами: от небольших кафе до крупных офисных центров.</li>
        </ul>
      </div>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<div class="page-popup popup ukhod-popup">
    <div class="page-popup__container">
        <div class="page-popup__wrap">
            <h2 class="page-popup__heading heading-2">Уход за растениями</h2>
            <span class="page-popup__close heading-2"><?php echo $close_icon ?></span>
            <div class="page-popup__form"><?php echo do_shortcode('[contact-form-7 id="64580" title="Уход за растениями"]')?></div>
            <p class="page-popup__text">Нажимая кнопку "Отправить", вы даете согласие на обработку своих персональных данных и соглашаетесь с положениями, 
                описанными в нашей <a class="page-popup__link" target="blank" href="<?php get_site_url()?>/privacy-policy/">политике конфиденциальности</a>.</p>
        </div>
    </div>
    <div class="page-popup__popup-overlay popup-overlay"></div>
</div>	

<?php get_footer();?>


