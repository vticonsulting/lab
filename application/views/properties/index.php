<main class="flex-1 container p-8">
	<header class="prose">
		<h2>Welcome, <?php echo $user_name; ?></h2>

		<?php if (empty($selected_filter)) : ?>
			<form method="get" action="<?php echo site_url('properties/set_filter'); ?>">

				<select name="filter" class="p-3 border form-select mt-1 block w-full">
					<?php foreach ($status_group as $status) : ?>
						<option><?php echo $status; ?></option>
					<?php endforeach ?>
				</select>

				<div class="mt-4">
					<input class="px-4 py-1 border rounded shadow" type="submit" value="Select">
				</div>
			</form>
		<?php else: ?>
			<h4>Showing filter for: <?php echo $selected_filter ?></h4>
		<?php endif ?>
	</header>

	<div class="container mx-auto mt-8">
		<h3 class="font-semibold text-2xl">Properties details</h3>

		<table class="table mt-4">
			<tr>
				<td>IMAGE</td>
				<td>NAME</td>
				<td>LOCATION</td>
				<td>STATUS</td>
				<td>ACTION</td>
			</tr>
			<?php foreach ($properties as $property) { ?>
			<tr>
				<td><img src="<?php echo base_url("assets/images/{$property['image']}"); ?>" width="150" /></td>
				<td><?php echo $property['name'] ?></td>
				<td><?php echo $property['city'] ?>, <?php echo $property['state'] ?> </td>
				<td>Available</td>
				<td>
					<a href="<?php echo site_url('properties/show/' . $property['id']); ?>" class="button success">View Details</a>
					<a href="<?php echo site_url('properties/edit/' . $property['id']); ?>" class="button">Edit Details</a>
				</td>
			</tr>
			<?php } ?>
		</table>
	</div>
</main>