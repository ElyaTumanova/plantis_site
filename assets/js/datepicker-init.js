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
let startDate;
let urgentDate;

let deliveryCost;
let deliveryCostUrg;


//console.log(hour);


function datepicker_options () {  
    console.log('hi datepicker_options');    

    // задаем даты
    if (hour >= 20) {  
        startDate = date.setDate(date.getDate() + 1);
    } else {
        startDate = date;               
    };

    if (hour >= 18 && hour <20) { 
        urgentDate = {};
    } else {
        urgentDate = startDate;
    } 

    console.log(date);
    console.log(startDate);
    console.log(urgentDate);
    

    //console.log('initial');

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

    // //определяем параметры календаря
    // datePickerOpts = datepicker_options ();
    // console.log(datePickerOpts);
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
    //определяем параметры календаря
    datePickerOpts = datepicker_options ();
    console.log(datePickerOpts);

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

        onRenderCell({date, cellType}) {
            let dateUTC = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());
            let dateMinUTC = Date.UTC(datePickerOpts.minDate.getFullYear(), datePickerOpts.minDate.getMonth(), datePickerOpts.minDate.getDate());
            let dateMaxUTC = Date.UTC(datePickerOpts.maxDate.getFullYear(), datePickerOpts.maxDate.getMonth(), datePickerOpts.maxDate.getDate());
            if (dateUTC == dateMinUTC) {
                if (urgentDate) {
                    return {
                        html: date.getDate() + '<br>' + 'ururu'   ,
                    }
                } else {
                    return {
                        html: date.getDate() + '<br>' + 'lalala'   ,
                    }
                }
            }
            if (dateUTC > dateMinUTC && dateUTC <= dateMaxUTC) {
                return {
                    html: date.getDate() + '<br>' + 'lalala'   ,
                }
            } else {
                return {
                    html: date.getDate(),
                }
            }
        }
    });

    datepicker_init ();
}, 1000);  

checkoutForm.addEventListener('change', onChangeShippingMethod);

function onChangeShippingMethod(event) {
    if(event && event.target.className == "shipping_method") {
        datepicker_init();
        console.log(event.target.value);
        getDeliveryCosts(event.target.value);
    }
}

function getDeliveryCosts(shippingValue) {

    let deliveryCost;
    let deliveryCostUrg;

    if(shippingValue == deliveryInMKAD || shippingValue == deliveryInMKADUrg) {
        deliveryCost = deliveryCostInMkad;
        deliveryCostUrg = urgentDate ? deliveryCostInMkadUrg : deliveryCostInMkad;
    }
    if(shippingValue == deliveryOutMKAD || shippingValue == deliveryOutMKADUrg) {
        deliveryCostUrg = deliveryCostOutMkadUrg;
    }

    console.log(deliveryCost);
    console.log(deliveryCostUrg);
    // if(shippingValue == deliveryInMKADSmall || shippingValue == deliveryInMKADSmallUrg) {
    // priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadSmallUrg}₽` : `${deliveryCostInMkadSmall}₽` ;
    // }
    // if(shippingValue == deliveryOutMKADSmall || shippingValue == deliveryOutMKADSmallUrg) {
    // priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadSmallUrg}₽` : `${deliveryCostOutMkadSmall}₽` ;
    // }
    // if(shippingValue == deliveryInMKADLarge || shippingValue == deliveryInMKADLargeUrg) {
    // priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadLargeUrg}₽` : `${deliveryCostInMkadLarge}₽` ;
    // }
    // if(shippingValue == deliveryOutMKADLarge || shippingValue == deliveryOutMKADLargeUrg) {
    // priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadLargeUrg}₽` : `${deliveryCostOutMkadLarge}₽` ;
    // }
    // if(shippingValue == deliveryInMKADMedium || shippingValue == deliveryInMKADMediumUrg) {
    // priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostInMkadMediumUrg}₽` : `${deliveryCostInMkadMedium}₽` ;
    // }
    // if(shippingValue == deliveryOutMKADMedium || shippingValue == deliveryOutMKADMediumUrg) {
    // priceEl.innerHTML = info.for == `delivery_dates_${today}` ? `${deliveryCostOutMkadMediumUrg}₽` : `${deliveryCostOutMkadMedium}₽` ;
    // }
    


    
}