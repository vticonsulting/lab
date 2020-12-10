<main class="container flex-1 p-8">
	<header class="prose">
		<h2><?php echo $post['title'] ?></h2>
	</header>

	<article class="prose post-body">
		<small class="post-date">
			<?php echo $post['created_at']; ?>
		</small>

		<img src="<?php echo site_url(); ?>assets/images/posts/<?php echo $post['post_image']; ?>">

		<?php echo $post['body']; ?>

	</article>

	<?php if ($this->session->userdata('user_id') == $post['user_id']): ?>
		<footer class="flex mt-4 space-x-4">
			<a href="<?php echo base_url(); ?>posts/edit/<?php echo $post['slug']; ?>" class="px-4 py-1 border rounded shadow btn btn-default">
				Edit
			</a>

			<?php echo form_open('/posts/delete/' . $post['id']); ?>
				<input type="submit" value="Delete" class="px-4 py-1 border rounded shadow btn btn-danger">
			</form>
		</footer>
	<?php endif; ?>


	<h3>Comments</h3>

	<?php if ($comments) : ?>
		<?php foreach ($comments as $comment) : ?>
			<div class="well">
				<h5><?php echo $comment['body']; ?> [by <strong><?php echo $comment['name']; ?></strong>]</h5>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<p>No Comments To Display</p>
	<?php endif; ?>

	<h3>Add Comment</h3>

	<?php echo validation_errors(); ?>

	<?php echo form_open('comments/create/' . $post['id']); ?>
		<div class="form-group">
			<label>Name</label>
			<input type="text" name="name" class="form-control">
		</div>

		<div class="form-group">
			<label>Email</label>
			<input type="text" name="email" class="form-control">
		</div>

		<div class="form-group">
			<label>Body</label>
			<textarea name="body" class="form-control"></textarea>
		</div>

		<input type="hidden" name="slug" value="<?php echo $post['slug']; ?>">
		<button class="btn btn-primary" type="submit">Submit</button>
	</form>
</main>


    <div class="container mt-6">
        <div class="row pull-x-4">
            <div class="px-4 col-3">
                <div class="block mb-4 box-shadow">
                    <a href="{{ url("/podcasts/{$episode->podcast->id}") }}">
                        <img src="{{ $episode->podcast->imageUrl() }}" class="img-fit">
                    </a>
                </div>
            </div>
            <div class="px-4 col-9">
                <div class="mb-6">
                    <div class="mb-5 flex-spaced flex-y-center">
                        <div>
                            <p class="text-sm text-spaced text-uppercase text-medium text-dark-softest">{{ $episode->published_at->format('j M Y')}}</p>
                            <h1 class="mb-2 leading-none">{{ $episode->title }}</h1>
                            <p class="text-sm text-uppercase text-spaced text-ellipsis">
                                <a href="{{ url("/podcasts/{$episode->podcast->id}") }}" class="link-softer text-medium">
                                    {{ $episode->podcast->title }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <audio class="block mb-5 full-width" controls preload="metadata">
                        <source src="{{ $episode->download_url }}" type="audio/mpeg">
                    </audio>
                    <div class="markdown">
                        {!! $episode->content_html !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
