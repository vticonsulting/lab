<!DOCTYPE html>
<html lang="<?= $this->config->item('language') or 'en' ?>">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="{{ $page->description }}">
	<link rel="canonical" href="<?= site_url() ?>">
	<title>
		<?= isset($title)
            ? $title . ' -- ' . $this->config->item('site_name')
            : $this->config->item('site_name')
        ?>
	</title>

	<link rel="icon" type="image/png" href="<?= base_url('/assets/img/favicon.png') ?>">

	<!-- OpenGraph -->
	<meta property="og:image" content="/assets/images/og-image.png">
	<meta property="twitter:card" content="summary_large_image">
	<meta property="og:url" content="<?= base_url() ?>"/>
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?= isset($title) ? $title : $this->config->item('site_name') ?>">

	<link rel="stylesheet" href="https://unpkg.com/@tailwindcss/typography@0.2.x/dist/typography.min.css">

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Work+Sans:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/application.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css') ?>">

	<style>
		:root {
			--color-primary: #95ba3d;
			--color-codeigniter: #ee4623;
		}
	</style>

	<!-- FullCalendar -->
	<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.4.0/main.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.4.0/main.min.js"></script>

	<!-- FilePond -->
	<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
	<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
	<link href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css" rel="stylesheet">

	<!-- Tagify -->
	<link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

	<!-- Litepicker -->
	<link src="https://cdn.jsdelivr.net/npm/litepicker/dist/css/style.css"/>

	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

	<!-- FIX Only load this script on pages requiring the WYSIWYG edtior -->
	<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>

	<!-- DataTables -->
	<link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
	<div class="flex flex-col min-h-screen">
		<!-- <input type="hidden" id="hidSiteURL" value=""> -->
		<?php if ($this->session->flashdata('user_registered')): ?>
			<?php echo '<p class="alert alert-success">' . $this->session->flashdata('user_registered') . '</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('post_created')): ?>
			<?php echo '<p class="alert alert-success">' . $this->session->flashdata('post_created') . '</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('post_updated')): ?>
			<?php echo '<p class="alert alert-success">' . $this->session->flashdata('post_updated') . '</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('category_created')): ?>
			<?php echo '<p class="alert alert-success">' . $this->session->flashdata('category_created') . '</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('post_deleted')): ?>
			<?php echo '<p class="alert alert-success">' . $this->session->flashdata('post_deleted') . '</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('login_failed')): ?>
			<?php echo '<p class="alert alert-danger">' . $this->session->flashdata('login_failed') . '</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('user_loggedin')): ?>
			<?php echo '<p class="alert alert-success">' . $this->session->flashdata('user_loggedin') . '</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('user_loggedout')): ?>
			<?php echo '<p class="alert alert-success">' . $this->session->flashdata('user_loggedout') . '</p>'; ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('category_deleted')): ?>
			<?php echo '<p class="alert alert-success">' . $this->session->flashdata('category_deleted') . '</p>'; ?>
		<?php endif; ?>
