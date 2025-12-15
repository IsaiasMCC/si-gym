<template>
  <div :style="{ color: 'var(--color-text)' }">
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">
      <Link class="hover:underline" :style="{ color: 'var(--color-primary)' }" href="/inf513/grupo18sc/proyecto2/sis-gym/public/roles">Roles</Link>
      <span class="font-medium mx-1">/</span>
      {{ form.name }}
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden transition-colors" :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input 
            v-model="form.name" 
            :error="form.errors.name" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Nombre *" 
          />
          <text-input 
            v-model="form.description" 
            :error="form.errors.description" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Descripción" 
          />
          <select-input 
            v-model="form.estado" 
            :error="form.errors.estado" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Estado *"
          >
            <option :value="null" />
            <option value=true>Activo</option>
            <option value=false>Inactivo</option>
          </select-input>
        </div>

        <div class="flex items-center px-8 py-4 border-t transition-colors" :style="{ backgroundColor: 'var(--color-bg)', borderColor: 'var(--color-text)' }">
          <button 
            v-if="!role.deleted_at" 
            class="text-red-600 hover:underline" 
            tabindex="-1" 
            type="button" 
            @click="destroy"
          >
            Eliminar Rol
          </button>

          <loading-button 
            :loading="form.processing" 
            class="btn-indigo ml-auto" 
            type="submit"
          >
            Actualizar Rol
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

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    role: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.role.name,
        description: this.role.description,
        estado: this.role.estado,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/inf513/grupo18sc/proyecto2/sis-gym/public/roles/${this.role.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this role?')) {
        this.$inertia.delete(`/inf513/grupo18sc/proyecto2/sis-gym/public/roles/${this.role.id}`)
      }
    },
  },
}
</script>
