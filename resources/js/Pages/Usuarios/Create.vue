<template>
  <div :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }" class="min-h-screen p-6">
    <Head title="Crear Usuario" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/inf513/grupo18sc/proyecto2/sis-gym/public/usuarios">Usuarios</Link>
      <span class="text-indigo-400 font-medium">/</span> Crear
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden" :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.ci" :error="form.errors.ci" class="pb-8 pr-6 w-full lg:w-1/2" label="CI*" />
          <text-input v-model="form.nombres" :error="form.errors.nombres" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombres*" />
          <text-input v-model="form.apellidos" :error="form.errors.apellidos" class="pb-8 pr-6 w-full lg:w-1/2" label="Apellidos*" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email*" />
          <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" label="Contraseña*" type="password" />
          <select-input v-model="form.role_id" :error="form.errors.role_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Rol*">
            <option disabled selected :value="null">Seleccione un rol</option>
            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
          </select-input>
          <select-input v-model="form.estado" :error="form.errors.estado" class="pb-8 pr-6 w-full lg:w-1/2" label="Estado*">
            <option :value=true selected>Activo</option>
            <option :value=false>Inactivo</option>
          </select-input>
        </div>

        <div class="flex items-center justify-end px-8 py-4 border-t"
             :style="{ backgroundColor: 'var(--color-card-bg)', borderColor: 'var(--color-border)' }">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Crear Usuario</loading-button>
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

export default {
  components: { Head, Link, TextInput, SelectInput, LoadingButton },
  layout: Layout,
  props: { roles: Array },
  data() {
    return {
      form: this.$inertia.form({
        ci: '',
        nombres: '',
        apellidos: '',
        email: '',
        password: '',
        role_id: null,
        estado: true
      }),
    }
  },
  methods: {
    store() { this.form.post('/inf513/grupo18sc/proyecto2/sis-gym/public/usuarios') },
  },
}
</script>
