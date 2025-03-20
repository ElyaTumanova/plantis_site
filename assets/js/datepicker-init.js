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

let todayDP = `${new Date().getDate()< 10 ? '0' : ''}${new Date().getDate()}.${(new Date().getUTCMonth()+1) < 10 ? '0' : ''}${new Date().getUTCMonth() + 1}.${new Date().getUTCFullYear()}`;
let tomorrowDP = `${(new Date().getDate() + 1)< 10 ? '0' : ''}${new Date().getDate() + 1}.${(new Date().getUTCMonth()+1) < 10 ? '0' : ''}${new Date().getUTCMonth() + 1}.${new Date().getUTCFullYear()}`;

function datepicker_init () {
    console.log('hi datepicker_init');

    // //определяем параметры календаря
    // datePickerOpts = datepicker_options ();
    // console.log(datePickerOpts);
    datepickerCal.update(datePickerOpts);
    if (weekend) {
        datepickerCal.disableDate(weekend);
    }         
}

setTimeout(() => {
    //определяем параметры календаря
    datePickerOpts = datepicker_options ();
    let dateMinUTC = Date.UTC(datePickerOpts.minDate.getFullYear(), datePickerOpts.minDate.getMonth(), datePickerOpts.minDate.getDate());
    let dateTomorrowUTC = Date.UTC(datePickerOpts.minDate.getFullYear(), datePickerOpts.minDate.getMonth(), datePickerOpts.minDate.getDate()+1);
    let dateMaxUTC = Date.UTC(datePickerOpts.maxDate.getFullYear(), datePickerOpts.maxDate.getMonth(), datePickerOpts.maxDate.getDate());
    console.log(datePickerOpts);
    console.log(dateTomorrowUTC);

    datepickerCal = new AirDatepicker('#datepicker', {
        onSelect({date, formattedDate, datepicker}) {
            console.log('hi date');
            console.log(date);
            console.log(formattedDate);
            console.log(todayDP);
            console.log(tomorrowDP);

            chekIfUrgent(date);
        },

        onRenderCell({date, cellType}) {
            let dateUTC = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());
            console.log(checkedShippingMethod);
            if(normalDeliveries.includes(checkedShippingMethod) || urgentDeliveries.includes(checkedShippingMethod)) {
                if (dateUTC == dateMinUTC) {
                    if (urgentDate) {
                        return {
                            html: date.getDate() + '<p>' + deliveryCostUrg + '₽' + '</p>' ,
                        }
                    } else {
                        return {
                            html: date.getDate() + '<p>' + deliveryCost + '₽'  + '</p>' ,
                        }
                    }
                }
                if (dateUTC > dateMinUTC && dateUTC <= dateMaxUTC) {
                    return {
                        html: date.getDate() + '<p>' + deliveryCost + '₽'  + '</p>' ,
                    }
                } else {
                    return {
                        html: date.getDate(),
                    }
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
        getDeliveryCosts(event.target.value);
        checkedShippingMethod = event.target.value;
        console.log(checkedShippingMethod);
        datepicker_init();
    }
}

function getDeliveryCosts(shippingValue) {

    if(shippingValue == deliveryInMKAD || shippingValue == deliveryInMKADUrg) {
        deliveryCost = deliveryCostInMkad;
        deliveryCostUrg = urgentDate ? deliveryCostInMkadUrg : deliveryCostInMkad;
    }
    if(shippingValue == deliveryOutMKAD || shippingValue == deliveryOutMKADUrg) {
        deliveryCost = deliveryCostOutMkad;
        deliveryCostUrg = urgentDate ? deliveryCostOutMkadUrg : deliveryCostOutMkad;
    }

    if(shippingValue == deliveryInMKADSmall || shippingValue == deliveryInMKADSmallUrg) {
        deliveryCost = deliveryCostInMkadSmall;
        deliveryCostUrg = urgentDate ? deliveryCostInMkadSmallUrg : deliveryCostInMkadSmall;
    }
    if(shippingValue == deliveryOutMKADSmall || shippingValue == deliveryOutMKADSmallUrg) {
        deliveryCost = deliveryCostOutMkadSmall;
        deliveryCostUrg = urgentDate ? deliveryCostOutMkadSmallUrg : deliveryCostOutMkadSmall;
    }
    if(shippingValue == deliveryInMKADLarge || shippingValue == deliveryInMKADLargeUrg) {
        deliveryCost = deliveryCostInMkadLarge;
        deliveryCostUrg = urgentDate ? deliveryCostInMkadLargeUrg : deliveryCostInMkadLarge;
    }
    if(shippingValue == deliveryOutMKADLarge || shippingValue == deliveryOutMKADLargeUrg) {
        deliveryCost = deliveryCostOutMkadLarge;
        deliveryCostUrg = urgentDate ? deliveryCostOutMkadLargeUrg : deliveryCostOutMkadLarge;
    }
    if(shippingValue == deliveryInMKADMedium || shippingValue == deliveryInMKADMediumUrg) {
        deliveryCost = deliveryCostInMkadMedium;
        deliveryCostUrg = urgentDate ? deliveryCostInMkadMediumUrg : deliveryCostInMkadMedium;
    }
    if(shippingValue == deliveryOutMKADMedium || shippingValue == deliveryOutMKADMediumUrg) {
        deliveryCost = deliveryCostOutMkadMedium;
        deliveryCostUrg = urgentDate ? deliveryCostOutMkadMediumUrg : deliveryCostOutMkadMedium;
    }
    
    console.log(deliveryCost);
    console.log(deliveryCostUrg);
    


    
}

function chekIfUrgent(date) {
    // проверяем срочная ли доставка и запускам аякс
    let dateUTC = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());
    //if (date == todayDP || date == tomorrowDP && hour >= 18) {
    if (date == dateMinUTC || date == dateTomorrowUTC && hour >= 18) {
        isUrgent = '1'
    } else (
        isUrgent = '0'
    );
    ajaxGetUrgent();
}

if (checkoutForm) {
    console.log(checkedShippingMethod);
    getDeliveryCosts(checkedShippingMethod);
}