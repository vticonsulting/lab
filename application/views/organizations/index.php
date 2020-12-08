<main class="container flex-1 p-8 mr-auto prose">
	<?php foreach ($organizations as $organization) : ?>
		<h3><?php echo $organization['title']; ?></h3>

		<div class="flex space-x-8 align-start">
			<div class="w-3/12">
				<img class="organization-thumb img-responsive" src="<?php echo site_url(); ?>assets/images/organizations/<?php echo $organization['organization_image']; ?>">
			</div>

			<div class="w-9/12">
				<small class="organization-date">
					organizationed on: <?php echo $organization['created_at']; ?> in <strong><?php echo $organization['name']; ?></strong>
				</small>
				<article class="mb-4">
					<?php echo word_limiter($organization['body'], 60); ?>
				</article>
				<p>
					<a class="btn btn-default" href="<?php echo site_url('/organizations/' . $organization['slug']); ?>">
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
