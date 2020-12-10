import path from 'path'

export default {
  env: {
    baseUrl: process.env.BASE_URL || 'http://localhost:3000',
    apiUrl: process.env.API_URL || 'http://localhost.3000/api',
    assetsUrl: process.env.ASSETS_URL || 'http://localhost:3000',
  },

  components: true,
  loading: false,
  ssr: false,
  target: 'static',

  head: {
    title: 'Lab',
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1'},
      {hid: 'description', name: 'description', content: 'Lab'},
    ],
    link: [
      {rel: 'icon', type: 'image/x-icon', href: '/favicon.svg'},
      {
        rel: 'stylesheet',
        href: 'https://fonts.googleapis.com/css?family=Inter:400,500,600&display=swap',
      },
    ],
    script: [
      {
        src: 'https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js',
        defer: true,
      },
    ],
  },

  modules: ['@nuxt/content', '@nuxtjs/axios', '@nuxtjs/sentry', 'nuxt-i18n'],

  buildModules: [
    '@nuxtjs/color-mode',
    '@nuxtjs/eslint-module',
    '@nuxtjs/google-analytics',
    '@nuxtjs/svg',
    '@nuxtjs/tailwindcss',
    'nuxt-ackee',
  ],

  plugins: [
    '@/plugins/portal-vue',
    '@/plugins/vue-content-placeholders',
    // '@/plugins/vue-feather-icons',
  ],

  serverMiddleware: ['@/server'],

  router: {
    linkActiveClass: 'is-active',
    linkExactActiveClass: 'is-exact-active',
  },

  ackee: {
    server: 'https://cranky-borg.herokuapp.com',
    domainId: '601bbeb1-8a0a-4d5d-ba1f-a75ce1cefda3',
    ignoreLocalhost: true,
    detailed: true,
  },

  axios: {
    baseURL: process.env.apiUrl || 'http://localhost:3000/api',
    credentials: true,
  },

  colorMode: {
    preference: 'system',
  },

  content: {
    markdown: {
      remarkPlugins: [['remark-emoji', {emoticon: true}]],
      prism: {
        theme: 'prism-themes/themes/prism-material-oceanic.css',
      },
    },
    nestedProperties: ['author.name', 'categories.slug'],
    googleAnalytics: {
      id: process.env.GOOGLE_ANALYTICS_ID || 'UA-76464598-4',
    },
  },

  googleAnalytics: {
    id: process.env.GOOGLE_ANALYTICS_ID,
  },

  i18n: {
    locales: [
      {
        code: 'es',
        file: 'es-ES.js',
        iso: 'en-ES',
        name: 'Español',
      },
      {
        code: 'en',
        file: 'en-US.js',
        iso: 'en-US',
        name: 'English',
      },
    ],
    defaultLocale: 'en',
    parsePages: false,
    lazy: true,
    seo: false,
    langDir: 'i18n/',
  },

  sentry: {
    dsn: 'https://c30dc69c78434050aed6f64b97cbd645@o244691.ingest.sentry.io/1422222',
    config: {
      maxBreadcrumbs: 50,
      debug: false,
    },
  },

  storybook: {
    stories: ['~/components/**/*.stories.mdx', '~/components/**/*.stories.@(js|jsx|ts|tsx)'],
    parameters: {
      viewMode: 'docs',
      actions: {argTypesRegex: '^on[A-Z].*'},
    },
    addons: ['@storybook/addon-docs', '@storybook/addon-controls'],
    webpackFinal(config) {
      return config
    },
  },

  tailwindcss: {
    exposeConfig: true,
  },

  build: {
    extend(config, ctx) {},
    babel: {
      plugins: ['@babel/plugin-syntax-jsx'],
    },
    postcss: {
      plugins: {
        'postcss-import': {},
        tailwindcss: path.resolve(__dirname, './tailwind.config.js'),
        'postcss-preset-env': {},
      },
    },
    preset: {
      stage: 1,
    },
  },

  purgeCSS: {
    whitelist: ['dark-mode'],
    whitelistPatternsChildren: [/^token/, /^pre/, /^code/, /^nuxt-content/],
  },

  generate: {
    interval: 2000,
    fallback: true,

    // fallback: '404.html',
    // async routes() {
    //   const {$content} = require('@nuxt/content')
    //   const files = await $content('blog').fetch()

    //   return files.map(file => (file.path === '/index' ? '/' : file.path))
    // },
  },
}
