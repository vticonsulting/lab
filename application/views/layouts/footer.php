<footer class="p-4 mt-8 text-xs bg-gray-700 text-primary-50">
	<p class="flex items-center justify-between">
		<span>Page rendered in <strong>{elapsed_time}</strong> seconds.</span>

		<!-- <a href="base_url('index') ?>">Sitemap</a> -->

		<span class="flex items-center space-x-1">
			<svg class="w-4 h-4" viewBox="0 0 24 24">
				<path
					fill="currentColor"
					style="color: var(--color-codeigniter, #ee4623)"
					d="M8.49 24c-1.54-0.68-2.586-2.146-2.723-3.824 0.090-1.727 1.002-3.305 2.45-4.246-0.238 0.58-0.18 1.24 0.15 1.77 0.376 0.525 1.022 0.777 1.655 0.646 0.902-0.254 1.43-1.19 1.176-2.092-0.090-0.316-0.27-0.602-0.516-0.818-1.020-0.83-1.532-2.133-1.35-3.436 0.175-0.69 0.557-1.314 1.096-1.785-0.405 1.080 0.737 2.146 1.504 2.67 1.36 0.816 2.67 1.713 3.924 2.686 1.37 1.080 2.117 2.77 2 4.5-0.308 1.84-1.61 3.36-3.385 3.93 3.55-0.79 7.21-3.61 7.28-7.61-0.070-3.2-1.98-6.072-4.9-7.38h-0.13c0.065 0.157 0.096 0.326 0.090 0.496 0.010-0.11 0.010-0.22 0-0.33 0.016 0.13 0.016 0.26 0 0.39-0.222 0.91-1.14 1.47-2.052 1.248-0.364-0.090-0.69-0.295-0.924-0.59-1.17-1.5 0-3.207 0.196-4.857 0.12-2.11-0.844-4.127-2.554-5.36 0.856 1.427-0.284 3.3-1.113 4.366s-2.030 1.86-3.008 2.79c-1.054 0.98-2.020 2.058-2.887 3.21-1.874 2.29-2.61 5.31-2 8.205 0.836 2.79 3.155 4.886 6.015 5.43z"
				></path>
			</svg>

			<a class="hover:underline" target="docs" href="<?= $this->config->item('site_docs_url')?>">
				<?= (ENVIRONMENT === 'development')
                    ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>'
                    : null
                ?>
			</a>
		</span>
	</p>

	<!-- <div id="components-app" class="demo">
		<ol>
			<todo-item
				v-for="item in groceryList"
				v-bind:todo="item"
				v-bind:key="item.id"
			></todo-item>
		</ol>
	</div> -->
</footer>

<!-- Floating Footer Banner -->
<div class="fixed inset-x-0 bottom-0 z-10 pb-2 sm:pb-16" style="display: none">
  <div class="px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="p-2 rounded-lg shadow-lg bg-primary-600 sm:p-3">
      <div class="flex flex-wrap items-center justify-between">
        <div class="flex items-center flex-1 w-0">
          <span class="flex p-2 rounded-lg bg-primary-800">
            <!-- Heroicon name: speakerphone -->
            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
          </span>
          <p class="ml-3 font-medium text-white truncate">
            <span class="md:hidden">
              We announced a new product!
            </span>
            <span class="hidden md:inline">
              Big news! We're excited to announce a brand new product.
            </span>
          </p>
        </div>
        <div class="flex-shrink-0 order-3 w-full mt-2 sm:order-2 sm:mt-0 sm:w-auto">
          <a href="#" class="flex items-center justify-center px-4 py-2 text-sm font-medium bg-white border border-transparent rounded-md shadow-sm text-primary-600 hover:bg-primary-50">
            Learn more
          </a>
        </div>
        <div class="flex-shrink-0 order-2 sm:order-3 sm:ml-2">
          <button type="button" class="flex p-2 -mr-1 rounded-md hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-white">
            <span class="sr-only">Dismiss</span>
            <!-- Heroicon name: x -->
            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

<!-- Filepond -->
<script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>

<!-- Muuri -->
<script src="https://cdn.jsdelivr.net/npm/muuri@0.9.0/dist/muuri.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/web-animations-js@2.3.2/web-animations.min.js"></script>

<!-- Tagify -->
<script src="https://unpkg.com/@yaireo/tagify"></script>
<script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>

<!-- Litepicker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/litepicker/dist/js/main.js"></script>

<!-- Vue.js -->
<script src="https://unpkg.com/vue@next"></script>

<script>
	// CKEDITOR.replace('editor-1');

	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth'
		});

		calendar.render();
	});


	$(document).ready( function () {
		$('#example-table').DataTable();
	});

	const Counter = {
		data() {
			return {
				counter: 0
			}
		},
		mounted() {
			setInterval(() => {
				this.counter++
			}, 1000)
		}
	}
	Vue.createApp(Counter).mount('#counter')

	var element = document.querySelector("trix-editor")
	element.editor  // is a Trix.Editor instance

	// window.VueUse
</script>
</body>
</html>
