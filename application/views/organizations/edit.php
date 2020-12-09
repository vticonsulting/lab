<main class="container flex-1 p-8">
	<header class="prose">
		<h2><?= $title ?></h2>
	</header>

	<?php echo validation_errors(); ?>

	<?php echo form_open('organizations/update'); ?>
		<input type="hidden" name="id" value="<?php echo $organization['id']; ?>">

		<div class="mt-4 form-group">
			<label for="title">Title</label>
			<input name="title" type="text" class="form-control" id="title" placeholder="Add Title" value="<?php echo $organization['title']; ?>">
		</div>

		<div class="mt-4 form-group">
			<label for="body">Body</label>
			<textarea name="body" class="form-control" id="editor-1" placeholder="Add Body"><?php echo $organization['body']; ?></textarea>
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
