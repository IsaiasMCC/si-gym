<template>
  <div>
    <Head title="Reporte de Seguimientos" />
    <h1 class="mb-8 text-3xl font-bold">Reporte de Seguimientos</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label>Buscar por cliente:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" />
      </search-filter>

      <search-filter v-model="form.fecha" class="mr-4 w-full max-w-md">
        <label>Filtrar por fecha:</label>
        <input v-model="form.fecha" type="date" class="form-input mt-1 w-full" />
      </search-filter>

      <div class="space-x-2">
        <button @click="exportar('pdf')" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">PDF</button>
        <button @click="exportar('excel')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">Excel</button>
      </div>
    </div>

    <div class="overflow-x-auto rounded shadow">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="px-6 py-4">ID</th>
            <th class="px-6 py-4">Cliente</th>
            <th class="px-6 py-4">CI</th>
            <th class="px-6 py-4">Peso</th>
            <th class="px-6 py-4">Altura</th>
            <th class="px-6 py-4">IMC</th>
            <th class="px-6 py-4">% Grasa</th>
            <th class="px-6 py-4">Observaciones</th>
            <th class="px-6 py-4">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="s in seguimientos.data" :key="s.id">
            <td class="px-6 py-4">{{ s.id }}</td>
            <td class="px-6 py-4">{{ s.usuario.nombres }} {{ s.usuario.apellidos }}</td>
            <td class="px-6 py-4">{{ s.usuario.ci }}</td>
            <td class="px-6 py-4">{{ s.peso }}</td>
            <td class="px-6 py-4">{{ s.altura }}</td>
            <td class="px-6 py-4">{{ s.imc }}</td>
            <td class="px-6 py-4">{{ s.porcentaje_grasa }}</td>
            <td class="px-6 py-4">{{ s.observaciones }}</td>
            <td class="px-6 py-4">{{ s.fecha_registro }}</td>
          </tr>
          <tr v-if="seguimientos.data.length === 0">
            <td colspan="9" class="px-6 py-4 text-center text-gray-500">No hay registros</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination :links="seguimientos.links" class="mt-6" />
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Layout from '@/Shared/Layout.vue'

export default {
  layout: Layout,
  props: { seguimientos: Object, filters: Object },
  components: { Head, Pagination, SearchFilter },
  data() {
    return {
      form: {
        search: this.filters.search || null,
        fecha: this.filters.fecha || null
      }
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function() {
        this.$inertia.get('/seguimientos-reportes/reportes', pickBy(this.form), { preserveState: true })
      }, 150)
    }
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    exportar(tipo) {
      const query = pickBy(this.form)
      window.open(`/seguimientos-reportes/reportes/export?tipo=${tipo}&${new URLSearchParams(query)}`, '_blank')
    }
  }
}
</script>
