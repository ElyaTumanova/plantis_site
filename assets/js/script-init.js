<script>
jQuery(function($){
    $('.nivo-main-banner-gallery').nivoSlider({
        effect: 'slideInLeft',               // эффекты, например: 'fold, fade, sliceDown, sliceDownLeft, sliceUp, sliceUpLeft, sliceUpDown, sliceUpDownLeft, slideInRight, slideInLeft'
        animSpeed: 200,                 // скорость анимации
        pauseTime: 3000,                // пауза между сменой слайдов
        directionNav: true,             // нужно ли отображать кнопки перехода на следующий и предыдущий слайд
        controlNav: true,               // 1,2,3... навигация (например в виде точек)
        pauseOnHover: true,             // останавливать прокрутку слайдов при наведении мыши
        manualAdvance: true,           // true - отключить автопрокрутку
        prevText: '&#10094;',               // текст перехода на предыдущий слайд
        nextText: '&#10095;',               // текст кнопки перехода на следующий слайд
        randomStart: false,             // начинать со случайного слайда
    })
});
</script>