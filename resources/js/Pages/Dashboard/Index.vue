<template>
  <div class="min-h-screen p-6 space-y-8">

    <Head title="Dashboard" />

    <!-- Encabezado -->
    <div>
      <h1 class="text-4xl font-bold mb-2 text-gray-900 dark:text-gray-100">
        Dashboard
      </h1>
      <p class="text-gray-600 dark:text-gray-300">
        Bienvenido al panel de control. Aquí puedes ver un resumen de tu gimnasio.
      </p>
    </div>

    <!-- Estadísticas generales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Usuarios -->
      <div class="bg-indigo-600 text-white rounded-lg shadow p-5 flex items-center space-x-4 hover:scale-105 transform transition">
        <div class="p-3 bg-indigo-700 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium opacity-75">Usuarios</p>
          <p class="text-2xl font-bold">{{ stats.users }}</p>
        </div>
      </div>

      <!-- Subscripciones -->
      <div class="bg-green-600 text-white rounded-lg shadow p-5 flex items-center space-x-4 hover:scale-105 transform transition">
        <div class="p-3 bg-green-700 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h2l2 8h6l2-8h2" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium opacity-75">Subscripciones</p>
          <p class="text-2xl font-bold">{{ stats.subscripcions }}</p>
        </div>
      </div>

      <!-- Ventas -->
      <div class="bg-blue-600 text-white rounded-lg shadow p-5 flex items-center space-x-4 hover:scale-105 transform transition">
        <div class="p-3 bg-blue-700 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h6v6" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium opacity-75">Ventas</p>
          <p class="text-2xl font-bold">Bs {{ stats.sales }}</p>
        </div>
      </div>

      <!-- Pagos pendientes -->
      <div class="bg-yellow-500 text-white rounded-lg shadow p-5 flex items-center space-x-4 hover:scale-105 transform transition">
        <div class="p-3 bg-yellow-600 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium opacity-75">Pagos Pendientes</p>
          <p class="text-2xl font-bold">{{ stats.pending }}</p>
        </div>
      </div>
    </div>

    <!-- Últimas subscripciones -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">
        Últimas Subscripciones
      </h2>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead>
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Membresía</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Costo</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Inicio</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fin</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="s in subscripcions" :key="s.id">
              <td class="px-6 py-4">{{ s.id }}</td>
              <td class="px-6 py-4">{{ s.membresia.nombre }}</td>
              <td class="px-6 py-4 font-semibold">Bs {{ s.membresia.precio_base }}</td>
              <td class="px-6 py-4">{{ s.fecha_inicio }}</td>
              <td class="px-6 py-4">{{ s.fecha_fin }}</td>
              <td class="px-6 py-4">
                <span :class="{
                  'px-2 inline-flex text-xs font-semibold rounded-full': true,
                  'bg-green-100 text-green-800': s.estado === 'activa',
                  'bg-yellow-100 text-yellow-800': s.estado === 'pendiente',
                  'bg-red-100 text-red-800': s.estado === 'cancelada'
                }">
                  {{ s.estado.toUpperCase() }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 📊 Gráfica de barras -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">
        Subscripciones por Fecha
      </h2>

      <!-- Aquí usamos tu BarChart tal como lo pasaste -->
      <BarChart :subscripcions="subscripcions" />
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import BarChart from '@/Shared/BarChart.vue'

export default {
  components: { Head, BarChart },
  layout: Layout,
  props: {
    stats: Object,
    subscripcions: Array,
  },
}
</script>
