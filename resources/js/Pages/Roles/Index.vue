<template>
  <div :style="{ color: 'var(--color-text)' }">

    <Head title="Roles" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">Roles</h1>

    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block" :style="{ color: 'var(--color-text)' }">Buscar:</label>
        <input v-model="form.search" type="text" class="form-input mt-1 w-full" placeholder="Buscar roles..." />
      </search-filter>

      <Link class="btn-indigo" v-if="can('roles agregar')"
        :href="route('roles.create')">
      <span>Crear</span>
      <span class="hidden md:inline">&nbsp;Rol</span>
      </Link>
    </div>

    <div class="rounded-md shadow overflow-x-auto transition-colors"
      :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Nombre</th>
          <th class="pb-4 pt-6 px-6">Descripción</th>
          <th class="pb-4 pt-6 px-6">Estado</th>
          <th class="pb-4 pt-6 px-6">Permisos</th>
          <th v-if="canAny" class="pb-4 pt-6 px-6"> Acciones</th>
        </tr>

        <tr v-for="role in roles.data" :key="role.id" class="hover:bg-gray-200 focus-within:bg-gray-200">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4"
              :href="route('roles.edit', role.id)">
            {{ role.name }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4"
              :href="route('roles.edit', role.id)" tabindex="-1">
            {{ role.description }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4"
              :href="route('roles.edit', role.id)" tabindex="-1">
            <span :class="role.estado == 1 ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
              {{ role.estado == 1 ? 'Activo' : 'Inactivo' }}
            </span>
            </Link>
          </td>

          <td class="border-t">
            <Link v-if="can('roles permisos')" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-400 text-sm"
              :href="route('roles.show', role.id)">
            Asignar Permisos
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4"
              :href="route('roles.edit', role.id)" tabindex="-1">
            <Icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>

        <tr v-if="roles.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="5">Roles no encontrados.</td>
        </tr>
      </table>
    </div>

    <pagination class="mt-6" :links="roles.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import { useCan } from '@/Composables/useCan'


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
    roles: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
      route,
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route('roles.index'), pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },

  },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('roles editar')
    )

    return {
      can,
      canAny,
    }
  },

}
</script>
