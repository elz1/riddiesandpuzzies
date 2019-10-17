<?php
/**
 * Standard Page Template
 */

get_header();

	while ( have_posts() ) : the_post(); ?>
	
		<?php
		/**
		 * This is an example of how to use the component system
		 * Instead of StdClass, you can create your own classes to organize you structure
		 * @var StdClass
		 */
		
		// $object = new StdClass;
		// $object->foo = 'bar';
		
		// Components\View::render('component-name', 'sub-component', $object);
		
		?>
		<?php if( is_page(696) ) : ?>
		<div class="bg-white">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<?php endif; ?>
						<?php the_content(); ?>
						<?php if( is_page(696) ) : ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

	<?php endwhile;

get_footer(); ?>