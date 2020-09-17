<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Posts
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <inertia-link  href="/posts/create" :class="linkClasses">
                create
            </inertia-link>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="post in posts" :key="post.id">
                        <inertia-link  :href="'posts/'+ post.id " class="text-blue-800 text-xl">
                            {{ post.title }}
                        </inertia-link>

                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    export default {
        props : ['posts'],
        components: {
            AppLayout,
        },
        computed: {
            linkClasses() {
                return "inline-flex items-center my-2 px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150";
            }
        }
    }
</script>

<style scoped>

</style>
