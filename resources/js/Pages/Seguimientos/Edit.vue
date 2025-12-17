<template>
  <div class="min-h-screen p-6">

    <Head title="Editar Seguimiento" />

    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400" :href="`/inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos/${rutinaUsuario.id}`">Seguimientos</Link> /
      Editar
    </h1>
    <div class="mb-6 p-6 max-w-3xl rounded-md shadow overflow-hidden"
      :style="{ backgroundColor: 'var(--color-card-bg)' }">
      <h2 class="text-xl font-bold mb-4">Rutina Usuario:</h2>
      <p><strong>Cliente:</strong> {{ rutinaUsuario.cliente?.nombres }} {{ rutinaUsuario.cliente?.apellidos }}</p>
      <p><strong>Rutina:</strong> {{ rutinaUsuario.rutina.nombre }}</p>
      <p><strong>Fecha Asignación:</strong> {{ rutinaUsuario.fecha_asignacion }}</p>
    </div>
    <div class="max-w-3xl rounded-md shadow overflow-hidden" :style="{ backgroundColor: 'var(--color-card-bg)' }">
      <form @submit.prevent="update">
        <div class="p-8 flex flex-wrap -mr-6 -mb-8">
          <!-- <select-input v-model="form.user_id" label="Usuario" class="pb-8 pr-6 w-full" disabled>
            <option :value="form.user_id">{{ seguimiento?.usuario.nombres }} {{ seguimiento?.usuario.apellidos }}</option>
          </select-input> -->
          <text-input v-model="form.peso" label="Peso (kg)*" type="number" step="0.1" class="pb-8 pr-6 w-full"
            :error="form.errors.peso" />

          <text-input v-model="form.altura" label="Altura (cm)*" type="number" step="0.1" class="pb-8 pr-6 w-full"
            :error="form.errors.altura" />

          <text-input v-model="form.fecha_registro" label="Fecha*" type="date" class="pb-8 pr-6 w-full"
            :error="form.errors.fecha_registro" />

          <text-input v-model="form.observaciones" label="Observaciones" type="text" class="pb-8 pr-6 w-full"
            :error="form.errors.observaciones" />

        </div>

        <div class="flex items-center px-8 py-4 border-t">
          <button v-if="canAny" type="button" class="text-red-600" @click="destroy">Eliminar Seguimiento</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto">Guardar</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import { useCan } from '@/Composables/useCan'
import { computed } from 'vue'

export default {
  layout: Layout,
  components: { Head, Link, TextInput, LoadingButton, SelectInput },
  props: { seguimiento: Object, rutinaUsuario: Object },
  data() {
    return {
      form: this.$inertia.form({
        user_id: this.seguimiento.user_id,
        peso: this.seguimiento.peso,
        altura: this.seguimiento.altura,
        fecha_registro: this.seguimiento.fecha_registro,
        observaciones: this.seguimiento.observaciones,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos/${this.seguimiento.id}`)
    },
    destroy() {
      if (confirm('¿Eliminar seguimiento?'))
        this.$inertia.delete(`/inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos/${this.seguimiento.id}`)
    },
  },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('seguimiento rutina eliminar')
    )

    return {
      can,
      canAny,
    }
  },
}
</script>
