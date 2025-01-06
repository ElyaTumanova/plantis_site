<form role="search" method="get" id="searchform" class="search-form" action="<?php echo home_url( '/' ) ?>" >
	<label class="screen-reader-text" for="s">Найти: </label>
	<input type="search" class="search-field" autofocus="autofocus" placeholder="Поиск…" value="<?php echo get_search_query() ?>" name="s" id="s" />
    <input type="hidden" value="product" name="post_type" />
	<!-- <input class="search-submit" type="submit" id="searchsubmit" value="Поиск" /> -->
</form>