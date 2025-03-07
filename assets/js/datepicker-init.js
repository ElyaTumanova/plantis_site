/*--------------------------------------------------------------
# Datepicker
--------------------------------------------------------------*/
// Utility function for datepicker init

//ДОБАВИТЬ В FUNCTIONS.PHP ПЕРЕМЕННЫЕ ДЛЯ ИСПОЛЬЗОВАНИЯ ДАННОГО СКРИПТА

//<?php $weekend_string = carbon_get_theme_option('weekend');?>
//выходной
let weekend_str = '<?php echo $weekend_string; ?>';
let weekend_arr = weekend_str.split(',');
// console.log(weekend_arr);
let weekend = [];
weekend_arr.forEach(element => {
    weekend.push(new Date(element));
});

let date = new Date();
let hour = date.getHours();

console.log(hour);

function datepicker_options () {  
    console.log('hi datepicker_options');     

    //определяем первую доступную дату
    //let startDate = new Date();
    let startDate;

    // определяем дату, которая будет выбрана по умолчанию
    let selectedDate = [];

    
    if (hour >= 20) {  
        startDate = date.setDate(date.getDate() + 1);
        //selectedDate = startDate;
    } else {
        startDate = date;
        //selectedDate = startDate + 1;                   
    };

    // тут ошибка??
    if (hour >= 18 && hour <20) {  
        selectedDate = new Date().setDate(startDate.getDate() + 2);
    } else {
        selectedDate = new Date().setDate(startDate.getDate() + 1);       
    };

    //console.log('initial');
    //console.log(new Date(selectedDate));

    //очищаем дату для срочной доставки  TO BE DELETED
    // if (urgentPickups.includes(checkedShippingMethod)) {
    //     selectedDate = [];
    // };

    // проверяем, что первая доступная дата не попадает на выходной
    const weekendTimeStamps = weekend.map(function (element) {
        return element.getTime();
    });
    let isSelectedDayWeekend = false;
    function checkSelectedDay (checkDate) {
        let newSelectedDate = checkDate;
        isSelectedDayWeekend = weekendTimeStamps.includes((new Date(checkDate)).setHours(3,0,0,0));
        if (isSelectedDayWeekend) {
            newSelectedDate = date.setDate(new Date(checkDate).getDate() + 1);
            // console.log('new date')
            // console.log(new Date(newSelectedDate));
            return checkSelectedDay (newSelectedDate);
        }
        // console.log('after if');
        // console.log(new Date(newSelectedDate));
        return selectedDate = newSelectedDate;
    };

    //checkSelectedDay (selectedDate);
    //console.log('finally');
   //console.log(new Date(selectedDate));

    //кнопка ОК
    let button = {
        content: 'OK',
        className: 'custom-button-classname',
        onClick: (datepicker) => {
            datepicker.hide();
        }
    }
    console.log(startDate)
    // datepicker options
    let datePickerOpts = {
        selectedDates: [startDate],
        minDate: startDate,
        maxDate: (function(){
            let date = new Date();
            date.setDate(date.getDate() + 30);
            return date;
        })(),
        isMobile: true,
        //autoClose: true,

        buttons: [button] 
    }

    return datePickerOpts;
}

// Datepicker init
let datepickerCal;
let datePickerOpts;

let todayDP = `${new Date().getDate()}.${new Date().getUTCMonth() + 1}.${new Date().getUTCFullYear()}`;
let tomorrowDP = `${new Date().getDate() + 1}.${new Date().getUTCMonth() + 1}.${new Date().getUTCFullYear()}`;

//let isUrgent = '0';

function datepicker_init () {
    console.log('hi datepicker_init');

    //определяем параметры календаря
    datePickerOpts = datepicker_options ();
    console.log(datePickerOpts);
    datepickerCal.update(datePickerOpts);
    if (weekend) {
        datepickerCal.disableDate(weekend);
    }

    
    // проверяем срочная ли доставка и запускам аякс
    // let selectedDateFormatted = `${new Date(datePickerOpts.selectedDates).getDate()}.${new Date(datePickerOpts.selectedDates).getUTCMonth() + 1}.${new Date(datePickerOpts.selectedDates).getUTCFullYear()}`;
    // if (selectedDateFormatted == todayDP || selectedDateFormatted == tomorrowDP && hour >= 18 ) {
    //     isUrgent = '1'
    // } else {
    //     isUrgent = '0'
    // }
    // plntAjaxGetUrgent();           
}

setTimeout(() => {
    datepickerCal = new AirDatepicker('#datepicker', {
        onSelect({date, formattedDate, datepicker}) {
            console.log('hi date');
            
            // проверяем срочная ли доставка и запускам аякс
            if (formattedDate == todayDP || formattedDate == tomorrowDP && hour >= 18) {
                isUrgent = '1'
            } else (
                isUrgent = '0'
            );
            //plntAjaxGetUrgent();
        },

        // onRenderCell({date, cellType}) {
        //     return {
        //         html: 'lalala'   ,
        //     }
        // }
    });

    datepicker_init ();
}, 1000);  

checkoutForm.addEventListener('change', onChangeShippingMethod);

function onChangeShippingMethod(event) {
    if(event && event.target.className == "shipping_method") {
        datepicker_init();
    }
}