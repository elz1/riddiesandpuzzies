<?php
/**
 * The component that handles the post loop.
 */
if(is_singular('riddle')) : 

		Components\View::render('riddles', 'single');

	else : ?>

<?php 
$omp = get_field('old_man_puzzles');
if($omp == 'Adal Rifai') : 
	$twitter = get_field('adal', 'option');
elseif($omp == 'JPC') : 
	$twitter = get_field('jpc', 'option');
elseif($omp == 'Erin Keif') :
	$twitter = get_field('erin', 'option');
else : 
	$twitter = false;
endif; 
?>
	
	<article <?php post_class(); ?> id="content" >
		<div class="title">
			<h2><?php the_title(); ?></h2>
			<?php if($omp !== 'Nobody') : ?>
				<h5 class="mt-2"><span class="byline">Old Man Puzzles: <?php if($twitter) : ?><a href="<?php echo $twitter; ?>" target="_blank"><?php endif; ?><?php echo $omp; ?><?php if($twitter) : ?></a><?php endif; ?></span></h5>
			<?php endif; ?>
			<?php if(get_field('link_to_episode')) : ?>
				<div class="my-3"><a target="_blank" href="<?php echo esc_url(get_field('link_to_episode')); ?>"><i class="far fa-play-circle mr-2"></i>Listen to Episode</a></div>
			<?php endif; ?>
		</div>

		<?php the_content(); ?>

		<?php
		// fetching the riddles from the acf relationship field
		$riddles = get_field('episode_riddles');
		$types = ['warm-up', 'main', 'listener-submitted'];

		if( $riddles ): 
			
			foreach($types as $type) {
				$show_type = false;
				foreach( $riddles as $riddle) {
					$this_type = get_the_terms( $riddle, 'riddle_types' )[0]->slug;
					if($this_type === $type ) { $show_type = true; }
					}
			?>
			<?php if($show_type) { ?>
			<div class="riddle-section">
				<div class="title">
					<h4><?php echo ucwords(str_replace('-', ' ', $type)); ?> Riddles</h4>
				</div>
				<hr>
				<?php foreach( $riddles as $riddle): ?>
					<?php $this_type = get_the_terms( $riddle, 'riddle_types' )[0]->slug; ?>
					<?php if($this_type === $type ) : ?>
					<div class="riddle-holder mb-4">
						<div class="riddle"><p><?php the_field('riddle', $riddle->ID); ?></p></div>
						<p class="show-answer">Show Answer</p>
						<p class="hide-answer">Hide Answer</p>
						<div class="answer"><p><?php the_field('answer', $riddle->ID); ?></p></div>
					</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php } } ?>
		<?php endif; ?>
		<?php
		// the old method of querying riddles. keeping it so the old posts don't break.
			$episode = wp_get_post_tags(get_the_ID())[0]->slug;
			
			foreach($types as $type) :

				$args = array(
					'post_type' => 'riddle',
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'episodes',
							'field' => 'slug',
							'terms' => array( $episode ),
						),
						array(
							'taxonomy' => 'riddle_types',
							'field' => 'slug',
							'terms' => array( $type ),
						),
					),
					'posts_per_page' => -1,
					'order'			 => 'ASC',
					'date_query' => array(
						array(
							'before' => array( // (string/array) - Date to retrieve posts after. Accepts strtotime()-compatible string, or array of 'year', 'month', 'day'
								'year' => 2019, // (string) Accepts any four-digit year. Default is empty.
								'month' => 1, // (string) The month of the year. Accepts numbers 1-12. Default: 12.
								'day' => 12, // (string) The day of the month. Accepts numbers 1-31. Default: last day of month.
							),
						),
					),
				);

				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) : ?>
					<div class="riddle-section">
						<div class="title">
							<h4><?php echo ucwords(str_replace('-', ' ', $type)); ?> Riddles</h4>
						</div>
						<hr>
						<?php while ( $the_query->have_posts() ) : $the_query->the_post(); 
							setup_postdata(get_the_ID()); ?>
							<div class="riddle-holder mb-4">
								<div class="riddle"><p><?php the_field('riddle'); ?></p></div>
								<p class="show-answer">Show Answer</p>
								<p class="hide-answer">Hide Answer</p>
								<div class="answer"><p><?php the_field('answer'); ?></p></div>
								
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif;
				wp_reset_postdata();
			endforeach;
			?>
		
	</article>
<?php endif; ?>