<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">
    <Head title="Crear Rutina" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/inf513/grupo18sc/proyecto2/sis-gym/public/rutinas">Rutinas</Link>
      <span class="text-indigo-400 font-medium">/</span> Crear
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden" :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.nombre" :error="form.errors.nombre" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre*" />
          <text-input v-model="form.descripcion" :error="form.errors.descripcion" class="pb-8 pr-6 w-full lg:w-1/2" label="Descripción" />
          <text-input v-model="form.objetivo" :error="form.errors.objetivo" class="pb-8 pr-6 w-full lg:w-1/2" label="Objetivo*" />
          <select-input v-model="form.nivel" :error="form.errors.nivel" class="pb-8 pr-6 w-full lg:w-1/2" label="Nivel*">
            <option value="principiante">Principiante</option>
            <option value="intermedio">Intermedio</option>
            <option value="avanzado">Avanzado</option>
          </select-input>
          <select-input v-model="form.entrenador_id" :error="form.errors.entrenador_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Entrenador*">
            <option disabled value="">Seleccione un entrenador</option>
            <option v-for="e in entrenadores" :key="e.id" :value="e.id">{{ e.nombres }} {{ e.apellidos }}</option>
          </select-input>
        </div>

        <div class="flex items-center justify-end px-8 py-4 border-t">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Crear Rutina</loading-button>
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
  components: { Head, Link, TextInput, SelectInput, LoadingButton },
  layout: Layout,
  props: { entrenadores: Array },
  data() {
    return {
      form: this.$inertia.form({
        nombre: '',
        descripcion: '',
        nivel: 'principiante',
        objetivo: '',
        entrenador_id: null
      }),
    }
  },
  methods: { store() { this.form.post('/inf513/grupo18sc/proyecto2/sis-gym/public/rutinas') } },
}
</script>
