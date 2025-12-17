<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">

    <Head title="Paquetes" />
    <h1 class="mb-8 text-3xl font-bold">Paquetes</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mb-1">Buscar:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" placeholder="Buscar paquetes..." />
      </search-filter>

      <Link v-if="can('paquetes agregar')" class="btn-indigo" href="/paquetes/create">Crear Paquete</Link>
    </div>

    <div class="rounded-md shadow overflow-x-auto"
      :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Nombre</th>
            <th class="pb-4 pt-6 px-6">Membresía</th>
            <th class="pb-4 pt-6 px-6">Precio Adicional</th>
            <th class="pb-4 pt-6 px-6">Estado</th>
            <th class="pb-4 pt-6 px-6" v-if="canAny"> Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in paquetes.data" :key="p.id" class="border-t transition-colors duration-300"
            @mouseover="hover = p.id" @mouseleave="hover = null"
            :class="hover === p.id ? 'bg-[var(--color-hover)]' : ''">
            <td class="px-6 py-4">
              <Link :href="`/paquetes/${p.id}/edit`">{{ p.nombre }}</Link>
            </td>
            <td class="px-6 py-4">{{ p.membresia?.nombre ?? '-' }}</td>
            <td class="px-6 py-4">{{ p.precio_adicional }}</td>
            <td class="px-6 py-4">{{ p.estado }}</td>
            <td class="px-4 py-4 w-px" v-if="canAny">
              <Link :href="`/paquetes/${p.id}/edit`">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="paquetes.data.length === 0">
            <td colspan="5" class="px-6 py-4 border-t text-center">No se encontraron paquetes.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="paquetes.links" />
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
  props: { filters: Object, paquetes: Object },
  data() { return { form: { search: this.filters.search }, hover: null } },
  watch: {
    form: { deep: true, handler: throttle(function () { this.$inertia.get('/paquetes', pickBy(this.form), { preserveState: true }) }, 150) }
  },
  methods: { reset() { this.form = mapValues(this.form, () => null) } },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('paquetes editar')
    )

    return {
      can,
      canAny,
    }
  },
}
</script>
