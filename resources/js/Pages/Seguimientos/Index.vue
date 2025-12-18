<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">

    <Head title="Seguimientos" />

    <div class="flex justify-between mb-6">
      <h1 class="text-3xl font-bold">Seguimientos</h1>
      <div>
        <Link class="btn-indigo bg-red-500 me-2" :href="route('rutinas-usuarios.index')">Volver</Link>
        <Link v-if="can('seguimiento rutina agregar')" class="btn-indigo" :href="route('seguimientos.create', id)">Nuevo
        Seguimiento</Link>
      </div>
    </div>

    <div class="rounded-md shadow overflow-x-auto" :style="{ backgroundColor: 'var(--color-card-bg)' }">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="px-6 py-4">Fecha</th>
            <th class="px-6 py-4">Peso (kg)</th>
            <th class="px-6 py-4">Altura (cm)</th>
            <th class="px-6 py-4">IMC</th>
            <th class="px-6 py-4">Observaciones</th>
            <th class="px-6 py-4" v-if="canAny"> Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="s in seguimientos.data" :key="s.id" class="border-t">
            <td class="px-6 py-4">{{ s.fecha_registro }}</td>
            <td class="px-6 py-4">{{ s.peso }}</td>
            <td class="px-6 py-4">{{ s.altura }}</td>
            <td class="px-6 py-4">{{ s.imc ?? '-' }}</td>
            <td class="px-6 py-4">{{ s.observaciones ?? '-' }}</td>
            <td class="px-6 py-4" v-if="canAny">
              <Link :href="route('seguimientos.edit', s.id)">Editar</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import { useCan } from '@/Composables/useCan'
import { computed } from 'vue'

export default {
  layout: Layout,
  components: { Head, Link },
  props: { seguimientos: Object, id: Number },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('seguimiento rutina editar')
    )

    return {
      can,
      canAny,
    }
  },
  data() {
    return {
      route,
    }
  }
}
</script>
