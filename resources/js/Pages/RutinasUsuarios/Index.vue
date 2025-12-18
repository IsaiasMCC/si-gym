<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">

    <Head title="Rutinas Asignadas" />

    <div class="flex justify-between mb-6">
      <h1 class="text-3xl font-bold">Rutinas Asignadas</h1>

      <Link v-if="can('rutinas entrenador agregar')" class="btn-indigo" :href="route('rutinas-usuarios.create')">
      Asignar Rutina
      </Link>
    </div>

    <div class="rounded-md shadow overflow-x-auto" :style="{ backgroundColor: 'var(--color-card-bg)' }">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="px-6 py-4">Rutina</th>
            <th class="px-6 py-4">Cliente</th>
            <th class="px-6 py-4">Entrenador</th>
            <th class="px-6 py-4">Fecha</th>
            <th class="px-6 py-4">Estado</th>
            <th class="px-6 py-4 text-center" v-if="canAny">Acción</th>
            <!-- <th class="px-6 py-4"></th> -->
          </tr>
        </thead>

        <tbody>
          <tr v-for="ru in rutinaUsuarios.data" :key="ru.id" class="border-t">
            <td class="px-6 py-4">{{ ru.rutina.nombre }}</td>
            <td class="px-6 py-4">{{ ru.cliente?.nombres }} {{ ru.cliente?.apellidos }}</td>
            <td class="px-6 py-4">{{ ru.rutina.entrenador?.nombres }} {{ ru.rutina.entrenador?.apellidos }}</td>
            <td class="px-6 py-4">{{ ru.fecha_asignacion }}</td>
            <td class="px-6 py-4">{{ ru.estado }}</td>
            <div v-if="canAny">
              <td class="px-6 py-4" v-if="can('rutinas entrenador editar')">
                <Link :href="`${route('rutinas-usuarios.edit', ru.id)}`">
                Editar
                </Link>
              </td>
              <td class="px-6 py-4" v-if="can('seguimiento rutina visualizar')">
                <Link :href="`${route('seguimientos.index', ru.id)}`">
                Ver Seguimiento
                </Link>
              </td>
            </div>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import { useCan } from '@/Composables/useCan'
import { computed } from 'vue'

export default {
  layout: Layout,
  components: { Head, Link },
  props: { rutinaUsuarios: Object },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('rutinas entrenador editar'),
      can('seguimiento rutina visualizar'),
    )

    return {
      can,
      canAny,
    }
  },

  data() {
    return {
      route,
    }
  },
}
</script>
