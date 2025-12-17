<template>
  <div :style="{ color: 'var(--color-text)' }">
    <Head title="Crear Servicio" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/servicios">Servicios</Link>
      <span class="text-indigo-400 font-medium">/</span> Crear
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden transition-colors"
         :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">

          <text-input 
            v-model="form.nombre" 
            :error="form.errors.nombre" 
            class="pb-8 pr-6 w-full" 
            label="Nombre" 
          />

          <textarea-input 
            v-model="form.detalles" 
            :error="form.errors.detalles" 
            class="pb-8 pr-6 w-full" 
            label="Detalles" 
          />

          <text-input 
            v-model="form.precio" 
            :error="form.errors.precio" 
            class="pb-8 pr-6 w-full" 
            label="Precio" 
            type="number" 
            step="0.01"
          />

          <select-input 
            v-model="form.estado" 
            :error="form.errors.estado" 
            class="pb-8 pr-6 w-full" 
            label="Estado"
          >
            <option :value="true">Activo</option>
            <option :value="false">Inactivo</option>
          </select-input>

        </div>

        <div class="flex items-center justify-end px-8 py-4 border-t border-gray-100"
             :style="{ backgroundColor: 'var(--color-bg)' }">
          <loading-button 
            :loading="form.processing" 
            class="btn-indigo" 
            type="submit"
          >
            Crear Servicio
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import TextareaInput from '@/Shared/TextareaInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: { Head, Link, LoadingButton, TextInput, TextareaInput, SelectInput },
  layout: Layout,
  data() {
    return {
      form: this.$inertia.form({
        nombre: '',
        detalles: '',
        precio: '',
        estado: true,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/servicios')
    },
  },
}
</script>
