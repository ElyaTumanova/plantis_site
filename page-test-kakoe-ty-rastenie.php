<?php get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main test-page">
    <h1 class = "entry-title">Какое ты комнатное растение?</h1>
    <div class="test__intro">
        <picture class="test__cover">
        <!-- Мобильная версия (до 767px) -->
        <source 
            srcset="<?php echo get_template_directory_uri() .'/images/test/test_cover_long.webp'?>" 
            media="(max-width: 768px)">
        
        <!-- Десктопная версия (от 768px и выше) -->
        <source 
            srcset="<?php echo get_template_directory_uri() .'/images/test/test_cover.webp'?>" 
            media="(min-width: 768px)">
        
        <!-- Фолбэк (на случай, если браузер не поддерживает <picture>) -->
        <img 
            src="<?php echo get_template_directory_uri() .'/images/test/test_cover.webp'?>" 
            alt="Тест - Какое ты комнатное растение?">
        </picture>

        <div class="test__disclaimer-text test__disclaimer-text_desktop">
            <p>Внимание! Результат этого теста абсолютно не обязывает вас пересаживаться, поливаться чаще или пытаться фотосинтезировать энергию солнца.</p>
            <p>Все совпадения с вашими чертами характера — случайны, но подозрительно точны.</p>
            <p>Тест не имеет медицинской, психологической или ботанической ценности, зато может помочь определить, кто вы в мире фикусов, замиокулькасов и прочих зелёных гениев.</p>
            <p>Если вы вдруг окажетесь сансевиерией — не обижайтесь. Это прекрасно. А если антуриумом — то это прекраснее вдвойне.</p>
        </div>
        <div class="test__disclaimer-text test__disclaimer-text_mob">
            <p>Внимание! Тест имеет исключительно развлекательный характер. Его результат не обязывает вас пересаживаться или поливаться чаще.</p>
            <p>Все совпадения с вашими чертами характера — случайны, но подозрительно точны.</p>
        </div>
        <div class = "test__init">
            <p class = "test__init-text"><strong>А теперь вас ждет 10 увлекательных вопросов!</strong></p>
            <button class = "test__init-btn button button--green">Поехали!</button>
            <p class="test__disclaimer-agree">Нажимая кнопку "Поехали!", вы соглашаетесь с тем, что разработчики теста душнилы</p>
        </div>
    </div>
    <div class="test__select-gen d-none">
        <legend>Выберите Ваш пол</legend>
        <div class="test__select-gen-wrap">
            <div class="test__gen test__gen-f">
                <input type="radio" name="gender" id="f" value="f" checked>
                <label for="f">Женский</label>
            </div>
            <div class="test__gen test__gen-m">
                <input type="radio" name="gender" id="m" value="m">
                <label for="m">Мужской</label>
            </div>
        </div>
       
        <button class="test__select-gen-btn button button--green">Далее</button>
    </div>
    <div class="test d-none">
      <progress id="progress" value="1" max="10"></progress>
      <h2 class="test__question"></h2>
      <form class = "test__answers-form" id="questionForm">
        <div class = "test__answers">

        </div>
        <button class="test__button button button--green" type="submit">Далее</button>
      </form>
      <img src="" alt="" class = "test__image" loading="lazy">
    </div> 
    <div class = "test__result d-none">
      <p class = "test__result-name h1">Поздравляем!<br><span></span></p>
      <img src="" alt="" class = "test__result-image" loading="lazy">
      <p class = "test__result-descr"></p>
      <div class = "test__result-share">
        <span id="copyShareIcon"></span> 
        <button id="copyShareBtn" type="button" data-url>Поделись результатом</button>
        <div class="test__result-socials">
          <a class="social-media__button button social-media__button-telegram" href="" target = "_blank">
            <?php echo plnt_icon('telegram', 'social-media__button-icon'); ?>
          </a>
          <a class="social-media__button button social-media__button-whatsapp" href="" data-action="share/whatsapp/share" target="_blank" rel="noopener" title="WhatsApp">
            <?php echo plnt_icon('whatsapp', 'social-media__button-icon'); ?>
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