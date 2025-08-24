<?php get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main test-page">
    <h1 class = "entry-title">Какое ты растение?</h1>
    <div class="test__disclaimer">
        <div class="test__disclaimer-text">
            <p><strong>Внимание! Результат этого теста абсолютно не обязывает вас пересаживаться, поливаться чаще или пытаться фотосинтезировать энергию солнца.</strong></p>
            <p>Все совпадения с вашими чертами характера — случайны, но подозрительно точны.</p>
            <p>Тест не имеет медицинской, психологической или ботанической ценности, зато может помочь определить, кто вы в мире фикусов, замиокулькасов и прочих зелёных гениев.</p>
            <p>Если вы вдруг окажетесь сансевиерией — не обижайтесь. Это прекрасно. А если антуриумом — то это прекраснее вдвойне.</p>
            <p><strong>С любовью, ваш зелёный ироничный внутренний сад!</strong></p>
        </div>
        <div class="test__select-gen">
            <legend>Выберите Ваш пол</legend>
            <input type="radio" name="gender" id="f" value="f" checked>
            <label for="f">Женский</label>

            <input type="radio" name="gender" id="m" value="m">
            <label for="m">Мужской</label>
        </div>
        <button class = "test__init-btn button">Начать тест</button>
        <p class="test__disclaimer-agree">Нажимая кнопку "Начать тест", вы соглашаетесь с тем, что его разработчики душнилы</p>
    </div>
    <div class="test d-none">
      <h2 class="test__question"></h2>
      <form class = "test__answers-form">
        <div class = "test__answers">

        </div>
        <button class="test__button button" type="submit">Далее</button>
        <p class = "test__error">Выберите вариант ответа</p>
      </form>
      <img src="" alt="" class = "test__image" loading="lazy">
    </div> 
    <div class = "test__result d-none">
      <p class = "test__result-name">Поздравляем!<br><span></span></p>
      <p class = "test__result-descr"></p>
      <img src="" alt="" class = "test__result-image" loading="lazy">
      <div class = "test__result-share">
        <!-- <a class = "test__result-link" href="">Поделись результатом</a> -->
        <span id="copyShareIcon"></span> 
        <button id="copyShareBtn" type="button" data-url>Поделись результатом</button>
        <div>
          <a class="social-media__button button social-media__button-telegram" href="" target = "_blank">
            <svg class="social-media__button-icon" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm121.8 169.9l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z"></path></svg>
          </a>
          <a class="social-media__button button social-media__button-whatsapp" href="" data-action="share/whatsapp/share" target="_blank" rel="noopener" title="WhatsApp">
            <svg class="social-media__button-icon" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg>
          </a>
          <a class="social-media__button button social-media__button-ok" href="" target = "_blank">
            <svg class="social-media__button-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M19.339 23.255a15.57 15.57 0 0 0 4.801-1.989a2.42 2.42 0 0 0-2.577-4.094a10.504 10.504 0 0 1-11.125 0a2.42 2.42 0 0 0-3.333.749v.005a2.413 2.413 0 0 0 .756 3.333l.004.005a15.421 15.421 0 0 0 4.792 1.985l-4.62 4.619a2.394 2.394 0 0 0-.036 3.381l.041.041c.459.473 1.079.708 1.699.708s1.239-.235 1.697-.708l4.563-4.537l4.536 4.543c.964.921 2.495.9 3.423-.063a2.418 2.418 0 0 0 0-3.36zM16 16.516a8.265 8.265 0 0 0 8.26-8.256C24.26 3.708 20.552 0 16 0S7.74 3.708 7.74 8.26A8.27 8.27 0 0 0 16 16.521zm0-11.672a3.418 3.418 0 0 1 3.416 3.416A3.424 3.424 0 0 1 16 11.683a3.43 3.43 0 0 1-3.421-3.423A3.433 3.433 0 0 1 16 4.839z"/></svg>
          </a>
          <a class="social-media__button button social-media__button-vk" href="" target = "_blank">
            <svg class="social-media__button-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path d="M21.579 6.855c.14-.465 0-.806-.662-.806h-2.193c-.558 0-.813.295-.953.619c0 0-1.115 2.719-2.695 4.482c-.51.513-.743.675-1.021.675c-.139 0-.341-.162-.341-.627V6.855c0-.558-.161-.806-.626-.806H9.642c-.348 0-.558.258-.558.504c0 .528.79.65.871 2.138v3.228c0 .707-.127.836-.407.836c-.743 0-2.551-2.729-3.624-5.853c-.209-.607-.42-.852-.98-.852H2.752c-.627 0-.752.295-.752.619c0 .582.743 3.462 3.461 7.271c1.812 2.601 4.363 4.011 6.687 4.011c1.393 0 1.565-.313 1.565-.853v-1.966c0-.626.133-.752.574-.752c.324 0 .882.164 2.183 1.417c1.486 1.486 1.732 2.153 2.567 2.153h2.192c.626 0 .939-.313.759-.931c-.197-.615-.907-1.51-1.849-2.569c-.512-.604-1.277-1.254-1.51-1.579c-.325-.419-.231-.604 0-.976c.001.001 2.672-3.761 2.95-5.04"/></svg>
          </a>
        </div>

      </div>
      <div class="test__result-upsells"></div>
    </div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();?>