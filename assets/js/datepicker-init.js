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

//console.log(weekend);


function datepicker_options () {  
    console.log('hi datepicker_options');    

    // задаем даты
    if (hour >= 20) {  
       startDate = date.setDate(date.getDate() + 1);
    } else {
       startDate = date;               
    };

    dateMinUTC = Date.UTC(startDate.getFullYear(), startDate.getMonth(), startDate.getDate());
    dateTomorrowUTC = Date.UTC(startDate.getFullYear(), startDate.getMonth(), startDate.getDate()+1);

    let selectedDate = startDate;
    checkSelectedDay (startDate);
    // console.log('finally');
    // console.log(new Date(selectedDate));
    // console.log(new Date(startDate));

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
    console.log(new Date(startDate));
    return selectedDate = newSelectedDate;
};

function datepicker_create () {
    datepickerCal = new AirDatepicker('#datepicker', {
        onSelect({date, formattedDate, datepicker}) {
            chekIfUrgent(date);
            checkShortDay(date);
            checkedShippingMethod = getCheckedShippingMethod();
            renderDeliveryIntervals(checkedShippingMethod);
            plnt_hide_checkout_fields();
        },

        onRenderCell({date, cellType}) {
            let dateUTC = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());
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
    });

    //определяем параметры календаря
    datePickerOpts = datepicker_options ();
    console.log(datePickerOpts);

    dateMaxUTC = Date.UTC(datePickerOpts.maxDate.getFullYear(), datePickerOpts.maxDate.getMonth(), datePickerOpts.maxDate.getDate());
    checkShortDay(datePickerOpts.selectedDates[0]);
    datepickerCal.update(datePickerOpts);
    if (weekend) {
        datepickerCal.disableDate(weekend);
    }  
} 

function chekIfUrgent(date) {
    // проверяем срочная ли доставка и запускам аякс
    let dateUTC = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());

    if (dateUTC == dateMinUTC || dateUTC == dateTomorrowUTC && hour >= 18) {
        isUrgent = '1'
    } else (
        isUrgent = '0'
    );

    console.log(isUrgent);
    ajaxGetUrgent();
}

function checkShortDay(date) {
    if (shortdays) {
        if (shortdays.includes(date.setHours(3,0,0,0))) {
            isShortDay = '1';
            ajaxGetLateDelivery();
        } else {
            isShortDay = '0'
        };
    }
}