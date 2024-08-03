var datepicker = new Datepicker('#datepicker', {

    min: (function(){
      var date = new Date();
      date.setDate(date.getDate());
      console.log(date);
      return date;
    })(),

    // 30 days in the future
    max: (function(){
      var date = new Date();
      date.setDate(date.getDate() + 30);
      return date;
    })(),

    openOn: "today",

    without: [deserialize('05.08.2024')]
  });