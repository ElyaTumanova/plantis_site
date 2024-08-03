// var datepicker = new Datepicker('#datepicker', {

//     min: (function(){
//     var date = new Date();
//     date.setDate(date.getDate()+1);
//     // console.log(startDate);
//     // console.log(date);
//     return date;
//     })(),

//     // 30 days in the future
//     max: (function(){
//     var date = new Date();
//     date.setDate(date.getDate() + 30);
//     return date;
//     })(),

//     openOn: "today",

//     onInit: (function(){console.log('hi')}),

//     without: [(function(){
//         var date = new Date(2024,7,5);
//         return date;
//     })(),]
// });

var datepicker = new AirDatepicker('#datepicker')
