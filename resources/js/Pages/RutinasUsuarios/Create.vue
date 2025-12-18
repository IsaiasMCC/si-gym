<template>
  <div class="min-h-screen p-6">

    <Head title="Asignar Rutina" />

    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400" :href="route('rutinas-usuarios.index')">
      Rutinas
      </Link> / Asignar
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden" :style="{ backgroundColor: 'var(--color-card-bg)' }">
      <form @submit.prevent="store">
        <div class="p-8 flex flex-wrap -mr-6 -mb-8">
          <select-input v-model="form.user_id" class="pb-8 pr-6 w-full" label="Cliente*" :error="form.errors.user_id">
            <option :value="null" />
            <option v-for="u in clientes" :key="u.id" :value="u.id">
              {{ u.nombres }} {{ u.apellidos }}
            </option>
          </select-input>

          <select-input v-model="form.rutina_id" class="pb-8 pr-6 w-full" label="Rutina*"
            :error="form.errors.rutina_id">
            <option :value="null" />
            <option v-for="r in rutinas" :key="r.id" :value="r.id">
              {{ r.nombre }}
            </option>
          </select-input>

          <text-input v-model="form.fecha_asignacion" type="date" class="pb-8 pr-6 w-full" label="Fecha*"
            :error="form.errors.fecha_asignacion" />

        </div>

        <div class="flex justify-end px-8 py-4 border-t">
          <loading-button class="btn-indigo" :loading="form.processing">
            Asignar Rutina
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
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  layout: Layout,
  components: { Head, Link, TextInput, SelectInput, LoadingButton },
  props: { clientes: Array, rutinas: Array },
  data() {
    return {
      form: this.$inertia.form({
        user_id: null,
        rutina_id: null,
        fecha_asignacion: '',
      }),
      route,
    }
  },
  methods: {
    store() {
      this.form.post(route('rutinas-usuarios.store'))
    },
  },
}
</script>
