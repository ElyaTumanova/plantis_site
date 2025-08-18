<!DOCTYPE html>
<html <?php language_attributes(); ?> itemscope itemtype="https://schema.org/Store">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Schema.org -->
    <meta itemprop="image" content="<?php echo carbon_get_theme_option('logo') ?>">
    <meta itemprop="name" content="Интернет-магазин комнатных растений в Москве - Plantis">
    <meta itemprop="openingHours" content="Mo-Su 10:00-20:00"/>
    <meta itemprop="telephone" content="+7 800 201 57 90">
    <meta itemprop="address" content="г. Москва, ул. Мещерякова, д.3. м. Тушинская">
    <meta itemprop="priceRange" content="100 - 40000">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!-- /Schema.org -->
	<!-- Yandex.Metrika counter -->
	<script async type="text/javascript" >
	(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
	m[i].l=1*new Date();
	for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
	k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	ym(103710881, "init", {
			clickmap:true,
			trackLinks:true,
			accurateTrackBounce:true,
			webvisor:true,
			ecommerce:"dataLayer"
	});
	window.dataLayer = window.dataLayer || [];
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/103710881" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
	 
	<!-- Top.Mail.Ru counter -->
	<script type="text/javascript">
	var _tmr = window._tmr || (window._tmr = []);
	_tmr.push({id: "3527126", type: "pageView", start: (new Date()).getTime()});
	(function (d, w, id) {
	if (d.getElementById(id)) return;
	var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
	ts.src = "https://top-fwz1.mail.ru/js/code.js";
	var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
	if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
	})(document, window, "tmr-code");
	</script>
	<noscript><div><img src="https://top-fwz1.mail.ru/counter?id=3527126;js=na" style="position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript>
	<!-- /Top.Mail.Ru counter -->
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!-- <noscript><div><img src="https://mc.yandex.ru/watch/103710881" style="position:absolute; left:-9999px;" alt="" /></div></noscript> -->
<div id="page" class="site">

<?php
    get_template_part( 'template-parts/heading' );
?>