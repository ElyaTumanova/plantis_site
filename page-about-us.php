<?php
get_header(); ?>

<?php 
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');
?>

<div class="content-area content-area_sidebar">
    <aside class='info-menu-sidebar'>  
        <?php get_template_part('template-parts/info-pages-list');?> 
    </aside> 
		<div class="entry-header container">
			<h1 class="entry-title about__title">О нас</h1>                
    </div>
		<div class="about info__content">
      <div class="about__wrap container">
          <div>
              <div class="info__accent-text-upper">Мы хотим стать для вас местом, где вы поймёте, что выращивание комнатных растений простой процесс, который не требует много сил и специфических знаний.</div>
              <h2 class="info__heading heading-2">Кто мы такие?</h2>
              <div class="info__paragraph info__list">
                  <p>Мы – те, кто однажды проснулись и поняли, что хотим сделать удобный сервис покупки комнатных растений.</p>
                  <p>У нас есть хороший опыт предоставления клиентского сервиса. Все заботы мы берём на себя. Вам не нужно выходить из дома.</p>
                  <ul>
                      <li>В нашем каталоге мы используем только оригинальные фотографии;</li>
                      <li>Перед отправкой вы получите фотографии именно вашего растения;</li>
                      <li>Если вы покупаете растение вместе с горшком, то мы абсолютно бесплатно пересадим его в хороший грунт;</li>
                      <li>Вам не нужно ждать, потому что у нас есть доставка “День в день”<?php if($min_free_delivery) { echo '. А при заказе от '.$min_free_delivery.' рублей, мы доставим нового друга бесплатно';}?>;</li>
                      <li>Мы не пропадём после продажи, и вы всегда можете написать нам или позвонить. Мы обязательно проконсультируем по уходу и дадим совет;</li>
                      <li>А ещё мы не любим формальный подход, поэтому делаем всё с любовью и вовлеченностью.</li>
                  </ul>					
              </div>
  
              <h2 class="info__heading heading-2">Почему мы – хорошее место?</h2>
              <div class="info__paragraph">
                  <p>У нас работают люди, которые сами выращивают растения и способны дать консультацию по любому вопросу, связанному с уходом за растением.</p>
                  <p>Мы думаем не только о том, как продать вам новое растение, мы думаем, как его органично вписать в ваш быт, квартиру, вашу занятость. И если поймём, что этот цветок вам не подходит, то честно скажем вам об этом. А вы сами уже примете решение.</p>				
              </div>
  
              <h2 class="info__heading heading-2">Почему мы решили заняться горшечными растениями?</h2>
              <div class="info__paragraph">
                  <p>Мы поняли, что наличие цветов в доме способствует повышению качества жизни и уровню счастья человека, и теперь хотим поделиться этим знанием с вами.</p>
                  <p>Для нас самым главным оказался тот факт, что зелёные друзья оказывают влияние на эмоциональное состояние.</p>
                  <p>Растения дома помогают снизить стресс и усталость, а также повысить продуктивность и реализовать свой потенциал. Они поднимают настроение и помогают нам чувствовать себя более расслабленными и защищенными.</p>
              </div>
          </div>

          <!-- <div class="about__slider about__slider_photo">
              <div class="about__swiper-photo swiper">
                  <div class="swiper-wrapper">
                      <div class="swiper-slide">
                          <img loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_4.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                      <div class="swiper-slide">
                          <img  loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_2.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                      <div class="swiper-slide">
                          <img  loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_7.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                      <div class="swiper-slide">
                          <img  loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_9.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                      <div class="swiper-slide">
                          <img  loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_8.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                      <div class="swiper-slide">
                          <img  loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_3.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                      <div class="swiper-slide">
                          <img  loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_6.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                      <div class="swiper-slide">
                          <img  loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_5.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                      <div class="swiper-slide">
                          <img  loading="lazy" src="https://plantis.shop/wp-content/uploads/2022/11/%D0%9C%D0%B0%D1%81%D1%82%D0%B5%D1%80%D1%81%D0%BA%D0%B0%D1%8F-Plantis_1.webp" alt="Мастерская Plantis">
                          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                      </div>
                  </div>
                  <div class="swiper-pagination"></div>
                  <div class="swiper-button-prev"></div>
                  <div class="swiper-button-next"></div>
              </div>
          </div> -->
      </div>
      <!-- <div class="about__full-width-wrap">
          <h2 class="entry-title container">Отзывы</h2> 
          <div class="about__slider about__slider_feedback container">
                  <div class="about__swiper-feedback swiper">
                      <div class="swiper-wrapper">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/12.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/14.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/13.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/01.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/02.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/03.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/04.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/05.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/06.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/07.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/08.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/09.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/10.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/11.webp" alt="Отзывы об интернет-магазине Plantis">
                          <img class="swiper-slide" src="https://plantis.shop/wp-content/uploads/2024/02/15.webp" alt="Отзывы об интернет-магазине Plantis">
                      </div>
                      <div class="swiper-pagination"></div>
                      <div class="swiper-button-prev"></div>
                      <div class="swiper-button-next"></div>
                  </div>  
                
          </div>                   
      </div> -->
      
      <div class="reviews-card">
        <h2>Отзывы о нас</h2>
        <div class="reviews-frame">
          <iframe src="https://yandex.ru/maps-reviews-widget/237252555639?comments"
                  loading="lazy"
                  frameborder="0"
                  allowfullscreen>
          </iframe>
          <div class="reviews-mask" aria-hidden="true"></div>
        </div>
      </div>
		</div>

</div><!-- #primary -->

<?php get_footer(); ?>