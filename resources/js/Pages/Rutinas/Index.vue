<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">

    <Head title="Rutinas" />
    <h1 class="mb-8 text-3xl font-bold">Rutinas</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mb-1">Buscar:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" placeholder="Buscar rutinas..." />
      </search-filter>

      <Link v-if="can('rutinas agregar')" class="btn-indigo" :href="route('rutinas.create')">Crear Rutina</Link>
    </div>

    <div class="rounded-md shadow overflow-x-auto"
      :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Nombre</th>
            <th class="pb-4 pt-6 px-6">Nivel</th>
            <th class="pb-4 pt-6 px-6">Objetivo</th>
            <th class="pb-4 pt-6 px-6">Entrenador</th>
            <th class="pb-4 pt-6 px-6" v-if="canAny"> Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in rutinas.data" :key="r.id" class="border-t transition-colors duration-300"
            @mouseover="hover = r.id" @mouseleave="hover = null"
            :class="hover === r.id ? 'bg-[var(--color-hover)]' : ''">
            <td class="px-6 py-4">
              <Link :href="`/rutinas/${r.id}/edit`">{{ r.nombre }}</Link>
            </td>
            <td class="px-6 py-4">{{ r.nivel }}</td>
            <td class="px-6 py-4">{{ r.objetivo }}</td>
            <td class="px-6 py-4">{{ r.entrenador ? r.entrenador.nombres + ' ' + r.entrenador.apellidos : '-' }}</td>
            <td class="px-4 py-4 w-px" v-if="canAny">
              <Link :href="`/rutinas/${r.id}/edit`">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="rutinas.data.length === 0">
            <td colspan="5" class="px-6 py-4 border-t text-center">No se encontraron rutinas.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="rutinas.links" />
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
  props: { filters: Object, rutinas: Object },
  data() { return { form: { search: this.filters.search }, hover: null, route } },
  watch: {
    form: { deep: true, handler: throttle(function () { this.$inertia.get(route('rutinas.index'), pickBy(this.form), { preserveState: true }) }, 150) }
  },
  methods: { reset() { this.form = mapValues(this.form, () => null) } },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('rutinas editar')
    )

    return {
      can,
      canAny,
    }
  },
}
</script>
