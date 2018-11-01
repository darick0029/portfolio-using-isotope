<?php 
/**
 * Template Name: Portfolio
 *
 */

// enque isotope js using cdn
add_action( 'wp_enqueue_scripts', 'add_isotope' ); 
function add_isotope() {
	wp_enqueue_script( 'isotope-min-js', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array( 'jquery' ), '1.0.0', true);
}

// initialize isotope using jquery
add_action('wp_footer', 'start_isotope');
function start_isotope() {
?>
<script>
	jQuery(document).ready(function($) {
		var $grid = $('.grid').isotope({
		  // options
		  itemSelector: '.grid-item',
		  layoutMode: 'fitRows'
		});
		
		// filter items on button click
		$('.filter-button-group').on( 'click', 'button', function() {
		  var filterValue = $(this).attr('data-filter');
		  $grid.isotope({ filter: filterValue });
		});		
	});
</script>
<?php 
}

// customise the genesis main loop
remove_action ('genesis_loop', 'genesis_do_loop');
add_action( 'genesis_loop', 'portfolio_loop' );

function portfolio_loop() {
	$args = array( 'post_type' => 'portfolio', 'posts_per_page' => -1 );
	$loop = new WP_Query( $args );
?>

	<div style="margin: 0 0 35px" class="button-group filter-button-group">
		  <button data-filter="*">Show all</button>
			<?php $args = array('hide_empty' => true);	//This will display the term title even if its empty?>
			<?php $terms = get_terms(array('project_type', 'client_industry'), $args ); // Displays all of the term title
				foreach ( $terms as $term ) {
			?>
					<button data-filter="<?php echo '.'.$term->slug; ?>"><?php echo $term->slug; ?></button>
			<?php 
				}
			?>		
	</div>

<?php
	echo '<div class="grid">';
	while ( $loop->have_posts() ) : $loop->the_post();
?>	

	<div class="grid-item
		<?php   // Get terms for post
		 $terms = get_the_terms( $post->ID , array('project_type', 'client_industry') );
		 // Loop over each item since it's an array
		 if ( $terms != null ){
		 foreach( $terms as $term ) {
		 // Print the name method from $term which is an OBJECT
		 print $term->slug. ' ' ;
		 // Get rid of the other data stored in the object, since it's not needed
		 unset($term);
		} } ?>				
	">
		<?php if ( has_post_thumbnail() ) {
			 /* the post thumbnail parameter is already set before i created this so if you dont have a specific image size you should create one. */
			the_post_thumbnail('portfolio-thumb-lg');
		} 	?>	
		<div class="pgw-content">
			<a href="<?php echo get_the_permalink(); ?>" rel="bookmark" title="<?php echo get_the_title(); ?>" class="fl-post-carousel-image-overlay" target="_blank" tabindex="0">
					<h2 class="portfolio-grid-title" itemprop="headline">
						<?php echo get_the_title(); ?>
					</h2>
					<p class="portfolio-grid-meta">
							<?php if(get_field('what_we_did')): echo the_field('what_we_did'); endif; ?>			
					</p>
					<button class="button portfolio-url" tabindex="0">View</button>
			</a>
		</div>
	</div>
	
<?php
	endwhile;
	echo '</div>';
}



genesis();
