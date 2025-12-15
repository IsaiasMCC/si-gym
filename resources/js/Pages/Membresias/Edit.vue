<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">
    <Head :title="form.nombre" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/inf513/grupo18sc/proyecto2/sis-gym/public/membresias">Membresías</Link>
      <span class="text-indigo-400 font-medium">/</span> Editar
      {{ form.nombre }}
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden" :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.nombre" :error="form.errors.nombre" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre*" />
          <text-input v-model="form.duracion_dias" :error="form.errors.duracion_dias" class="pb-8 pr-6 w-full lg:w-1/2" label="Duración (días)*" type="number" />
          <text-input v-model="form.precio_base" :error="form.errors.precio_base" class="pb-8 pr-6 w-full lg:w-1/2" label="Precio*" type="number" step="0.01" />
          <text-input v-model="form.descripcion" :error="form.errors.descripcion" class="pb-8 pr-6 w-full lg:w-1/2" label="Descripción" />
          <select-input v-model="form.estado" :error="form.errors.estado" class="pb-8 pr-6 w-full lg:w-1/2" label="Estado*">
            <option :value="'activo'">Activo</option>
            <option :value="'inactivo'">Inactivo</option>
          </select-input>
        </div>

        <div class="flex items-center px-8 py-4 border-t"
             :style="{ backgroundColor: 'var(--color-card-bg)', borderColor: 'var(--color-border)' }">
          <button class="text-red-600 hover:underline" type="button" @click="destroy">Eliminar Membresía</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Actualizar Membresía</loading-button>
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
  props: { membresia: Object },
  data() {
    return {
      form: this.$inertia.form({
        nombre: this.membresia.nombre,
        duracion_dias: this.membresia.duracion_dias,
        precio_base: this.membresia.precio_base,
        descripcion: this.membresia.descripcion,
        estado: this.membresia.estado
      }),
    }
  },
  methods: {
    update() { this.form.put(`/inf513/grupo18sc/proyecto2/sis-gym/public/membresias/${this.membresia.id}`) },
    destroy() { if(confirm('¿Seguro que quieres eliminar esta membresía?')) this.$inertia.delete(`/inf513/grupo18sc/proyecto2/sis-gym/public/membresias/${this.membresia.id}`) },
  },
}
</script>
