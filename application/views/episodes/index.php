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





<div class="container mt-6">
    <div class="mx-auto constrain-xl">
        <h1 class="mb-6">Latest Episodes</h1>
        @foreach ($episodes as $episode)
        <div class="mb-6 row pull-x-4">
            <div class="px-4 col-3">
                <div class="block box-shadow">
                    <a href="{{ url("/podcasts/{$episode->podcast->id}") }}">
                        <img src="{{ $episode->podcast->imageUrl() }}" class="img-fit">
                    </a>
                </div>
            </div>
            <div class="px-4 pt-4 col-9">
                <div class="mb-4">
                    <p class="text-sm text-spaced text-uppercase text-medium text-dark-softest">{{ $episode->published_at->format('j M Y')}}</p>
                    <h2 class="text-lg text-ellipsis">
                        <a href="{{ url("/episodes/{$episode->id}") }}" class="text-bold">
                            {{ $episode->title }}
                        </a>
                    </h2>
                    <p class="text-sm text-uppercase text-spaced text-ellipsis">
                        <a href="{{ url("/podcasts/{$episode->podcast->id}") }}" class="link-softer text-medium">
                            {{ $episode->podcast->title }}
                        </a>
                    </p>
                </div>
                <audio class="block mb-5 full-width" controls preload="metadata">
                    <source src="{{ $episode->download_url }}" type="audio/mpeg">
                </audio>
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-center">
        {{ $episodes->links() }}
    </div>
</div>
