<?php

/**
 * Gets the first image of a post or the featured image (post thumbnail)
 * 
 * @uses evolution_get_first_image()
 * @since 1.0.6
 * 
 */
function evolution_post_thumbnails() { 

if(has_post_thumbnail()) {
               
    the_post_thumbnail( 'thumbnail' );

			} else {

                $image = evolution_get_first_image();
				if($image) :
					echo '<img src="';
					echo $image;
                    echo '"width="50px" height="50px" alt="Blog Post';
					the_title();
					echo '" />';
				endif;
			}
}



/**
 * 
 * Gets the first image of a post
 * 
 * @since 1.0.6
 * 
 */
function evolution_get_first_image() {
 		global $post, $posts;
 		$first_img = '';
 		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
 		if(isset($matches[1][0])){
 		$first_img = $matches [1][0];
 		return $first_img;
 		}
}