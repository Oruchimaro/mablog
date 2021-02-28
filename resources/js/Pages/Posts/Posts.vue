<template>
  <app-layout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Posts</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <inertia-link href="/posts/create" :class="linkClasses" v-if="$page.user">create</inertia-link>

        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
          <div v-for="post in posts" :key="post.id">
            <inertia-link :href="'posts/'+ post.id " class="text-xl text-blue-800">{{ post.title }}</inertia-link>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "./../../Layouts/AppLayout";
export default {
  props: ["posts"],
  components: {
    AppLayout,
  },
  computed: {
    linkClasses() {
      return "inline-flex items-center my-2 px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150";
    },
  },
};
</script>

<style scoped>
</style>
