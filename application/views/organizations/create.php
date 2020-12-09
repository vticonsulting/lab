<main class="container flex-1 p-8">
	<header class="prose">
		<h2><?= $title ?></h2>
	</header>

	<?php echo validation_errors(); ?>

	<?php echo form_open_multipart('organizations/create'); ?>
		<div class="form-group">
			<label for="title">Title</label>
			<input name="title" type="text" class="form-control" id="title" placeholder="Add Title">
		</div>

		<div class="form-group">
			<label for="body">Body</label>
			<textarea name="body" class="form-control" id="editor-1" placeholder="Add Body"></textarea>
		</div>

		<div class="form-group">
			<label for="category">Category</label>
			<select id="category" name="category_id" class="form-control">
				<?php foreach ($categories as $category) : ?>
					<option value="<?php echo $category['id']; ?>">
						<?php echo $category['name']; ?>
					</option>
				<?php endforeach ?>
			</select>
		</div>

		<div class="form-group">
			<label for="upload-image">Upload Image</label>
			<input id="upload-image" type="file" name="userfile" size="20">
		</div>

		<button type="submit" class="btn btn-default">Submit</button>
	</form>
</main>
