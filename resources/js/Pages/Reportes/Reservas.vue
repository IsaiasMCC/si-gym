<template>
  <div :style="{ color: 'var(--color-text)' }">
    <Head title="Reporte de Reservas" />
    <h1 class="mb-8 text-3xl font-bold">Reporte de Reservas</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label>Buscar por fecha:</label>
        <input v-model="form.fecha" type="date" class="form-input mt-1 w-full" />
      </search-filter>

      <div class="space-x-2">
        <button @click="exportar('pdf')" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">
          Exportar PDF
        </button>
        <button @click="exportar('excel')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
          Exportar Excel
        </button>
      </div>
    </div>

    <div class="rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="px-6 py-4">ID</th>
            <th class="px-6 py-4">Cliente</th>
            <th class="px-6 py-4">Fecha</th>
            <th class="px-6 py-4">Hora</th>
            <th class="px-6 py-4">Total</th>
            <th class="px-6 py-4">Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in reservas.data" :key="r.id">
            <td class="px-6 py-4">{{ r.id }}</td>
            <td class="px-6 py-4">{{ r.cliente.nombres }} {{ r.cliente.apellidos }}</td>
            <td class="px-6 py-4">{{ r.fecha }}</td>
            <td class="px-6 py-4">{{ r.hora }}</td>
            <td class="px-6 py-4">Bs {{ r.total }}</td>
            <td class="px-6 py-4">{{ r.estado_reserva }}</td>
          </tr>
          <tr v-if="reservas.data.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay registros</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination :links="reservas.links" class="mt-6" />
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'

export default {
  props: {
    reservas: Object,
    filters: Object,
  },
  layout: Layout,
  components: { Head, Pagination, SearchFilter },
  data() {
    return {
      form: {
        fecha: this.filters.fecha || null,
      }
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function() {
        this.$inertia.get('/reservas/reportes', pickBy(this.form), { preserveState: true })
      }, 150),
    }
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    exportar(tipo) {
      const query = pickBy(this.form)
      window.open(`/reservas/reportes/export?tipo=${tipo}&${new URLSearchParams(query)}`, '_blank')
    }
  }
}
</script>
