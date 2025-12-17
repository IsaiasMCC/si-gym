<template>
  <div class="w-full text-base transition-colors duration-300">
    <Head title="Servicios" />
    <h1 class="mb-8 text-3xl font-bold" style="color: var(--color-text)">Servicios</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block" style="color: var(--color-text)">Estado:</label>
        <select v-model="form.estado" class="form-select mt-1 w-full">
          <option :value="null">Todos</option>
          <option :value="true">Activo</option>
          <option :value="false">Inactivo</option>
        </select>
      </search-filter>

      <Link class="btn-indigo" href="/servicios/create">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Servicio</span>
      </Link>
    </div>

    <div class="rounded-md shadow overflow-x-auto transition-colors duration-300"
         style="background-color: var(--color-bg); color: var(--color-text)">
      <table class="w-full whitespace-nowrap transition-colors duration-300">
        <thead>
          <tr class="text-left font-bold" style="color: var(--color-text)">
            <th class="pb-4 pt-6 px-6">Nombre</th>
            <th class="pb-4 pt-6 px-6">Detalles</th>
            <th class="pb-4 pt-6 px-6">Precio</th>
            <th class="pb-4 pt-6 px-6">Estado</th>
            <th class="pb-4 pt-6 px-6"></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="servicio in servicios.data" :key="servicio.id"
              class="border-t transition-colors duration-300 hover:bg-[var(--color-hover)]"
          >
            <td class="px-6 py-4">
              <Link :href="`/servicios/${servicio.id}/edit`" class="block w-full" style="color: var(--color-text)">
                {{ servicio.nombre }}
              </Link>
            </td>
            <td class="px-6 py-4" style="color: var(--color-text)">{{ servicio.detalles }}</td>
            <td class="px-6 py-4" style="color: var(--color-text)">{{ servicio.precio }}</td>
            <td class="px-6 py-4" style="color: var(--color-text)">{{ servicio.estado ? 'Activo' : 'Inactivo' }}</td>
            <td class="px-4 py-4 w-px">
              <Link :href="`/servicios/${servicio.id}/edit`">
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>

          <tr v-if="servicios.data.length === 0">
            <td class="px-6 py-4 border-t" style="color: var(--color-text)" colspan="5">No se encontraron servicios.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="servicios.links" />
  </div>
</template>

<script>
import Icon from '@/Shared/Icon.vue'
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    servicios: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        estado: this.filters.estado,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/servicios', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>

<style scoped>
/* Opcional: aquí solo aseguramos transición de color en hover */
tr:hover {
  transition: background-color 0.3s;
}
</style>
