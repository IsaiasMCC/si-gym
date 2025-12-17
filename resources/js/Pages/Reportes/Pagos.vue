<template>
  <div :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }" class="min-h-screen p-6">
    <Head title="Reporte de Pagos" />
    <h1 class="mb-8 text-3xl font-bold">Reporte de Pagos</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label>Buscar por usuario o membresía:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" placeholder="Nombre, Apellido o Membresía" />
      </search-filter>

      <div class="flex items-center space-x-2">
        <label>Filtrar por fecha:</label>
        <input v-model="form.fecha" type="date" class="form-input mt-1" />
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
            <th class="px-6 py-4">Membresía</th>
            <th class="px-6 py-4">Usuario</th>
            <th class="px-6 py-4">Tipo Pago</th>
            <th class="px-6 py-4">Metodo Pago</th>
            <th class="px-6 py-4">Monto (Bs)</th>
            <th class="px-6 py-4">Saldo (Bs)</th>
            <th class="px-6 py-4">Estado</th>
            <th class="px-6 py-4">Fecha Limite</th>
            <th class="px-6 py-4">Fecha Pago</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="pago in pagos.data" :key="pago.id" class="border-t transition-colors duration-300">
            <td class="px-6 py-4">{{ pago.id }}</td>
            <td class="px-6 py-4">{{ pago.plan_pago.subscripcion.membresia.nombre }}</td>
            <td class="px-6 py-4">{{ pago.plan_pago.subscripcion.usuario.nombres }} {{ pago.plan_pago.subscripcion.usuario.apellidos }}</td>
            <td class="px-6 py-4">{{ pago.plan_pago.tipo_pago }}</td>
            <td class="px-6 py-4">{{ pago.metodo_pago }}</td>
            <td class="px-6 py-4">{{ pago.monto }}</td>
            <td class="px-6 py-4">{{ pago.plan_pago.saldo }}</td>
            <td class="px-6 py-4">{{ pago.estado }}</td>
            <td class="px-6 py-4">{{ pago.plan_pago.fecha_vencimiento }}</td>
            <td class="px-6 py-4">{{ pago.fecha_pago }}</td>
          </tr>
          <tr v-if="pagos.data.length === 0">
            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No hay registros</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination :links="pagos.links" class="mt-6" />
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
    pagos: Object,
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
        this.$inertia.get('/inf513/grupo18sc/proyecto2/sis-gym/public/pagos-reportes/reportes/', pickBy(this.form), { preserveState: true })
      }, 150),
    }
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    exportar(tipo) {
      const query = pickBy(this.form)
      window.open(`/inf513/grupo18sc/proyecto2/sis-gym/public/pagos-reportes/reportes/export?tipo=${tipo}&${new URLSearchParams(query)}`, '_blank')
    }
  }
}
</script>
