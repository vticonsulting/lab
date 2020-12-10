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




    <div class="mb-6 border-t border-b bg-circuit border-dark-softer">
        <div class="container">
            <div class="py-9">
                <h1 class="text-3xl">Podcasting for Programmers</h1>
                <p class="text-2xl text-thin text-dark-soft">The best place to discover and publish podcasts about building software.</p>
            </div>
        </div>
    </div>
    <div class="container">
        <div>
            <h1 class="mb-4 text-bold">Popular Shows</h1>
            <div class="row pull-x-4 pull-b-6">
                @foreach ($podcasts as $podcast)
                <div class="px-4 mb-6 col-2">
                    <div class="hover-grow">
                        <a href="{{ url("/podcasts/{$podcast->id}") }}" class="block mb-2 box-shadow">
                            <img src="{{ $podcast->imageUrl() }}" class="img-fit">
                        </a>
                        <p class="text-ellipsis">
                            <a href="{{ url("/podcasts/{$podcast->id}") }}" class="text-sm text-medium">
                                {{ $podcast->title }}
                            </a>
                        </p>
                        <p class="text-xs text-uppercase text-spaced text-ellipsis">
                            <a href="{{ $podcast->website }}" class="link-softer">
                                {{ $podcast->websiteHost() }}
                            </a>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
