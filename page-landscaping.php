<?php 
$close_icon = carbon_get_theme_option('close_icon');

get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
    <div class="page-lanscaping service-page">
      <div class="page-peresadka__first-screen">
        <h1 class="page-peresadka__entry-title entry-title">Озеленение офисов</h1> 
        <img class="page-peresadka__photo" loading="lazy" src="<?php echo get_template_directory_uri()?>/images/landscaping.webp" alt="Пересадка комнатных растений">               
        <div class="page-peresadka__intro">
          <p>Мечтаете наполнить офис живыми растениями, но не знаете, с чего начать? </p>
          <p>Это нормально — первый шаг всегда кажется самым трудным. </p>
          <p>Мы поможем вам его сделать.</p>
        </div>
        <div class="page-peresadka__buttons">
          <button class="page-peresadka__rasschet button page-popup-open-btn" name="Заявка на пересадку">Оставить заявку</button>
          <?php get_template_part('template-parts/social-media-btns');?> 
        </div>
             
      </div>
			<div class="page-peresadka__services">
        <div class="page-peresadka__services-title"><h2>В Plantis мы создаём индивидуальные решения.</h2> 
          <p>Наши проекты выбирают потому, что мы:</p>
        </div>
        <ul class="page-peresadka__services-list">
          <li>Работаем только со здоровыми растениями от лучших садовников Европы.</li>
          <li>Создаём проекты «под ключ» — с фиксированной ценой и прозрачными условиями оплаты.</li>
          <li>Создаём комфортное зелёное пространство.</li>
          <li>Консультируем вас после покупки по уходу или сами занимаемся уходом за растениями.</li>
        </ul>
      </div>

      <div class="page-ukhod__advantages">
        <h2 class="page-ukhod__advantages-title">Зачем нужно озеленение офисов?</h2>
        <ul class="page-ukhod__advantages-item">
          <li>
              <p>Престиж и доверие к бренду</p> 
              <p>Профессиональное озеленение офиса мгновенно повышает статус компании в глазах клиентов, кандидатов и просто гостей.</p>
          </li>
          <li>
              <p>Здоровый эмоциональный фон</p> 
              <p>Зеленые композиции снижают уровень стресса и поддерживают доброжелательную атмосферу в коллективе.</p>
          </li>
          <li>
              <p>Качество воздуха</p> 
              <p>Живые растения очищают и увлажняют воздух, создавая комфортный микроклимат для ежедневной работы.</p>
          </li>
          <li>
              <p>Зонирование</p> 
              <p>Растения добавят уюта помещению. Грамотное озеленение поможет разделить офис на зоны.</p>
          </li>
        </ul>
      </div>

      <div class="page-peresadka__services page-peresadka__services_btn">
        <h2 class="page-peresadka__services-title">Как выглядит процесс?</h2>
        <ul class="page-peresadka__services-list page-peresadka__services-list_nums">
          <li>
              <p>01</p>
              <div>
                  <p>Заявка</p>
                  <p>Вы оставляете заявку - мы сразу берём процесс в работу.</p>
              </div>
          </li>
          <li>
              <p>02</p>
              <div>
                  <p>Выезд и диагностика</p>
                  <p>Наш специалист приезжает к вам в офис и оценивает условия - освещение, влажность, температуру и т.д.</p>
              </div>
          </li>
          <li>
              <p>03</p>
              <div>
                  <p>Согласование проекта</p>
                  <p>Обсуждаем задачи, уточняем детали и утверждаем концепцию.</p>
              </div>
          </li>
          <li>
              <p>04</p>
              <div>
                  <p>Договор</p>
                  <p>После подписания и оплаты проект переходит в реализацию.</p>
              </div>
          </li>
          <li>
              <p>05</p>
              <div>
                  <p>Озеленение</p>
                  <p>Мы приступаем к озеленению - аккуратно, профессионально и в срок.</p>
              </div>
          </li>
        </ul >
        <button class="page-peresadka__rasschet button page-popup-open-btn" name="Заявка на пересадку">Оставить заявку</button>
      </div>

      <div class="page-peresadka__service-item">
        <h2 class="page-peresadka__service-item-title">Сколько стоит озеленение “под ключ”?</h2>
        <p class="page-peresadka__service-item-descr">Стоимость озеленения зависит от большего количества факторов - количества растений, высоты, материала кашпо и т.д. Для вашего удобства мы выделили три условных тарифа, чтобы вам было проще ориентироваться:</p>
        <ul class="page-ukhod__advantages-item">
          <li>
            <p>Акцент</p> 
            <p>от&nbsp;15&nbsp;000 рублей </p>
            <p>4-7 небольших растений для ресепшн, кабинета, переговорной или столовой</p>
          </li>
          <li>
              <p>Среда</p> 
              <p>от&nbsp;40&nbsp;000 рублей</p>
              <p>2-3 напольных растений для кабинета руководителя или наполнение зелёными акцентами офиса площадью до 100кв.м.</p>
          </li>
          <li>
              <p>Экосистема</p> 
              <p>от&nbsp;100&nbsp;000 рублей</p>
              <p>Разработка проекта уникального дизайна для полной трансформации помещения</p>
          </li>
        </ul>
      </div>
      <div class="page-peresadka__service-item">
        <h2 class="page-peresadka__service-item-title">Профессиональное обслуживание</h2>
        <p class="page-peresadka__service-item-descr">Чтобы растения всегда выглядели безупречно и оставались здоровыми, мы можем предложить вам постпродажное обслуживание. Посмотреть тарифы на обслуживание растений вы можете здесь (ссылка).</p>
      </div>
		</div>

    <div class="faq-items">
      <div class="faq-item">
        <div class="faq-question">
          <h3>Сколько времени занимает озеленение?</h3>
        </div>
        <div class="faq-answer">
          <p>Небольшие проекты можно реализовать за 1-2 дня, но, как правило, озеленение занимает 7-10 дней. Сложные проекты могут требовать больше времени.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Работаете ли вы с небольшими офисами?</h3>
        </div>
        <div class="faq-answer">
          <p>Да. Мы подбираем решения для любых пространств — от кабинетов до больших open space.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Можно ли озеленить офис, где мало света?</h3>
        </div>
        <div class="faq-answer">
          <p>Да. В таких случаях мы используем теневыносливые растения.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Сколько живут растения?</h3>
        </div>
        <div class="faq-answer">
          <p>При регулярном уходе — годами. Мы подбираем устойчивые виды под ваши условия.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Нужен ли растениям регулярный уход?</h3>
        </div>
        <div class="faq-answer">
          <p>Да. Даже неприхотливые растения требуют ухода. Мы предлагаем сервисное обслуживание - от регулярного полива до полной замены растений при необходимости.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Какие документы вы предоставляете?</h3>
        </div>
        <div class="faq-answer">
          <p>Мы работаем по договору, предоставляем полный пакет закрывающих документов и все необходимые сертификаты соответствия, а также сертификаты фитосанитарного контроля.</p>
        </div>
      </div>
    </div>

    
	</main><!-- #main -->
</div><!-- #primary -->


<?php
get_template_part('template-parts/popups/service-popup');
get_footer();?>


