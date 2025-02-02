<nav x-data="{ open: false }" class="bg-white shadow-lg">
	<!-- Desktop_view -->
	<div class="px-4 mx-auto sm:px-6 lg:px-8">

		<!-- Navbar_O1_navbar_links_container -->
		<div class="flex justify-between h-16">

			<!-- Primary_navbar_links -->
			<div class="flex">
				<!-- Mobile_menu_button -->
				<div class="flex items-center mr-2 -ml-2 md:hidden">
					<button @click="open = !open" class="inline-flex items-center justify-center p-2 text-gray-400 rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500" x-bind:aria-expanded="open">
						<span class="sr-only">Open main menu</span>

						<!-- Icon when menu is closed. -->
						<svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': open, 'block': !open }" class="block w-6 h-6" x-description="Heroicon name: menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
						</svg>

						<!-- Icon when menu is open. -->
						<svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': !open, 'block': open }" class="hidden w-6 h-6" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
						</svg>
					</button>
				</div>

				<!-- Branding_container -->
				<div class="flex items-center flex-shrink-0">
					<!-- Default_navbar_brand_link -->
					<a class="flex items-center flex-shrink-0" href="<?= base_url() ?>">
						<svg class="w-10 h-10 text-primary-500" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M16.7198 20.1C16.8531 18.4666 17.3198 16.3 17.9198 14.5333C18.4864 12.9666 18.8531 11.6 18.7864 11.5C18.4531 11.2 15.5864 17.8 15.1864 19.7666L14.7864 21.8L14.0198 20.6333C12.0198 17.7666 12.4531 14.9 15.2531 12.1333C16.7198 10.7 20.5864 8.1333 21.3198 8.1333C21.3864 8.1333 21.4531 9.2333 21.4864 10.5333C21.4864 11.8666 21.6198 14.4666 21.7531 16.3C21.9198 18.5 21.8864 19.9333 21.6531 20.5333C21.1864 21.7666 19.4198 22.8 17.8198 22.8H16.5198L16.7198 20.1Z" fill="currentColor"/>
							<path d="M8.01988 21.1665C1.58655 18.9332 0.21988 12.3665 4.35321 3.4665L5.65321 0.666504L6.61988 2.3665C7.18655 3.33317 9.01988 5.3665 10.9199 7.13317L14.2199 10.1998L12.9532 11.8665C11.6199 13.6665 10.9532 15.3665 10.9532 17.1665C10.9199 18.2665 10.9199 18.2665 10.4199 17.6332C9.45321 16.3998 7.71988 12.6665 6.78655 9.8665C6.28655 8.33317 5.78655 7.1665 5.71988 7.2665C5.41988 7.5665 6.98655 14.2665 7.95321 16.7332C8.45321 18.0665 9.28655 19.6665 9.75321 20.2998C11.0865 22.0665 10.8865 22.1665 8.01988 21.1665Z" fill="currentColor"/>
						</svg>
						<span class="ml-2 font-black">&lt;<?= $this->config->item('site_name') ?> /></span>
				

						<!-- <svg class="h-12 text-primary-500" width="60" height="24" viewBox="0 0 60 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M6.41025 23.8407C4.47868 23.4667 2.97634 22.7186 1.73416 21.5029C0.583016 20.3674 0.199303 19.5658 0.0367128 17.9226C-0.106367 16.4197 0.160281 15.0237 0.98624 12.8996C1.20736 12.3385 1.71464 11.2898 2.08535 10.6152C2.1699 10.4683 2.51459 9.80698 2.85278 9.13902C3.73727 7.40234 4.5177 6.28685 5.33716 5.56546C5.88996 5.08454 6.10458 4.95094 6.57284 4.81067C7.09963 4.65704 7.5809 4.61697 8.13371 4.7038C8.41987 4.74388 9.0182 4.80399 9.46695 4.83739C10.4555 4.90419 10.9368 5.02442 11.7107 5.40515C12.283 5.69237 13.6228 6.1733 13.8309 6.1733C14.039 6.1733 14.6113 6.52732 14.7024 6.71435C14.8389 6.98153 14.8259 7.1218 14.6113 7.60941C13.9089 9.19914 11.2099 12.1983 8.49141 14.4159C6.84599 15.7585 5.68835 16.9007 5.4152 17.4551C5.19408 17.8959 5.08351 18.5906 5.12254 19.2986C5.14205 19.7328 5.18757 19.9198 5.35667 20.2738C5.72087 21.0353 6.57935 21.9704 7.25572 22.3378L7.48335 22.4581L8.39385 21.8903C8.89463 21.5764 9.67506 21.042 10.1303 20.6947C11.1254 19.9465 12.4196 19.0515 13.4211 18.4303C13.8894 18.1431 14.1821 17.916 14.2211 17.8158C14.2536 17.7289 14.2926 17.248 14.3186 16.747C14.3447 16.1258 14.4032 15.6983 14.4812 15.4445C14.5528 15.2308 14.6048 14.9369 14.6048 14.7899C14.6048 14.6363 14.6503 14.3157 14.7024 14.0685C14.878 13.2403 15.0536 11.6906 15.1251 10.2812C15.1641 9.51308 15.2096 8.84512 15.2226 8.80505C15.2552 8.68481 15.9185 8.49779 16.4583 8.46439C16.9721 8.43099 17.4144 8.48443 18.9297 8.76497C19.2874 8.83176 19.6451 8.92528 19.7166 8.96536C19.9443 9.09227 20.0353 9.41957 20.0353 10.1476C20.0288 10.9625 19.8207 15.5247 19.7622 15.8921C19.7296 16.1592 19.7296 16.1592 20.8223 15.0504C21.4271 14.4359 22.4026 13.4807 22.9945 12.913C23.5863 12.3519 24.3147 11.6572 24.6139 11.3633C25.3553 10.6486 25.726 10.3547 26.1617 10.141C26.7666 9.84706 27.3714 9.71347 28.1128 9.71347C28.6916 9.71347 28.9518 9.74018 29.6086 9.88045C29.7842 9.92053 29.9143 10.3013 30.0509 11.183C30.2135 12.225 30.1549 13.287 29.8298 15.5247C29.6867 16.4598 29.5956 17.7289 29.6607 17.7289C29.9598 17.7289 34.7725 13.0599 35.4359 12.1248C35.8651 11.5236 36.7951 10.9024 37.7902 10.5484C38.1934 10.4015 38.4405 10.3614 38.8633 10.3547C39.3575 10.348 39.4486 10.3681 39.7217 10.5351C40.1965 10.8223 40.203 10.9091 39.8648 12.6324C39.7022 13.434 39.5461 14.2422 39.5136 14.4226C39.4876 14.6096 39.4291 14.8901 39.3901 15.0571C39.351 15.2241 39.2145 15.852 39.0844 16.4598C38.9543 17.0677 38.8047 17.7423 38.7592 17.9627C38.6226 18.5505 38.5706 19.853 38.6617 20.1736C38.7852 20.5811 39.0063 21.0687 39.0779 21.0687C39.1754 21.0687 39.7152 20.6412 39.9689 20.3674C40.0925 20.2271 40.3461 20 40.5217 19.853C40.7038 19.7061 40.9834 19.4189 41.146 19.2251L41.4452 18.8578L41.4842 18.0429C41.5688 16.206 42.1021 14.6296 43.5329 11.9912C44.5149 10.1677 45.1393 9.1457 45.4449 8.83844C45.8807 8.40427 46.0758 8.37088 47.8968 8.41763C50.7324 8.47775 52.4883 8.72489 53.1582 9.13902C53.3598 9.26594 53.646 9.40621 53.7826 9.45964C54.0037 9.53312 54.4719 10.0274 54.4719 10.181C54.4719 10.2412 53.0672 10.515 52.7745 10.515C52.6509 10.515 52.4168 10.4683 52.2477 10.4215C51.8445 10.2946 50.4527 10.0474 50.1405 10.0474C49.4707 10.0474 48.5927 11.0828 47.7407 12.8863C46.8952 14.663 46.3359 16.3797 46.0498 18.0963C45.9197 18.8711 45.8416 20.5343 45.9392 20.5343C46.0628 20.5343 46.6676 20 46.8692 19.7128C46.9798 19.5591 47.2659 19.125 47.5001 18.7442C49.4186 15.7117 49.8934 15.0705 51.7599 12.9731C52.5013 12.1315 52.5208 12.1315 53.8866 12.2116C54.8556 12.2717 55.3694 12.3586 55.7336 12.5122C56.2864 12.7593 56.2734 13.1134 55.6556 14.9302C55.3304 15.8987 55.0963 17.0009 54.9272 18.3501C54.8101 19.3053 54.8036 19.5057 54.9012 19.8797C54.9272 19.9599 55.2133 19.7061 55.9873 18.9112C57.0994 17.769 57.9579 17.0409 59.1675 16.226C59.8699 15.7451 60 15.6783 60 15.7651C60 15.852 58.8684 17.916 58.4326 18.6307C58.2505 18.9246 57.7172 19.686 57.2425 20.3206C56.7742 20.9618 56.2864 21.6298 56.1629 21.8035C55.7792 22.3445 55.3434 22.6451 54.6085 22.8722C53.9386 23.0859 53.2688 23.0392 52.5534 22.7319C51.6429 22.3512 51.2201 21.7701 50.4982 19.9666C50.2121 19.2385 50.1926 19.2251 50.0235 19.4857C49.5032 20.2672 49.0284 20.9886 48.9634 21.1021C48.9244 21.1756 48.7488 21.4762 48.5797 21.7701C48.2025 22.438 47.7017 22.9323 47.2399 23.106C46.9408 23.2195 46.7261 23.2396 45.627 23.2396C44.4954 23.2396 44.3003 23.2195 43.806 23.0859C43.5003 22.9991 43.0776 22.8321 42.863 22.7186C42.3492 22.438 41.8159 21.8836 41.7249 21.543L41.6533 21.2758L41.1851 21.5897C40.3201 22.1775 39.5917 22.418 38.5641 22.4581C37.5235 22.4981 36.8016 22.3111 35.4944 21.6565C34.8961 21.3559 34.8245 21.2624 34.701 20.6145C34.4538 19.3454 34.9091 17.435 36.1968 14.3558C36.7171 13.1 36.8081 12.6859 36.509 12.9664C36.4309 13.0332 35.4424 14.2823 34.2977 15.7451C33.1596 17.2079 32.028 18.5906 31.7938 18.8177C31.5597 19.0448 31.3061 19.2919 31.228 19.3654C30.6037 19.9799 29.6151 20.8683 29.4265 20.9886C29.2444 21.1021 29.0883 21.1355 28.7437 21.1355C28.4965 21.1355 28.2039 21.1088 28.0998 21.0754C26.9812 20.708 26.0186 19.9799 25.6935 19.2452C25.4593 18.7309 25.4203 17.9226 25.5504 16.4932C25.6089 15.872 25.6739 15.0905 25.6935 14.7632C25.7195 14.4359 25.7585 14.0151 25.7975 13.8281C25.8756 13.3672 26.0577 11.5637 26.0251 11.5303C25.9731 11.4835 25.6219 11.7107 25.3097 11.9979C25.1341 12.1515 24.7634 12.5857 24.4773 12.953C24.1976 13.3204 23.9375 13.6544 23.905 13.7012C23.8724 13.7412 23.7489 13.9349 23.6383 14.1353C23.2936 14.7098 22.7668 15.4178 22.1945 16.0791C21.5572 16.8072 20.9523 17.5686 20.6532 18.0295C20.5296 18.2165 20.0613 18.7375 19.6061 19.1984C19.0208 19.7929 18.7086 20.1736 18.5135 20.5143C18.2143 21.0487 17.9867 21.2691 17.733 21.2691C17.642 21.2691 17.3623 21.1889 17.1087 21.0954C16.8616 21.0019 16.5819 20.9017 16.4908 20.875C15.7624 20.6212 15.008 20.1937 14.7804 19.8864L14.6568 19.7261L14.0585 20.354C13.7333 20.6947 13.2911 21.1155 13.0765 21.2825C12.7188 21.563 11.6001 22.3512 11.34 22.5048C11.275 22.5382 10.8977 22.7787 10.4945 23.0392C9.51898 23.6604 9.43443 23.7071 8.92065 23.8674C8.33532 24.0545 7.48335 24.0411 6.41025 23.8407ZM7.53538 11.5236C9.22632 9.96729 10.4555 8.57794 11.1384 7.46246C11.2945 7.19527 11.418 6.94145 11.405 6.90137C11.3725 6.80786 10.514 6.58076 10.1693 6.57408C10.0328 6.57408 9.84416 6.62751 9.7401 6.69431C9.30436 6.96817 8.49141 7.78975 7.84105 8.6247C7.35328 9.2459 6.61186 10.8022 6.42976 11.5837C6.3257 12.0246 6.3257 12.0513 6.44277 12.1849C6.50781 12.2584 6.58585 12.3185 6.61837 12.3185C6.64438 12.3185 7.06061 11.9645 7.53538 11.5236Z" fill="currentColor"/>
							<path d="M1.56485 3.78884C1.33072 3.7354 1.04457 3.60849 0.862464 3.4749C0.673859 3.33463 0.212103 2.53308 0.140563 2.21914C0.0560163 1.84509 0.3877 1.73822 1.93556 1.63134C3.66552 1.51111 5.2459 1.27065 6.89781 0.863194C7.29453 0.769681 7.89287 0.62941 8.23105 0.555935C8.56924 0.489139 9.29114 0.33551 9.82444 0.221957C10.5593 0.061648 10.9886 0.00821156 11.5479 0.001532C12.2568 -0.00514755 12.3153 0.001532 12.7185 0.201919C13.3624 0.522537 13.6421 0.763001 13.7851 1.13038C13.8827 1.3842 13.8892 1.45767 13.8242 1.49775C13.6291 1.61798 10.9951 2.09223 8.55624 2.43957C6.96285 2.66667 5.51255 2.96057 4.03623 3.36135C2.97614 3.64857 1.8315 3.84895 1.56485 3.78884Z" fill="currentColor"/>
						</svg> -->
					</a>
				</div>

				<!-- Navbar_links -->
				<div class="hidden md:ml-6 md:flex md:items-center md:space-x-2">
					<div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
						<div>
							<button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-primary-500" id="church-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
								Affiliates
								<svg class="w-5 h-5 ml-2 -mr-1" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
								</svg>
							</button>
						</div>

						<div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
							<div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Organization Settings</a>
								<a href="<?= base_url('reports') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Reports</a>
								<a href="<?= base_url('reports/map') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">MAP Report</a>
							</div>
						</div>
					</div>
					<div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
						<div>
							<button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-primary-500" id="church-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
								My Organization
								<svg class="w-5 h-5 ml-2 -mr-1" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
								</svg>
							</button>
						</div>

						<div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
							<div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Organization Settings</a>
								<a href="<?= base_url('reports') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Reports</a>
								<a href="<?= base_url('reports/map') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">MAP Report</a>
							</div>
						</div>
					</div>

					<div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
						<div>
							<button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-primary-500" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
								People
								<svg class="w-5 h-5 ml-2 -mr-1" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
								</svg>
							</button>
						</div>

						<div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
							<div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Advocates</a>
								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Volunteers</a>
								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Families</a>
								<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Communities</a>
							</div>
						</div>
					</div>

					<div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
						<div>
							<button @click="open = !open" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent whitespace-nowrap hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-primary-500" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="open" aria-expanded="true">
								Events & Needs
								<svg class="w-5 h-5 ml-2 -mr-1" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
								</svg>
							</button>
						</div>

						<div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute left-0 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
							<div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
								<a href="<?= base_url('calendar') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Calendar</a>
								<a href="<?= base_url('events') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Events</a>
								<a href="<?= base_url('care-requests') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Care Requests</a>
							</div>
						</div>
					</div>

					<a href="<?= base_url('resources') ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700">
						Resources
					</a>
				</div>
			</div>

			<!-- Secondary_navbar_links -->
			<div class="flex items-center">
				<div class="flex-shrink-0">

					<!-- Navbar_primary_action_button -->
					<!-- <button type="button" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						<svg class="w-5 h-5 mr-2 -ml-1" x-description="Heroicon name: plus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
							<path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
						</svg>
						<span>Call to Action</span>
					</button> -->
				</div>

				<div class="hidden md:ml-4 md:flex-shrink-0 md:flex md:items-center">
					<button class="p-1 text-gray-400 bg-white rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
						<span class="sr-only">View notifications</span>
						<svg class="w-6 h-6" x-description="Heroicon name: bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
						</svg>
					</button>

					<!-- Profile dropdown -->
					<div @click.away="open = false" class="relative ml-3" x-data="{ open: false }">
						<div>
							<button @click="open = !open" class="flex text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="user-menu" aria-haspopup="true" x-bind:aria-expanded="open">
								<span class="sr-only">Open user menu</span>

								<!-- Initials Avatar -->
								<span class="inline-flex items-center justify-center w-10 h-10 bg-gray-500 rounded-full">
									<span class="text-lg font-medium leading-none text-white">PS</span>
								</span>

								<!-- Generic Avatar -->
								<!-- <span class="inline-block w-8 h-8 overflow-hidden bg-gray-100 rounded-full">
									<svg class="w-full h-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
										<path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
									</svg>
								</span> -->
							</button>
						</div>
						<div x-show="open" x-description="Profile dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="user-menu" style="display: none;">
							<a href="<?= base_url('profile')?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
								My Profile
							</a>
							<a href="<?= base_url('requests')?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
								My Volunteer Requests
							</a>
							<a href="<?= base_url('requests')?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
								Church Volunteer Requests
							</a>
							<form method="POST" action="#">
								<button type="submit" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
									Logout
        						</button>
      						</form>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Mobile_view -->
	<div x-description="Mobile menu, toggle classes based on menu state." x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden">
		<div class="pt-2 pb-3 space-y-1">
			<!-- Current: "bg-primary-50 border-primary-500 text-primary-700", Default: "border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700" -->
			<a href="<?= base_url('dashboard') ?>" class="block py-2 pl-3 pr-4 text-base font-medium border-l-4 text-primary-700 border-primary-500 bg-primary-50 sm:pl-5 sm:pr-6">Dashboard</a>
			<a href="<?= base_url('properties') ?>" class="block py-2 pl-3 pr-4 text-base font-medium text-gray-500 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 sm:pl-5 sm:pr-6">Properties</a>
			<a href="<?= base_url('posts') ?>" class="block py-2 pl-3 pr-4 text-base font-medium text-gray-500 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 sm:pl-5 sm:pr-6">Blog</a>
			<a href="<?= base_url() ?>about" class="block py-2 pl-3 pr-4 text-base font-medium text-gray-500 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 sm:pl-5 sm:pr-6">About</a>
		</div>
		<div class="pt-4 pb-3 border-t border-gray-200">
			<div class="flex items-center px-4 sm:px-6">
				<div class="flex-shrink-0">
					<span class="inline-flex items-center justify-center w-10 h-10 bg-gray-500 rounded-full">
						<span class="text-xl font-medium leading-none text-white">AC</span>
					</span>
				</div>
				<div class="ml-3">
					<div class="text-base font-medium text-gray-800">Andy Cook</div>
					<div class="text-sm font-medium text-gray-500">andy@example.com</div>
				</div>
				<button class="flex-shrink-0 p-1 ml-auto text-gray-400 bg-white rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
					<span class="sr-only">View notifications</span>
					<svg class="w-6 h-6" x-description="Heroicon name: bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
					</svg>
				</button>
			</div>
		</div>
	</div>
</nav>

<!-- 
<nav>
	<ul>
		<li class="<?= ($this->uri->segment(1) == "home") ? 'underline' : '' ?>"><a href="<?php echo base_url();?>">Home</a></li>
		<li class="<?= ($this->uri->segment(1) == "about") ? 'underline' : '' ?>"><a href="<?php echo base_url('about');?>">About</a></li>
		<li class="<?= ($this->uri->segment(1) == "resources") ? 'underline' : '' ?>"><a href="<?php echo base_url('resources');?>">Resources</a></li>
	</ul>
</nav> -->
