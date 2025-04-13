let dataLazyLoadingJS = {
    data: {
        ya_counter: {
            status: false,
            html: '<!-- Yandex.Metrika counter --> <script type="text/javascript" >	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};	m[i].l=1*new Date();	for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}	k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");	ym(87781741, "init", {			clickmap:true,			trackLinks:true,			accurateTrackBounce:true,			webvisor:true,			ecommerce:"dataLayer"	});	window.dataLayer = window.dataLayer || [];	</script>	<noscript><div><img src="https://mc.yandex.ru/watch/87781741" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->',
            area: 'head'
        }
    }
};

let dataSettings = {
    cookie_name: 'plantis_welcome_cookie',
    fancybox: {
        content: $('.welcome-pt-message'),
        wrapCSS: 'site-scrollable-fancybox'
    }
};

let LazyLoad = new ptsLazyLoad(dataLazyLoadingJS, dataSettings);
let need_check = 1;
LazyLoad.simpleCheck( need_check ); //метод ожидает 0 или 1, 1 в случае, если необходимо выводить сообщение, 0, если не надо