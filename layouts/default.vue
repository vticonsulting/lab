<template>
  <Layout>
    <div class="overflow-hidden">
      <Nuxt keep-alive :key="$route.params.id" />
    </div>

    <template #header>
      <Navbar />
    </template>

    <template #footer>
      <Footer />
    </template>

    <PortalTarget name="modals" />
    <SvgSprite />
  </Layout>
</template>

<script>
export default {
  name: 'DefaultLayout',
  head() {
    return {
      title: '',
      titleTemplate: chunk => {
        if (chunk) {
          return `${chunk} - UI Exercise`
        }
        return 'UI Exercise'
      },
      bodyAttrs: {
        // class: ['bg-gray-100 dark:bg-gray-800 antialiased'],
      },
    }
  },
}
</script>

<style>
body {
  background-color: #fff;
  color: rgba(0, 0, 0, 0.8);
}
.dark-mode body {
  background-color: #091a28;
  color: #ebf4f1;
}
.sepia-mode body {
  background-color: #f1e7d0;
  color: #433422;
}
</style>
