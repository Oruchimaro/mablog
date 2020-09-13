<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Posts
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="post in posts" :key="post.id">
                        <h1 class="text-blue-800 text-xl">{{ post.title }}</h1>
                        <p> {{post.body}} </p>
                        </br>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../Layouts/AppLayout'

    export default {
        props : ['posts'],
         components: {
            AppLayout,
        },
    }
</script>
