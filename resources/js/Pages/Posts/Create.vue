<template>
 <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Posts
            </h2>
        </template>
   
    <jet-form-section @submitted="storePost">
        <template #form class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="title" value="Title" />
                <jet-input id="title" type="text" class="mt-1 block w-full"
                    v-model="form.title" ref="title" autocomplete="title" />
                <jet-input-error :message="form.error('title')" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="body" value="Body" />
                <textarea class="mt-1 block w-full bg-gray-100"
                          rows="10"
                          id="body" 
                          name="body"
                          v-model="form.body"
                          autocomplete="body" >
                </textarea>
                <jet-input-error :message="form.error('body')" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="published" value="Publish Now" />
                <input :value="toggleCh" type="checkbox" id="published"
                class="mt-1" v-model="form.published" autocomplete="published" />
                <jet-input-error :message="form.error('published')" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
    </app-layout>
</template>

<script>
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetButton from './../../Jetstream/Button'
    import JetFormSection from './../../Jetstream/FormSection'
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import AppLayout from './../../Layouts/AppLayout'
    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            AppLayout,
        },

        data() {
            return {
                form: this.$inertia.form({
                    title:'',
                    body: '',
                    published: false
                }, {
                    bag: 'storePost',
                }),
            }
        },

        methods: {
            storePost() {
                this.form.post('/posts', {
                    preserveScroll: true
                }).then(() => {
                    this.$refs.title.focus()
                })
            },

            toggleCh(){
                this.form.published = !this.form.published;
            }
        },
    }
</script>

