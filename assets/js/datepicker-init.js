/*--------------------------------------------------------------
# Datepicker
--------------------------------------------------------------*/
// Utility function for datepicker init

// переменные

    // Datepicker init
    let datepickerCal;
    let datePickerOpts;

    let date = new Date();
    let hour = date.getHours();
    let startDate;

    let dateMinUTC;
    let dateTomorrowUTC;
    let dateMaxUTC;

    //выходной
    let weekend_arr = weekend_str.split(',');
    let weekend = [];
    weekend_arr.forEach(element => {
        weekend.push(new Date(element));
    });

    const weekendTimeStamps = weekend.map(function (element) {
        return element.getTime();
    });
    let isSelectedDayWeekend = false;

    console.log(weekend);


function datepicker_options () {  
    console.log('hi datepicker_options');    

    // задаем даты
    if (hour >= 20) {  
        startDate = date.setDate(date.getDate() + 1);
    } else {
        startDate = date;               
    };

    // console.log(date);
    // console.log(startDate);
    

    // console.log('initial');

    // let checkedDate = checkSelectedDay (startDate);
    // console.log('finally');
    // console.log(new Date(checkedDate));

    //кнопка ОК
    let button = {
        content: 'OK',
        className: 'custom-button-classname',
        onClick: (datepicker) => {
            datepicker.hide();
        }
    }
  
    let selectedDate = startDate;
    // datepicker options
    let datePickerOpts = {
        selectedDates: [selectedDate],
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

// проверяем, что первая доступная дата не попадает на выходной
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

//let todayDP = `${new Date().getDate()< 10 ? '0' : ''}${new Date().getDate()}.${(new Date().getUTCMonth()+1) < 10 ? '0' : ''}${new Date().getUTCMonth() + 1}.${new Date().getUTCFullYear()}`;
//let tomorrowDP = `${(new Date().getDate() + 1)< 10 ? '0' : ''}${new Date().getDate() + 1}.${(new Date().getUTCMonth()+1) < 10 ? '0' : ''}${new Date().getUTCMonth() + 1}.${new Date().getUTCFullYear()}`;

function datepicker_init () {
    console.log('hi datepicker_init');

    //определяем параметры календаря
    datePickerOpts = datepicker_options ();
    dateMinUTC = Date.UTC(datePickerOpts.minDate.getFullYear(), datePickerOpts.minDate.getMonth(), datePickerOpts.minDate.getDate());
    dateTomorrowUTC = Date.UTC(datePickerOpts.minDate.getFullYear(), datePickerOpts.minDate.getMonth(), datePickerOpts.minDate.getDate()+1);
    dateMaxUTC = Date.UTC(datePickerOpts.maxDate.getFullYear(), datePickerOpts.maxDate.getMonth(), datePickerOpts.maxDate.getDate());
    console.log(datePickerOpts);
    // console.log(dateTomorrowUTC);
    datepickerCal.update(datePickerOpts);
    if (weekend) {
        datepickerCal.disableDate(weekend);
    }         
}

function datepicker_create () {
    datepickerCal = new AirDatepicker('#datepicker', {
        onSelect({date, formattedDate, datepicker}) {
            // console.log('hi date');
            // console.log(date);
            // console.log(formattedDate);
            // console.log(todayDP);
            // console.log(tomorrowDP);

            chekIfUrgent(date);
        },

        onRenderCell({date, cellType}) {
            let dateUTC = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());
            console.log(checkedShippingMethod);
            if(normalDeliveries.includes(checkedShippingMethod) || urgentDeliveries.includes(checkedShippingMethod)) {
                if (dateUTC == dateMinUTC) {
                    if (urgentDelivery) {
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
    })
} 

// checkoutForm.addEventListener('change', onChangeShippingMethod);

// function onChangeShippingMethod(event) {
//     if(event && event.target.className == "shipping_method") {
//         getDeliveryCosts(event.target.value);
//         checkedShippingMethod = event.target.value;
//         console.log(checkedShippingMethod);
//         datepicker_init();
//     }
// }



function chekIfUrgent(date) {
    // проверяем срочная ли доставка и запускам аякс
    let dateUTC = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());
    if (dateUTC == dateMinUTC || dateUTC == dateTomorrowUTC && hour >= 18) {
        isUrgent = '1'
    } else (
        isUrgent = '0'
    );
    ajaxGetUrgent();
}