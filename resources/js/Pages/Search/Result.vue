<template>
  <div :style="{ color: 'var(--color-text)' }">
    <Head :title="`Resultados para ${query}`" />

    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">
      Resultados para "{{ query }}"
    </h1>

    <!-- ======================= -->
    <!--       USUARIOS          -->
    <!-- ======================= -->
    <div v-if="users.length" class="mb-10">
      <h2 class="text-xl font-semibold mb-3">Usuarios</h2>

      <div class="rounded-md shadow overflow-x-auto transition-colors"
           :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">

        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="pb-4 pt-6 px-6">Ci</th>
              <th class="pb-4 pt-6 px-6">Nombre</th>
              <th class="pb-4 pt-6 px-6">Apellidos</th>
              <th class="pb-4 pt-6 px-6">Email</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="u in users" :key="u.id" class="hover:bg-gray-200 focus-within:bg-gray-200">
              <td class="border-t px-6 py-4">{{ u.ci }}</td>
              <td class="border-t px-6 py-4">{{ u.nombres }}</td>
              <td class="border-t px-6 py-4">{{ u.apellidos }}</td>
              <td class="border-t px-6 py-4">{{ u.email }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ======================= -->
    <!--        RESERVAS         -->
    <!-- ======================= -->
    <div v-if="reservas.length" class="mb-10">
      <h2 class="text-xl font-semibold mb-3">Reservas</h2>

      <div class="rounded-md shadow overflow-x-auto transition-colors"
           :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">

        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="pb-4 pt-6 px-6">ID</th>
              <th class="pb-4 pt-6 px-6">Fecha</th>
              <th class="pb-4 pt-6 px-6">Hora</th>
              <th class="pb-4 pt-6 px-6">Total</th>
              <th class="pb-4 pt-6 px-6">Estado</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="r in reservas" :key="r.id" class="hover:bg-gray-200 focus-within:bg-gray-200">
              <td class="border-t px-6 py-4">{{ r.id }}</td>
              <td class="border-t px-6 py-4">{{ r.fecha }}</td>
              <td class="border-t px-6 py-4">{{ r.hora }}</td>
              <td class="border-t px-6 py-4">{{ r.total }}</td>
              <td class="border-t px-6 py-4">{{ r.estado_reserva }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ======================= -->
    <!--         VENTAS          -->
    <!-- ======================= -->
    <div v-if="ventas.length" class="mb-10">
      <h2 class="text-xl font-semibold mb-3">Ventas</h2>

      <div class="rounded-md shadow overflow-x-auto transition-colors"
           :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">

        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="pb-4 pt-6 px-6">Total (Bs)</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="v in ventas" :key="v.id" class="hover:bg-gray-200 focus-within:bg-gray-200">
              <td class="border-t px-6 py-4">Bs {{ v.total }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ======================= -->
    <!--   SIN RESULTADOS        -->
    <!-- ======================= -->
    <div v-if="!users.length && !reservas.length && !ventas.length" class="text-gray-500">
      <p>No se encontraron resultados.</p>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  layout: Layout,
  components: { Head },
  props: {
    query: String,
    users: Array,
    reservas: Array,
    ventas: Array,
  },
}
</script>
