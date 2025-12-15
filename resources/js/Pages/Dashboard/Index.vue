<template>
  <div class="min-h-screen p-6 space-y-8">
    <Head title="Dashboard" />

    <!-- Encabezado -->
    <div>
      <h1 class="text-4xl font-bold mb-2 text-gray-900 dark:text-gray-100">Dashboard</h1>
      <p class="text-gray-600 dark:text-gray-300">
        Bienvenido al panel de control. Aquí puedes ver un resumen de tu gimnasio.
      </p>
    </div>

    <!-- Estadísticas generales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Miembros -->
      <div class="bg-indigo-600 text-white rounded-lg shadow p-5 flex items-center space-x-4 hover:scale-105 transform transition">
        <div class="p-3 bg-indigo-700 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium opacity-75">Miembros</p>
          <p class="text-2xl font-bold">{{ totalMiembros }}</p>
        </div>
      </div>

      <!-- Suscripciones activas -->
      <div class="bg-green-600 text-white rounded-lg shadow p-5 flex items-center space-x-4 hover:scale-105 transform transition">
        <div class="p-3 bg-green-700 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h2l2 8h6l2-8h2" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium opacity-75">Suscripciones</p>
          <p class="text-2xl font-bold">{{ totalSuscripciones }}</p>
        </div>
      </div>

      <!-- Clases reservadas -->
      <div class="bg-blue-600 text-white rounded-lg shadow p-5 flex items-center space-x-4 hover:scale-105 transform transition">
        <div class="p-3 bg-blue-700 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h6v6" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-medium opacity-75">Clases Reservadas</p>
          <p class="text-2xl font-bold">{{ totalReservas }}</p>
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
          <p class="text-2xl font-bold">{{ totalPendientes }}</p>
        </div>
      </div>
    </div>

    <!-- Últimas reservas -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Últimas Reservas</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead>
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Miembro</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clase</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hora</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="r in reservas" :key="r.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
              <td class="px-6 py-4 whitespace-nowrap">{{ r.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ r.miembro }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ r.clase }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ r.fecha }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ r.hora }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="{
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                  'bg-green-100 text-green-800': r.estado === 'CONFIRMADA',
                  'bg-yellow-100 text-yellow-800': r.estado === 'PENDIENTE',
                  'bg-red-100 text-red-800': r.estado === 'CANCELADA'
                }">
                  {{ r.estado }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Gráfico de reservas -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Reservas por fecha</h2>
      <BarChart :reservas="reservas" />
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
  data() {
    return {
      // Datos adaptados a un gimnasio real
      totalMiembros: 120,
      totalSuscripciones: 85,
      totalReservas: 34,
      totalPendientes: 8,
      reservas: [
        { id: 1, miembro: 'Carlos Pérez', clase: 'Yoga', fecha: '2025-12-14', hora: '10:00', estado: 'CONFIRMADA' },
        { id: 2, miembro: 'María López', clase: 'Pilates', fecha: '2025-12-14', hora: '12:00', estado: 'PENDIENTE' },
        { id: 3, miembro: 'Juan Sánchez', clase: 'Crossfit', fecha: '2025-12-15', hora: '15:00', estado: 'CANCELADA' },
        { id: 4, miembro: 'Ana Torres', clase: 'Spinning', fecha: '2025-12-15', hora: '17:00', estado: 'CONFIRMADA' }
      ]
    }
  }
}
</script>
