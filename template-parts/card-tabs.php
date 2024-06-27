<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<style>
  .card__tab > input[type="radio"] {
    display: none;
  }

  .tab-content {
    display: none;
  }

  #tab-btn-1:checked~#content-1,
  #tab-btn-2:checked~#content-2,
  #tab-btn-3:checked~#content-3 {
    display: block;
  }
</style>

<div class="card__tab">
  <input checked id="tab-btn-1" name="tab-btn" type="radio" value="">
  <label for="tab-btn-1">Вкладка 1</label>
  <input id="tab-btn-2" name="tab-btn" type="radio" value="">
  <label for="tab-btn-2">Вкладка 2</label>
  <input id="tab-btn-3" name="tab-btn" type="radio" value="">
  <label for="tab-btn-3">Вкладка 3</label>
  <div class="tab-content" id="content-1">
    Содержимое 1...
  </div>
  <div class="tab-content" id="content-2">
    Содержимое 2...
  </div>
  <div class="tab-content" id="content-3">
    Содержимое 3...
  </div>
</div>