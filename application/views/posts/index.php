<main class="container flex-1 p-8 mr-auto prose">
	<?php foreach ($posts as $post) : ?>
		<h3><?php echo $post['title']; ?></h3>

		<div class="flex space-x-8 align-start">
			<div class="w-3/12">
				<img class="post-thumb img-responsive" src="<?php echo site_url(); ?>assets/images/posts/<?php echo $post['post_image']; ?>">
			</div>

			<div class="w-9/12">
				<small class="post-date">
					Posted on: <?php echo $post['created_at']; ?> in <strong><?php echo $post['name']; ?></strong>
				</small>
				<article class="mb-4">
					<?php echo word_limiter($post['body'], 60); ?>
				</article>
				<p>
					<a class="btn btn-default" href="<?php echo site_url('/posts/' . $post['slug']); ?>">
						Read More
					</a>
				</p>
			</div>
		</div>
	<?php endforeach; ?>

	<div class="pagination-links">
		<?php echo $this->pagination->create_links(); ?>
	</div>
</main>
