jQuery(function ($) {
  let timer = null;
  let xhr = null;

  $('.search-form input[name="s"]').on('input', function () {
    const search = this.value.trim();

    clearTimeout(timer);

    if (search.length < 4) return;

    timer = setTimeout(function () {
      // отменяем предыдущий незавершённый запрос (не обязательно, но полезно)
      if (xhr && xhr.readyState !== 4) xhr.abort();

      xhr = $.ajax({
        url: search_form.url,
        type: 'POST',
        dataType: 'json',
        data: {
          s: search,
          action: 'search-ajax',
          nonce: search_form.nonce
        },
        success: function (data) {
          // console.log(data.search_timing_1, data.search_timing_2);
          console.log(data);
          $('.search .search-result').html(data.out);
        }
      });
    }, 300); // подождать 300 мс после ввода
  });
});
