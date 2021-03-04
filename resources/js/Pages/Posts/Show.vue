<template>
  <app-layout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Create Post
      </h2>
    </template>
    <div v-if="$page.user">
      <div v-if="$page.user.id === post.owner_id">
        <inertia-link :href="this.editUrl" :class="linkClasses" v-if="$page.user">Edit</inertia-link>
        <form @submit.prevent="deletePost">
          <button :class="linkClasses" class="bg-red-600" type="submit" v-if="$page.user">Delete</button>
        </form>
      </div>
    </div>
    <div class="cont">
      <img :src="post.cover_image" alt="image">
      <h1 class="text-xl text-gray-700">{{ post.title }}</h1>
      <p class="my-4 text-md">{{ post.body }}</p>
      <h3 class="text-xs text-red-300">{{ post.created_at }}</h3>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "./../../Layouts/AppLayout";
export default {
  props: ["post"],
  data() {
    return {
      form: this.$inertia.form(
        {},
        {
          bag: "deletePost",
        }
      ),
      editUrl: "/posts/" + this.post.id + "/edit",
      deleteUrl: "/posts/" + this.post.id,
    };
  },

  components: {
    AppLayout,
  },
  computed: {
    linkClasses() {
      return "inline-flex items-center my-2 px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 transition ease-in-out duration-150";
    },
  },
  methods: {
    deletePost() {
      this.form.delete(this.deleteUrl, {});
    },
  },
};
</script>

<style scoped>
.cont {
  width: 80%;
  margin: 10vh auto;
  background-color: white;
}
</style>
