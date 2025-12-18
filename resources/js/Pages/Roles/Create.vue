<template>
  <div :style="{ color: 'var(--color-text)' }">

    <Head title="Create Role" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">
      <Link class="hover:underline" :style="{ color: 'var(--color-primary)' }"
        :href="route('roles.index')">Roles</Link>
      <span class="font-medium mx-1">/</span> Create
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden transition-colors"
      :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Nombre *" />
          <text-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Descripción" />
          <select-input v-model="form.estado" :error="form.errors.estado" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Estado *">
            <option value=true>Activo</option>
            <option value=false>Inactivo</option>
          </select-input>
        </div>

        <div class="flex items-center justify-end px-8 py-4 border-t transition-colors"
          :style="{ backgroundColor: 'var(--color-bg)', borderColor: 'var(--color-text)' }">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">
            Crear Rol
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import { useCan } from '@/Composables/useCan'
import { computed } from 'vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        description: '',
        estado: true,
      }),
      route,
    }
  },
  methods: {
    store() {
      this.form.post(route('roles.store'))
    },
  },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('roles eliminar')
    )

    return {
      can,
      canAny,
    }
  },
}
</script>
