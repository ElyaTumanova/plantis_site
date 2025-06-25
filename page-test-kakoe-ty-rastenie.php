<?php get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
    <h1>This is test</h1>
    <div class="test">
      <h2 class="test__question"></h2>
      <form class = "test__answers-form">
        <div class = "test__answers">

        </div>
        <button type="submit">Далее</button>
        <p class = "test__error d-none">Выберите вариант ответа</p>
      </form>
      <div class = "test__result d-none">
        <p class = "test__result-name"></p>
        <p class = "test__result-descr"></p>
        <img src="" alt="" class = "test__result-image" loading="lazy">
        <a href="" class = "test__result-link">Поделиться</a>
      </div>
    </div> 
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();?>