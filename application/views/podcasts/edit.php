<main class="container flex-1 p-8">
	<header class="prose">
		<h2><?= $title ?></h2>
	</header>

	<?php echo validation_errors(); ?>

	<?php echo form_open('posts/update'); ?>
		<input type="hidden" name="id" value="<?php echo $post['id']; ?>">

		<div class="mt-4 form-group">
			<label for="title">Title</label>
			<input name="title" type="text" class="form-control" id="title" placeholder="Add Title" value="<?php echo $post['title']; ?>">
		</div>

		<div class="mt-4 form-group">
			<label for="body">Body</label>
			<textarea name="body" class="form-control" id="editor-1" placeholder="Add Body"><?php echo $post['body']; ?></textarea>
		</div>

		<div class="mt-4 form-group">
			<label for="category">Category</label>
			<select id="category" name="category_id" class="form-control">
				<?php foreach ($categories as $category) : ?>
					<option value="<?php echo $category['id']; ?>">
						<?php echo $category['name']; ?>
					</option>
				<?php endforeach ?>
			</select>
		</div>

		<div class="mt-4">
			<button type="submit" class="px-4 py-1 border rounded btn btn-default">Submit</button>
		</div>
	</form>
</main>



<div class="container mt-6">
    <h1 class="mb-6">Podcast Settings</h1>
    <div class="pb-6 mb-6 border-b row pull-x-4">
        <div class="px-4 col-4">
            <h2 class="mb-2 text-lg">Podcast Details</h2>
            <p class="text-dark-soft">Update your podcast title, description, and website URL.</p>
        </div>
        <div class="px-4 col-8">
            <form action="{{ url("/podcasts/{$podcast->id}") }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <label class="block mb-6">
                    <span class="block mb-2 text-medium">Title</span>
                    <input class="form-control" name="title" placeholder="The World's Best Podcast" value="{{ old('title', $podcast->title) }}">
                </label>
                <label class="block mb-6">
                    <span class="block mb-2 text-medium">Description</span>
                    <textarea rows="3" class="form-control" name="description" placeholder="The best podcast for getting the best information about the best stuff.">{{ old('description', $podcast->description) }}</textarea>
                </label>
                <label class="block mb-6">
                    <span class="block mb-2 text-medium">Website URL</span>
                    <input class="form-control" type="url" name="website" placeholder="http://example.com" value="{{ old('website', $podcast->website) }}">
                </label>
                <div class="text-right">
                    <button class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row pull-x-4">
        <div class="px-4 col-4">
            <h2 class="mb-2 text-lg">Cover Image</h2>
            <p class="text-dark-soft">Add a new cover image to your podcast.</p>
        </div>
        <div class="px-4 col-8">
            <cover-image-upload :podcast="{{ $podcast }}"></cover-image-upload>
        </div>
    </div>
</div>
