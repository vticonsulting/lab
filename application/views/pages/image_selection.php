<main class="flex-1 bg-gray-400">
	<div class="container mx-auto px-4 mt-4" x-data="{ image: 'image1' }">
		<div>
			<img src="<?php base_url('assets/images/mac-pro.jpg') ?>" alt="mac pro" x-show="image === 'image1'">
			<img src="base_url('assets/images/pro-display-xdr.jpg') ?>" alt="mac pro" x-show="image === 'image2'">
			<img src="base_url('assets/images/macbook-pro.jpg') ?>" alt="mac pro" x-show="image === 'image3'">
		</div>

		<div class="flex items-center mt-4">
			<a href="#" class="border border-transparent hover:border-blue-500" :class="{ 'border-blue-500' : image === 'image1'}" @click.prevent="image = 'image1'"><img src="<?php base_url('assets/images/mac-pro.jpg') ?>" alt="mac pro" class="w-12"></a>
			<a href="#" class="border border-transparent hover:border-blue-500 ml-2" :class="{ 'border-blue-500' : image === 'image2' }" @click.prevent="image = 'image2'"><img src="<?php base_url('assets/images/pro-display-xdr.jpg') ?>" alt="mac pro" class="w-12"></a>
			<a href="#" class="border border-transparent hover:border-blue-500 ml-2" :class="{ 'border-blue-500' : image === 'image3' }" @click.prevent="image = 'image3'"><img src="<?php base_url('assets/images/macbook-pro.jpg') ?>" alt="mac pro" class="w-12"></a>
		</div>
	</div>
</main>
