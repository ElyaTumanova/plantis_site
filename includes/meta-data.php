<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// not used

if ( ! function_exists( 'ast_posted_on' ) ) :

	function ast_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}
		
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
		
		$posted_on = sprintf(
			esc_html_x( 'Posted on %s', 'post date', 'plantis-theme' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);
		
		$byline = sprintf(
			esc_html_x( 'by %s', 'post author', 'plantis-theme' ),
			'<span class="author"><a class="url" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);
		
		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';
		
	}
endif;

if ( ! function_exists( 'ast_entry_footer' ) ) :

	function ast_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'plantis-theme' ) );
			if ( $categories_list && ast_categorized_blog() ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'plantis-theme' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}
			
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'plantis-theme' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'plantis-theme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
		
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'plantis-theme' ), esc_html__( '1 Comment', 'plantis-theme' ), esc_html__( '% Comments', 'plantis-theme' ) );
			echo '</span>';
		}
		
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'plantis-theme' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function ast_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'ast_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );
		
		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		
		set_transient( 'ast_categories', $all_the_cool_cats );
	}
	
	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so ast_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so ast_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in ast_categorized_blog.
 */
function ast_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'af_tf_categories' );
}
add_action( 'edit_category', 'ast_category_transient_flusher' );
add_action( 'save_post',     'ast_category_transient_flusher' );
