<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ) ?>" >
	<label class="screen-reader-text" for="s">Поиск: </label>
	<input type="text" value="<?php echo get_search_query() ?>" name="s" id="s" />
    <input type="hidden" value="product" name="post_type" />
	<input type="submit" id="searchsubmit" value="найти" />
</form>