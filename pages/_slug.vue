<template>
  <main class="max-w-screen-sm p-8">
    <h2 class="text-xl">
      {{ page.lead }}
    </h2>

    <NuxtContent :document="page" />

    <div class="mt-8">
      <article class="grid">
        <NuxtLink
          class="px-4 py-1 border rounded-md shadow hover:bg-primary-600 hover:text-primary-100"
          :to="link.path"
          :key="index"
          v-for="(link, index) in page.links"
        >
          {{ link.label }}
        </NuxtLink>
      </article>
    </div>
  </main>
</template>

<script>
export default {
  async asyncData({$content, params}) {
    const page = await $content(params.slug || 'index')
      .fetch()
      .catch(err => {
        console.log(err)
        // eslint-disable-next-line no-undef
        error({statusCode: 404, message: 'Page not found'})
      })

    return {page}
  },
  methods: {
    test(hash) {
      if (history.pushState) {
        history.pushState(null, null, hash)
      } else {
        location.hash = '#myhash'
      }
    },
    remove() {
      this.$refs['item-highlight'].classList.add('item-highlight')
    },
  },
}
</script>

<style>
:target {
  -webkit-animation: target-fade 3s 1;
  -moz-animation: target-fade 3s 1;
}

@-webkit-keyframes target-fade {
  0% {
    background-color: rgba(0, 0, 0, 0.1);
  }
  100% {
    background-color: rgba(0, 0, 0, 0);
  }
}
@-moz-keyframes target-fade {
  0% {
    background-color: rgba(0, 0, 0, 0.1);
  }
  100% {
    background-color: rgba(0, 0, 0, 0);
  }
}

@keyframes yellowfade {
  from {
    background: yellow;
  }
  to {
    background: transparent;
  }
}

p.item-highlight {
  animation: yellowfade 1s;
}
</style>
