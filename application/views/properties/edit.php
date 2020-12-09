<main class="flex-1 p-8 container">
	<header class="prose">
		<h2>Edit details</h2>
	</header>

	<article class="row column">
		<div class="row column text-left">
			<!--<form method="post">-->
			<?php echo form_open_multipart(''); ?>
				<label>Name</label>
				<input type="text" name="name" value="<?php echo $property['name'] ?>">

				<label>Description</label>
				<textarea name="description"><?php echo $property['description'] ?></textarea>

				<input type="file" name="image_file" />
				<input class="button success" type="submit" value="SAVE">
			</form>
		</div>
	</article>
</main>
