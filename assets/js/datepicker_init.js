var datepicker = new Datepicker('#datepicker', {

    min: (function(){
      var date = new Date();
      date.setDate(date.getDate());
      return date;
    })(),

    // 30 days in the future
    max: (function(){
      var date = new Date();
      date.setDate(date.getDate() + 30);
      return date;
    })(),

    openOn: "today",

    without: [05.08.2024]
  });