<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">

    <Head title="Usuarios" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">Usuarios</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mb-1" :style="{ color: 'var(--color-text)' }">Buscar:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" placeholder="Buscar usuarios..." />
      </search-filter>

      <Link class="btn-indigo" v-if="can('usuarios agregar')" href="/usuarios/create">
      <span>Crear</span>
      <span class="hidden md:inline">&nbsp;Usuario</span>
      </Link>
    </div>

    <div class="rounded-md shadow overflow-x-auto"
      :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold" :style="{ color: 'var(--color-text)' }">
            <th class="pb-4 pt-6 px-6">CI</th>
            <th class="pb-4 pt-6 px-6">Nombres</th>
            <th class="pb-4 pt-6 px-6">Apellidos</th>
            <th class="pb-4 pt-6 px-6">Email</th>
            <th class="pb-4 pt-6 px-6">Rol</th>
            <th class="pb-4 pt-6 px-6">Estado</th>
            <th class="pb-4 pt-6 px-6"> Acciones</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="user in users.data" :key="user.id" class="border-t transition-colors duration-300"
            :style="{ backgroundColor: 'var(--color-card-bg)' }" @mouseover="hover = user.id" @mouseleave="hover = null"
            :class="hover === user.id ? 'bg-[var(--color-hover)]' : ''">
            <td class="px-6 py-4" >
              <Link  :href="`/usuarios/${user.id}/edit`"
                :style="{ color: 'var(--color-text)' }">{{ user.ci }}</Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/usuarios/${user.id}/edit`"
                :style="{ color: 'var(--color-text)' }">{{ user.nombres }}</Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/usuarios/${user.id}/edit`"
                :style="{ color: 'var(--color-text)' }">{{ user.apellidos }}</Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/usuarios/${user.id}/edit`"
                :style="{ color: 'var(--color-text)' }">{{ user.email }}</Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/usuarios/${user.id}/edit`"
                :style="{ color: 'var(--color-text)' }">{{ user.role?.name ?? '-' }}</Link>
            </td>
            <td class="px-6 py-4">
              <Link :href="`/usuarios/${user.id}/edit`"
                :style="{ color: 'var(--color-text)' }">{{ user.estado ? 'Activo' : 'Inactivo' }}</Link>
            </td>
            <td class="px-4 py-4 w-px" v-if="canAny">
              <Link :href="`/usuarios/${user.id}/edit`">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>

          <tr v-if="users.data.length === 0">
            <td class="px-6 py-4 border-t text-center" :style="{ color: 'var(--color-text)' }" colspan="7">
              Usuarios no encontrados.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="users.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues'
import Layout from '@/Shared/Layout.vue'
import { useCan } from '@/Composables/useCan'
import { computed } from 'vue'

export default {
  components: { Head, Icon, Link, Pagination, SearchFilter },
  layout: Layout,
  props: { filters: Object, users: Object },
  data() {
    return { form: { search: this.filters.search }, hover: null }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/usuarios', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: { reset() { this.form = mapValues(this.form, () => null) } },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('usuarios editar')
    )

    return {
      can,
      canAny,
    }
  },
}
</script>
