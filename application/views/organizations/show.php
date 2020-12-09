<main class="container flex-1 p-8">
	<header class="prose">
		<h2><?php echo $organization['title'] ?></h2>
	</header>

	<article class="prose organization-body">
		<small class="organization-date">
			<?php echo $organization['created_at']; ?>
		</small>

		<img src="<?php echo site_url(); ?>assets/images/organizations/<?php echo $organization['organization_image']; ?>">

		<?php echo $organization['body']; ?>

	</article>

	<?php if ($this->session->userdata('user_id') == $organization['user_id']): ?>
		<footer class="flex mt-4 space-x-4">
			<a href="<?php echo base_url(); ?>organizations/edit/<?php echo $organization['slug']; ?>" class="px-4 py-1 border rounded shadow btn btn-default">
				Edit
			</a>

			<?php echo form_open('/organizations/delete/' . $organization['id']); ?>
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

	<?php echo form_open('comments/create/' . $organization['id']); ?>
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

		<input type="hidden" name="slug" value="<?php echo $organization['slug']; ?>">
		<button class="btn btn-primary" type="submit">Submit</button>
	</form>
</main>
