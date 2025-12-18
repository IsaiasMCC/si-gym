<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">

    <Head title="Membresías" />
    <h1 class="mb-8 text-3xl font-bold">Membresías</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mb-1">Buscar:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" placeholder="Buscar membresías..." />
      </search-filter>

      <Link v-if="can('membresias agregar')" class="btn-indigo" :href="route('membresias.create')">
      <span>Crear</span>
      <span class="hidden md:inline">&nbsp;Membresía</span>
      </Link>
    </div>

    <div class="rounded-md shadow overflow-x-auto"
      :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Nombre</th>
            <th class="pb-4 pt-6 px-6">Duración (días)</th>
            <th class="pb-4 pt-6 px-6">Precio</th>
            <th class="pb-4 pt-6 px-6">Descripción</th>
            <th class="pb-4 pt-6 px-6">Estado</th>
            <th v-if="canAny" class="pb-4 pt-6 px-6"> Accion</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in membresias.data" :key="m.id" class="border-t transition-colors duration-300"
            @mouseover="hover = m.id" @mouseleave="hover = null"
            :class="hover === m.id ? 'bg-[var(--color-hover)]' : ''">
            <td class="px-6 py-4">
              <Link :href="route('membresias.edit', m.id)">{{ m.nombre }}</Link>
            </td>
            <td class="px-6 py-4">{{ m.duracion_dias }}</td>
            <td class="px-6 py-4">{{ m.precio_base }}</td>
            <td class="px-6 py-4">{{ m.descripcion }}</td>
            <td class="px-6 py-4">{{ m.estado }}</td>
            <td class="px-4 py-4 w-px" v-if="canAny">
              <Link :href="route('membresias.edit', m.id)">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="membresias.data.length === 0">
            <td colspan="6" class="px-6 py-4 border-t text-center">No se encontraron membresías.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="membresias.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues'
import throttle from 'lodash/throttle'
import { useCan } from '@/Composables/useCan'
import { computed } from 'vue'

export default {
  components: { Head, Link, Pagination, SearchFilter, Icon },
  layout: Layout,
  props: { filters: Object, membresias: Object },
  data() {
    return { form: { search: this.filters.search }, hover: null, route }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/membresias', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: { reset() { this.form = mapValues(this.form, () => null) } },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('membresias editar')
    )

    return {
      can,
      canAny,
    }
  },
}
</script>
