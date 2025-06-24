<?php
/**
 * Template part for displaying the time line
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package d64
 */
?>

<div class="timeline max-w-[640px] m-auto py-10 px-4 sm:px-0">
		<div class="border-dashed border-l border-d64gray-500 min-h-40 flex flex-col gap-20">

			<?php while( have_rows('timeline') ): the_row(); 
				// Variables for the subfields
				$image = get_sub_field('image');
				$size = "medium-16-9"; 
				$headline = get_sub_field('headline');
				$paragraph = get_sub_field('absatz');
				$jahr = get_sub_field('jahr');
				$monat = get_sub_field('monat');

				$video = false;
				$videoFile = get_sub_field('video');
				if ($videoFile) {
					$video_url = $videoFile['url'];
					$video_title = $videoFile['title'];
					$video_caption = $videoFile['caption'];
					$video_mime_type = $videoFile['mime_type'];
					$video_description = $videoFile['description'];
					$video_size = 'medium-16-9';
					
					if ($video_mime_type == 'video/mp4') {
						$video = true;
					} else {
						$video = false;
					}

				}
			?>

			<!-- Timeline Item -->
			<div class="timeline-item border-l border-d64blue-900 flex flex-row gap-8 md:gap-10 -translate-x-[1px]">
				<div class="date flex flex-col pl-4 min-w-[60px]">
					<?php if ($monat) : ?>
						<span class="text-xs font-medium"><?php echo esc_html($monat); ?></span>
					<?php endif; ?>
					<?php if ($jahr) : ?>
						<span class="font-medium"><?php echo esc_html($jahr); ?></span>
					<?php endif; ?>
				</div>
				<div class="flex flex-col gap-4">
					<?php if( $image && !$video ): 
						// Image variables.
						$url = $image['url'];
						$title = $image['title'];
						$alt = $image['alt'];
						$caption = $image['caption'];    
						$description = $image['description'];
						$size = 'medium-16-9';
						$medium = $image['sizes'][ $size ];
					?>
						<div class="rounded-xl overflow-hidden relative">
							<img src="<?php echo esc_url($medium); ?>" alt="<?php echo esc_attr($alt); ?>" />
							<?php if ($description) : 
								echo '<div class="absolute bottom-0 right-0 max-w-max rounded-tl bg-slate-300 bg-opacity-60 text-slate-800  text-xs font-medium text-end px-1 py-[1px]">' . $description . '</div>';
							endif; ?>
						</div>
					<?php endif; ?>
					<?php if( $video && $video_url ): ?>
						<div class="aspect-video relative rounded-xl overflow-hidden">
							<video class="w-full h-full object-cover" controls>
								<source src="<?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($video_mime_type); ?>">
								<?php esc_html_e('Your browser does not support the video tag.', 'd64'); ?>
							</video>
						</div>
					<?php endif; ?>
					<div class="flex flex-col gap-2 md:gap-4">
					<?php if( $headline ): ?>
						<h2 class="font-serif font-bold text-xl sm:text-2xl md:text-2xl"><?php echo esc_html($headline); ?></h2>
					<?php endif; ?>
					<?php if( $paragraph ): ?>
						<div class="prose prose-p:mb-3">
							<?php echo $paragraph; ?>
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>

			<?php endwhile; ?>
		</div>
	</div>