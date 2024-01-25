<footer id="footer" class="footer" role="contentinfo">

</footer><!-- #colophon -->
</div><!-- #page -->

<?php get_template_part('template-parts/popups/search-popup');?>
<?php get_template_part('template-parts/popups/mini-cart-popup');?>

<?php 
    global $product;
    if (is_product() && $product->get_stock_status() ==='outofstock') {
        wc_get_template_part('template-parts/popups/preorder-popup');
    }
?>

<?php wp_footer(); ?>


</body>
</html>