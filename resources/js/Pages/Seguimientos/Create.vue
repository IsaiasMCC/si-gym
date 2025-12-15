<template>
  <div class="min-h-screen p-6">
    <Head title="Nuevo Seguimiento" />

    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400" href="/inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos">Seguimientos</Link> / Crear
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden" :style="{ backgroundColor:'var(--color-card-bg)' }">
      <form @submit.prevent="store">
        <div class="p-8 flex flex-wrap -mr-6 -mb-8">
          <select-input v-model="form.user_id" label="Usuario*" class="pb-8 pr-6 w-full">
            <option :value="null">Seleccione usuario</option>
            <option v-for="u in usuarios" :key="u.id" :value="u.id">{{ u.nombres }} {{ u.apellidos }}</option>
          </select-input>

          <text-input v-model="form.peso" label="Peso (kg)*" type="number" step="0.1" class="pb-8 pr-6 w-full"/>
          <text-input v-model="form.altura" label="Altura (cm)*" type="number" step="0.1" class="pb-8 pr-6 w-full"/>
          <text-input v-model="form.fecha_registro" label="Fecha*" type="date" class="pb-8 pr-6 w-full"/>
          <text-input v-model="form.observaciones" label="Observaciones" type="text" class="pb-8 pr-6 w-full"/>
        </div>

        <div class="flex justify-end px-8 py-4 border-t">
          <loading-button :loading="form.processing" class="btn-indigo">Guardar Seguimiento</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  layout: Layout,
  components: { Head, Link, TextInput, SelectInput, LoadingButton },
  props: { usuarios: Array },
  data() {
    return {
      form: this.$inertia.form({
        user_id: null,
        peso: '',
        altura: '',
        fecha_registro: '',
        observaciones: '',
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos')
    },
  },
}
</script>
