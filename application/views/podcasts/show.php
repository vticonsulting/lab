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
                    <a href="{{ url("/podcasts/{$podcast->id}") }}">
                        <img src="{{ $podcast->imageUrl() }}" class="img-fit">
                    </a>
                </div>
            </div>
            <div class="px-4 col-9">
                <div class="mb-6">
                    <div class="mb-4 flex-spaced flex-y-center">
                        <div>
                            <h1>
                                <a href="{{ url("/podcasts/{$podcast->id}") }}" class="text-bold">{{ $podcast->title }}</a>
                            </h1>
                            <p class="text-sm text-uppercase text-spaced text-ellipsis">
                                <a href="{{ $podcast->website }}" class="link-softer text-medium">
                                    {{ $podcast->websiteHost() }}
                                </a>
                            </p>
                        </div>
                        <div class="">
                            @if ($podcast->isOwnedBy(Auth::user()))
                                <publish-button :data-podcast="{{ $podcast }}" class="mr-2"></publish-button>
                                <a href="{{ url("/podcasts/{$podcast->id}/edit") }}" class="btn btn-sm btn-secondary">
                                    Settings
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <subscribe-button
                            :data-subscription="{{ json_encode(Auth::user()->subscriptionTo($podcast)) }}"
                            :podcast="{{ $podcast }}"
                        ></subscribe-button>
                    </div>
                    <p class="text-dark-soft">{{ $podcast->description }}</p>
                </div>
                @if (count($episodes) > 0)
                    <div>
                        <div class="flex mb-4 flex-y-baseline">
                            <h2 class="mr-4 text-lg">Recent Episodes</h2>
                            <a href="{{ url("/podcasts/{$podcast->id}/episodes") }}" class="link-brand">View all</a>
                        </div>
                        <div class="text-sm">
                        @foreach ($podcast->episodes->sortByDesc('number')->take(5) as $episode)
                            <div class="flex border-t flex-y-baseline">
                                <div style="flex: 0 0 3rem;" class="py-3 pr-4 text-right text-no-wrap text-dark-softest">{{ $episode->number }}</div>
                                <div class="text-ellipsis flex-fill">
                                    <a href="{{ url("/episodes/{$episode->id}") }}">{{ $episode->title }}</a>
                                </div>
                                <div style="flex: 0 0 6rem;" class="pr-4 text-right text-no-wrap text-dark-softer">
                                    {{ $episode->durationForHumans() }}
                                </div>
                                <div style="flex: 0 0 6.5rem;" class="pr-4 text-no-wrap text-dark-softer">
                                    {{ $episode->published_at->format('M j, Y') }}
                                </div>
                                <div style="flex: 0 0 4.5rem;" class="text-right text-no-wrap">
                                    <a href="{{ url("/episodes/{$episode->id}") }}" class="btn btn-xs btn-secondary">Listen</a>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                @else
                    <div class="py-6 text-lg text-center text-dark-soft">
                        This podcast hasn't published any episodes yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
