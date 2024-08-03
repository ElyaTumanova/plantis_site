jQuery(document).ready(function($){
    'use strict';
    // настройки по умолчанию. Их можно добавить в имеющийся js файл,
    // если datepicker будет использоваться повсеместно на проекте и предполагается запускать его с разными настройками
    $.datepicker.setDefaults({
        closeText: 'Закрыть',
        prevText: '<Пред',
        nextText: 'След>',
        currentText: 'Сегодня',
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        weekHeader: 'Нед',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        showAnim: 'slideDown',
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    } );

    // Инициализация
    $('input[id*="datepicker"], .datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
    
});