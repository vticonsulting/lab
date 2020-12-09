export const state = () => ({
  isMenuOpen: false,
  site: {
    nav: {
      groups: [
        {
          label: 'Examples',
          path: 'examples',
          links: [
            {
              label: 'CodeIgniter',
              path: 'codeigniter',
            },
          ],
        },
      ],
    },
  },
})

export const mutations = {
  toggleMenu(state) {
    state.isMenuOpen = !state.isMenuOpen
  },
  openMenu(state) {
    state.isMenuOpen = true
  },
  closeMenu(state) {
    state.isMenuOpen = false
  },
}
