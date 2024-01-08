jQuery(function ($){
    $('.search-form input[name="s"]').on('keyup', function (){  //стили контейнеров формы поиска из header
        var search = $('.search-form input[name="s"]').val();
        console.log(search);
        if (search.length <4) {
            return false; //ajax works after 4 digits input
        }
        var data = {
            s: search,
            action: 'search-ajax',
            nonce: search_form.nonce
        };
        $.ajax ({
            url: search_form.url,
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
            },
            success: function(data){
                $('.search .search-result').html(data.out);    //стили контейнеров формы поиска из header
            }
        });
    });
});