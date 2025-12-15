<template>
  <div :style="{ color: 'var(--color-text)' }">
    <Head title="Reporte de Servicios" />
    <h1 class="mb-8 text-3xl font-bold">Reporte de Servicios</h1>

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
            <th class="px-6 py-4">Servicio</th>
            <th class="px-6 py-4">Detalles</th>
            <th class="px-6 py-4">Cliente</th>
            <th class="px-6 py-4">Trabajador</th>
            <th class="px-6 py-4">Fecha</th>
            <th class="px-6 py-4">Precio</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ps in servicios.data" :key="ps.id">
            <td class="px-6 py-4">{{ ps.id }}</td>
            <td class="px-6 py-4">{{ ps.servicio.nombre }}</td>
            <td class="px-6 py-4">{{ ps.detalles }}</td>
            <td class="px-6 py-4">{{ ps.reserva.cliente.nombres }} {{ ps.reserva.cliente.apellidos }}</td>
            <td class="px-6 py-4">{{ ps.trabajador ? ps.trabajador.name : 'Sin asignar' }}</td>
            <td class="px-6 py-4">{{ ps.reserva.fecha }}</td>
            <td class="px-6 py-4">Bs {{ ps.precio }}</td>
          </tr>
          <tr v-if="servicios.data.length === 0">
            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay registros</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination :links="servicios.links" class="mt-6" />
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
    servicios: Object,
    filters: Object,
  },
  layout: Layout,
  components: { Head, Pagination, SearchFilter },
  data() {
    return {
      form: {
        search: this.filters.search || null,
        fecha: this.filters.fecha || null,
      }
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function() {
        this.$inertia.get('/inf513/grupo18sc/proyecto2/sis-gym/public/servicios/reportes', pickBy(this.form), { preserveState: true })
      }, 150),
    }
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    exportar(tipo) {
      const query = pickBy(this.form)
      window.open(`/inf513/grupo18sc/proyecto2/sis-gym/public/servicios/reportes/export?tipo=${tipo}&${new URLSearchParams(query)}`, '_blank')
    }
  }
}
</script>