<main class="p-8 container flex-1">
	<header class="prose">
		<h2><?= $title ?></h2>
	</header>

	<?php echo validation_errors(); ?>

	<?php echo form_open('posts/update'); ?>
		<input type="hidden" name="id" value="<?php echo $post['id']; ?>">

		<div class="form-group mt-4">
			<label for="title">Title</label>
			<input name="title" type="text" class="form-control" id="title" placeholder="Add Title" value="<?php echo $post['title']; ?>">
		</div>

		<div class="form-group mt-4">
			<label for="body">Body</label>
			<textarea name="body" class="form-control" id="editor-1" placeholder="Add Body"><?php echo $post['body']; ?></textarea>
		</div>

		<div class="form-group mt-4">
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
			<button type="submit" class="btn btn-default py-1 px-4 border rounded">Submit</button>
		</div>
	</form>
</main>
