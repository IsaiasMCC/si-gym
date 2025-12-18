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
    <!--        SUBSCRIPCIONES         -->
    <!-- ======================= -->
    <div v-if="subscripciones.length" class="mb-10">
      <h2 class="text-xl font-semibold mb-3">Subscripciones</h2>

      <div class="rounded-md shadow overflow-x-auto transition-colors"
           :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">

        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="pb-4 pt-6 px-6">ID</th>
              <th class="pb-4 pt-6 px-6">Membresia</th>
              <th class="pb-4 pt-6 px-6">Cliente</th>
              <th class="pb-4 pt-6 px-6">Fecha</th>
              <th class="pb-4 pt-6 px-6">Fecha Fin</th>
              <th class="pb-4 pt-6 px-6">Estado</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="s in subscripciones" :key="s.id" class="hover:bg-gray-200 focus-within:bg-gray-200">
              <td class="border-t px-6 py-4">{{ s.id }}</td>
              <td class="border-t px-6 py-4">{{ s.membresia.nombre }}</td>
              <td class="border-t px-6 py-4">{{ s.usuario.nombres }} {{  s.usuario.apellidos }}</td>
              <td class="border-t px-6 py-4">{{ s.fecha_inicio }}</td>
              <td class="border-t px-6 py-4">{{ s.fecha_fin }}</td>
              <td class="border-t px-6 py-4">{{ s.estado }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ======================= -->
    <!--         PLANES PAGO          -->
    <!-- ======================= -->
    <div v-if="planes.length" class="mb-10">
      <h2 class="text-xl font-semibold mb-3">Planes de Pago</h2>

      <div class="rounded-md shadow overflow-x-auto transition-colors"
           :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">

        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">

              <th class="pb-4 pt-6 px-6">Id</th>
              <th class="pb-4 pt-6 px-6">Membresia</th>
              <th class="pb-4 pt-6 px-6">cliente</th>
              <th class="pb-4 pt-6 px-6">Fecha Creada</th>
              <th class="pb-4 pt-6 px-6">Fecha Vencimiento</th>
              <th class="pb-4 pt-6 px-6"> Monto</th>
              <th class="pb-4 pt-6 px-6"> Saldo</th>
              <th class="pb-4 pt-6 px-6"> Estado</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="v in planes" :key="v.id" class="hover:bg-gray-200 focus-within:bg-gray-200">
              <td class="border-t px-6 py-4"> {{ v.id }}</td>
              <td class="border-t px-6 py-4"> {{ v.subscripcion.membresia.nombre }}</td>
              <td class="border-t px-6 py-4"> {{ v.subscripcion.usuario.nombres }} {{ v.subscripcion.usuario.apellidos }}</td>
              <td class="border-t px-6 py-4"> {{ v.fecha }}</td>
              <td class="border-t px-6 py-4"> {{ v.fecha_vencimiento }}</td>
              <td class="border-t px-6 py-4">Bs {{ v.monto }}</td>
              <td class="border-t px-6 py-4">Bs {{ v.saldo }}</td>
              <td class="border-t px-6 py-4"> {{ v.estado }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ======================= -->
    <!--   SIN RESULTADOS        -->
    <!-- ======================= -->
    <div v-if="!users.length && !subscripciones.length && !planes.length" class="text-gray-500">
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
    subscripciones: Array,
    planes: Array,
  },
}
</script>
