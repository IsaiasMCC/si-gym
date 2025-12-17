<template>

    <Head title="Register" />
    <div class="flex items-center justify-center p-6 min-h-screen bg-indigo-800">
        <div class="w-full max-w-md">
            <logo class="block mx-auto w-full max-w-xs fill-white" height="50" />

            <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="register">
                <div class="px-10 py-12">
                    <h1 class="text-center text-3xl font-bold">Crear Cuenta</h1>
                    <div class="mt-6 mx-auto w-24 border-b-2" />

                    <text-input v-model="form.ci" :error="form.errors.ci" class="mt-10" label="CI" type="text"
                        autofocus />

                    <text-input v-model="form.nombres" :error="form.errors.nombres" class="mt-6" label="Nombres"
                        type="text" />

                    <text-input v-model="form.apellidos" :error="form.errors.apellidos" class="mt-6" label="Apellidos"
                        type="text" />

                    <text-input v-model="form.email" :error="form.errors.email" class="mt-6" label="Email"
                        type="email" />

                    <text-input v-model="form.password" :error="form.errors.password" class="mt-6" label="Password"
                        type="password" />

                </div>
                <div class="text-center">
                    <Link href="/inf513/grupo18sc/proyecto2/sis-gym/public/login" class="text-sm text-indigo-600 hover:underline">
                        ¿Ya tienes una cuenta? Inicia sesión aquí
                    </Link>
                </div>

                <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">
                        Register
                    </loading-button>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
    components: {
        Link,
        Head,
        Logo,
        TextInput,
        LoadingButton,
    },
    data() {
        return {
            form: this.$inertia.form({
                ci: '',
                nombres: '',
                apellidos: '',
                email: '',
                password: '',
                estado: true,
                role_id: 2, // 👈 por defecto
            }),
        }
    },

    methods: {
        register() {
            this.form.post('/inf513/grupo18sc/proyecto2/sis-gym/public/register')
        },
    },
}
</script>
