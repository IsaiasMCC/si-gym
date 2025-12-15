<template>
    <div class="min-h-screen p-6">

        <Head title="Editar Rutina" />

        <h1 class="mb-8 text-3xl font-bold">
            <Link class="text-indigo-400" href="/inf513/grupo18sc/proyecto2/sis-gym/public/rutinas-usuarios">
            Rutinas
            </Link> / Editar
        </h1>

        <div class="max-w-3xl rounded-md shadow overflow-hidden" :style="{ backgroundColor: 'var(--color-card-bg)' }">
            <form @submit.prevent="update">
                <div class="p-8 flex flex-wrap -mr-6 -mb-8">
                    <select-input v-model="form.user_id" class="pb-8 pr-6 w-full" label="Cliente" disabled>
                        <option :value="form.user_id">{{ rutinaUsuario?.usuario?.nombres }}</option>
                    </select-input>

                    <select-input v-model="form.rutina_id" class="pb-8 pr-6 w-full" label="Rutina">
                        <option v-for="r in rutinas" :key="r.id" :value="r.id">{{ r.nombre }}</option>
                    </select-input>

                    <select-input v-model="form.estado" class="pb-8 pr-6 w-full" label="Estado">
                        <option value="activa">Activa</option>
                        <option value="finalizada">Finalizada</option>
                    </select-input>
                </div>

                <div class="flex items-center px-8 py-4 border-t">
                    <button type="button" class="text-red-600" @click="destroy">Eliminar</button>
                    <loading-button class="btn-indigo ml-auto" :loading="form.processing">
                        Guardar
                    </loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
    layout: Layout,
    components: { Head, Link, SelectInput, LoadingButton },
    props: { rutinaUsuario: Object, rutinas: Array },
    data() {
        return {
            form: this.$inertia.form({
                user_id: this.rutinaUsuario.user_id,
                rutina_id: this.rutinaUsuario.rutina_id,
                estado: this.rutinaUsuario.estado,
            }),
        }
    },
    methods: {
        update() {
            this.form.put(`/inf513/grupo18sc/proyecto2/sis-gym/public/rutinas-usuarios/${this.rutinaUsuario.id}`)
        },
        destroy() {
            if (confirm('¿Eliminar asignación?'))
                this.$inertia.delete(`/inf513/grupo18sc/proyecto2/sis-gym/public/rutinas-usuarios/${this.rutinaUsuario.id}`)
        },
    },
}
</script>
