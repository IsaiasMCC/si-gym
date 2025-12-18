<template>
  <div :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }" class="min-h-screen p-6">

    <Head :title="`${form.nombres} ${form.apellidos}`" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">
      <Link class="text-indigo-400 hover:text-indigo-600" :href="route('usuarios.index')">
      Usuarios</Link>
      <span class="text-indigo-400 font-medium">/</span> Editar
      {{ form.nombres }} {{ form.apellidos }}
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden"
      :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.ci" :error="form.errors.ci" class="pb-8 pr-6 w-full lg:w-1/2" label="CI*" />
          <text-input v-model="form.nombres" :error="form.errors.nombres" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Nombres*" />
          <text-input v-model="form.apellidos" :error="form.errors.apellidos" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Apellidos*" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Email*" />
          <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Contraseña (opcional)" type="password" />
          <select-input v-model="form.role_id" :error="form.errors.role_id" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Rol*">
            <option :value="null" />
            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
          </select-input>
          <select-input v-model="form.estado" :error="form.errors.estado" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Estado*">
            <option :value="true">Activo</option>
            <option :value="false">Inactivo</option>
          </select-input>
        </div>

        <div class="flex items-center px-8 py-4 border-t"
          :style="{ backgroundColor: 'var(--color-card-bg)', borderColor: 'var(--color-border)' }">
          <button v-if="canAny" class="text-red-600 hover:underline" type="button" @click="destroy">Eliminar Usuario</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Actualizar
            Usuario</loading-button>
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
  components: { Head, Link, TextInput, SelectInput, LoadingButton },
  layout: Layout,
  props: { user: Object, roles: Array },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        ci: this.user.ci,
        nombres: this.user.nombres,
        apellidos: this.user.apellidos,
        email: this.user.email,
        password: '',
        role_id: this.user.role_id,
        estado: this.user.estado,
      }),
      route,
    }
  },
  methods: {
    update() { this.form.put(`${route('usuarios.update', this.user.id)}`) },
    destroy() { if (confirm('¿Seguro que quieres eliminar este usuario?')) this.$inertia.delete(`${route('usuarios.destroy', this.user.id)}`) },
  },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('usuarios eliminar')
    )

    return {
      can,
      canAny,
    }
  },
}
</script>
