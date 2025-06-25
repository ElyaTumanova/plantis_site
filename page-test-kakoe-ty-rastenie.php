<?php get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main test-page">
    <h1 class = "entry-title">Какое ты растение?</h1>
    <div class="test">
      <h2 class="test__question"></h2>
      <form class = "test__answers-form">
        <div class = "test__answers">

        </div>
        <button class="test__button button" type="submit">Далее</button>
      </form>
      <p class = "test__error">Выберите вариант ответа</p>
      <img src="" alt="" class = "test__image" loading="lazy">
    </div> 
    <div class = "test__result d-none">
      <p class = "test__result-name"></p>
      <p class = "test__result-descr"></p>
      <img src="" alt="" class = "test__result-image" loading="lazy">
      <div class = "test__result-share">
        <!-- <a href="" class = "test__result-link">Поделись результатом</a> -->
        <a class="social-media__button button social-media__button-telegram" href="">
          <svg class="social-media__button-icon" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm121.8 169.9l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z"></path></svg>
        </a>

      </div>
    </div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();?>