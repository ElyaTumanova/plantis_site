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
        <div class="page-ukhod__intro_small">
           <p>Это просто!</p>
            <!-- <ul>
                <li><b>Мы абсолютно бесплатно пересадим вашего нового друга при одновременной покупке горшка и растения в нашем интернет-магазине;</b></li>
                <li>Поможем подобрать оптимальный размер горшка, и цветок приедет к вам сразу в хорошем грунте.</li>
                <li>У вас дома не будет никакой грязи, вам не нужно искать рецепт “идеального” грунта, ходить в гипермаркет за ним и смотреть видео-инструкции по пересадке. Мы всё сделаем сами!</li>
            </ul> -->
        </div>
      
      </div>
			<div class="page-ukhod__services">
        <h2 class="page-ukhod__services-title">Это просто!</h2>
        <ul class="page-ukhod__services-list">
          <li>Мы абсолютно бесплатно пересадим вашего нового друга при одновременной покупке горшка и растения в нашем интернет-магазине;</li>
          <li>Поможем подобрать оптимальный размер горшка, и цветок приедет к вам сразу в хорошем грунте.</li>
          <li>У вас дома не будет никакой грязи, вам не нужно искать рецепт “идеального” грунта, ходить в гипермаркет за ним и смотреть видео-инструкции по пересадке. Мы всё сделаем сами!</li>
        </ul>
      </div>

      <div class="page-ukhod__service-item">
        <h2 class="page-ukhod__service-item-title">Стандартное обслуживание</h2>
        <div class="page-ukhod__service-item-cost">
          <p>Стоимость:</p> 
          <p>от 18 000 рублей/месяц</p>
        </div>
        <div class="page-ukhod__service-item-period">
          <p>Выезд специалиста:</p>
          <p>1 раз в неделю</p>
        </div>
        <p class="page-ukhod__service-item-descr">Стандартный тариф — это комплексный уход за вашими комнатными растениями. Мы обеспечим регулярный полив, опрыскивание, обрезку, уберём лишнюю пыль с листьев. Проведём профилактику и лечение болезней, подкормим удобрениями по сезону и обновим верхний слой грунта. Вы получаете профессиональный уход, сохраняющий здоровье и внешний вид растений. Тариф идеально подходит для растений в офисах, кафе, ресторанах, где созданы постоянные условия.</p>
        <button class="page-ukhod__service-item-order button page-popup-open-btn" name="Заказать стандартный тариф">Заказать</button>
      </div>

      <div class="page-ukhod__service-item">
        <h2 class="page-ukhod__service-item-title">Гарантийное обслуживание</h2>
        <div class="page-ukhod__service-item-cost">
          <p>Стоимость:</p> 
          <p>от 26 000 рублей/месяц</p>
        </div>
        <div class="page-ukhod__service-item-period">
          <p>Выезд специалиста:</p>
          <p>1 раз в неделю</p>
        </div>
        <div class="page-ukhod__service-item-descr">
          <p>Премиальный уход без компромиссов. Персональный уход за каждым растением с учётом его биологических особенностей: точный полив, опрыскивание, формовочная обрезка, профессиональная гигиена растений и кашпо. Проводим регулярную профилактику, лечим болезни и вредителей, вносим удобрения в соответствии с сезоном, рыхлим и обновляем почву, устанавливаем опоры, пересаживаем по необходимости. В случае утраты декоративности — незамедлительная замена растения за наш счёт. Идеально для премиальных офисов, отелей, салонов и ресторанов, где важна безупречная эстетика интерьера.</p>
          <p>Данная услуга доступна только в том случае, если озеленение проводили специалисты нашей компании.</p>
          <p>Гарантийное обслуживание можно оформить только до дня поставки растений.</p>
        </div>
        <button class="page-ukhod__service-item-order button page-popup-open-btn" name="Заказать гарантийное обслуживание">Заказать</button>
      </div>

      <div class="page-ukhod__service-item">
        <h2 class="page-ukhod__service-item-title">Разовый выезд</h2>
         <div class="page-ukhod__service-item-cost">
          <p>Стоимость:</p> 
          <p>от 6 000 рублей/выезд</p>
        </div>
        <p class="page-ukhod__service-item-descr">Комплексный уход за растениями за один визит. Подойдёт для офисов, кафе, салонов, которые хотят привести растения в порядок без регулярного обслуживания. Консультация, полив, обрезка, обработка от вредителей, подкормка — ваши растения будут здоровы и ухожены после одной встречи.</p>
        <button class="page-ukhod__service-item-order button page-popup-open-btn" name="Заказать разовый выезд">Заказать</button>
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


