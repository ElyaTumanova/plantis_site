<?php 
$close_icon = carbon_get_theme_option('close_icon');

get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
    <div class="page-peresadka service-page">
      <div class="page-peresadka__first-screen">
        <h1 class="page-peresadka__entry-title entry-title">Услуги по пересадке комнатных растений</h1> 
        <img class="page-peresadka__photo" loading="lazy" src="<?php echo get_template_directory_uri()?>/images/lanscaping.webp" alt="Пересадка комнатных растений">               
        <div class="page-peresadka__intro">
          Вы хотите купить комнатное растение. И&nbsp;теперь перед вами стоит задача его пересадить. Но вы не знаете с чего начать?
        </div>
        <div class="page-peresadka__buttons">
          <button class="page-peresadka__rasschet button page-popup-open-btn" name="Заявка на пересадку">Оставить заявку</button>
          <?php get_template_part('template-parts/social-media-btns');?> 
        </div>
             
      </div>
			<div class="page-peresadka__services">
        <h2 class="page-peresadka__services-title">Это просто!</h2>
        <ul class="page-peresadka__services-list">
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
        
      <div class="page-peresadka__service-item">
        <h2 class="page-peresadka__service-item-title">На маркетплейсе есть именно то, что вам нужно с перламутровыми пуговицами и без крыльев?</h2>
        <div class="page-peresadka__service-item-descr">
           <p>Почему бы и нет?!?!</p>
            <ul>
                <li aria-level="1">Мы подскажем вам оптимальные размеры горшка для растения;</li>
                <li aria-level="1">Вы закажете кашпо или горшок, оформите доставку в ближайший к нам пункт выдачи маркетплейса;</li>
                <li aria-level="1">Мы сами сходим и заберём его из пункта выдачи;</li>
                <li aria-level="1">Сделаем всю грязную работу за вас и отправим его к вам домой.</li>
            </ul>
        </div>
      </div>

      <div class="page-peresadka__service-item">
        <h2 class="page-peresadka__service-item-title">Хотите пересадить растение у вас дома, в коттедже или офисе?</h2>
        <div class="page-peresadka__service-item-descr">
            <p>Наши специалисты приедут к вам в гости, сделают всю грязную работу, пересадят растения, проконсультируют по уходу и с чувством выполненного долга уедут обратно в нашу мастерскую.</p>
            <p>Услуга пересадки оплачивается отдельно. Стоимость выезда биолога:</p>
            <ul>
                <li aria-level="1">Внутри МКАД – 3000 рублей;</li>
                <li aria-level="1">За пределы МКАД до 10 км – 4000 рублей;</li>
                <li aria-level="1">Дальше 10 км от МКАД обговаривается индивидуально.</li>
            </ul>
        </div>
      </div>
      <div class="page-peresadka__example">
        <h2 class="page-peresadka__example-heading">Пример расчёта стоимости пересадки растений с выездом биолога:</h2>
        <div class="page-peresadka__example-descr">
          <p>Вы – очень занятой человек, который живёт в пределах МКАД, и хотите доверить работу нам.
          <br>У вас есть два фикуса Бенджамина в кашпо диаметром 30 см и родовой хлорофитум в 20-ом горшке. 
          <br>Вы приняли решение пересадить фикусы в кашпо диаметром 40 см, а хлорофитум в 25-ый горшок.</p>
        </div>
        <div class="page-peresadka__example-rasschet">
          <p>Стоимость наших услуг составит:</p>
              <table class="info__table">
                <tbody>
                <tr>
                <td><b>Услуга</b></td>
                <td><b>Стоимость, руб</b></td>
                </tr>
                <tr>
                <td>Выезд биолога внутри МКАД</td>
                <td>3 000</td>
                </tr>
                <tr>
                <td>Пересадка растения в кашпо от 29 до 40см</td>
                <td>2 000</td>
                </tr>
                <tr>
                <td>Пересадка растения в кашпо от 29 до 40см</td>
                <td>2 000</td>
                </tr>
                <tr>
                <td>Пересадка растения в кашпо от 22 до 28см</td>
                <td>1 000</td>
                </tr>
                <tr>
                <td><b>Итого</b></td>
                <td><b>8 000</b></td>
                </tr>
                </tbody>
              </table>
        </div>
        <button class="page-peresadka__rasschet button page-popup-open-btn" name="Заявка на пересадку">Оставить заявку</button>
      </div>
		</div>

    
	</main><!-- #main -->
</div><!-- #primary -->


<?php
get_template_part('template-parts/popups/service-popup');
get_footer();?>


