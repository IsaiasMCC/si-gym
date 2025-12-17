<template>
  <div class="w-full" :style="{ color: 'var(--color-text)' }">

    <Head title="Permisos del Rol" />

    <h1 class="mb-8 text-3xl font-bold" :style="{ color: 'var(--color-text)' }">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/roles">Roles</Link>
      <span class="text-indigo-400 font-medium"> / </span>
      {{ form.name }}
    </h1>

    <div class="w-full rounded-md shadow overflow-hidden p-6 transition-colors"
         :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">

      <!-- Mensajes Flash -->
      <div v-if="$page.props.flash.success" class="mb-4 px-4 py-2 rounded"
           :style="{ backgroundColor: '#d1fae5', color: '#065f46' }">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.flash.error" class="mb-4 px-4 py-2 rounded"
           :style="{ backgroundColor: '#fee2e2', color: '#991b1b' }">
        {{ $page.props.flash.error }}
      </div>

      <!-- Nombre del rol -->
      <div class="mb-6">
        <label class="block font-semibold mb-2" :style="{ color: 'var(--color-text)' }">Nombre del Rol</label>
        <p class="px-4 py-2 rounded w-full" :style="{ backgroundColor: 'var(--color-bg)', color: 'var(--color-text)' }">{{ form.name }}</p>
      </div>

      <!-- Checkbox "Seleccionar todos" -->
      <div class="form-check mb-4">
        <input type="checkbox" id="checkAll" class="form-check-input mr-2" v-model="selectAll" @change="toggleAll" />
        <label for="checkAll" class="form-check-label cursor-pointer">{{ 'Seleccionar todos' }}</label>
      </div>

      <!-- Lista de permisos -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6 w-full">
        <div v-for="permission in permissions" :key="permission.id">
          <div class="form-check mb-2">
            <input 
              class="form-check-input mr-2" 
              type="checkbox" 
              :value="permission.name"
              v-model="selectedPermissions" 
              :id="'perm-' + permission.id" 
            />
            <label 
              :for="'perm-' + permission.id" 
              class="form-check-label truncate cursor-pointer"
            >
              {{ permission.name }}
            </label>
          </div>
        </div>
      </div>

      <!-- Botones -->
      <div class="flex items-center p-4 border-t border-gray-100">
        <Link 
          href="/roles" 
          class="px-4 py-2 rounded hover:opacity-90"
          :style="{ backgroundColor: '#ef4444', color: 'white' }"
        >
          Volver
        </Link>

        <button
          @click="guardarPermisos"
          class="ml-auto px-4 py-2 rounded hover:opacity-90"
          :style="{ backgroundColor: '#2563eb', color: 'white' }"
        >
          Asignar Permisos
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: { Head, Link },
  props: { role: Object, permissions: Array },
  layout: Layout,
  data() {
    return {
      form: { name: this.role.name },
      selectedPermissions: this.role.permissions ? this.role.permissions.map(p => p.name) : [],
      selectAll: false,
    }
  },
  watch: {
    selectedPermissions: {
      handler(val) {
        this.selectAll = val.length === this.permissions.length
      },
      immediate: true,
    },
  },
  methods: {
    toggleAll() {
      this.selectedPermissions = this.selectAll
        ? this.permissions.map(p => p.name)
        : []
    },
    guardarPermisos() {
      this.$inertia.patch(`/roles/permisos/${this.role.id}`, {
        permissions: this.selectedPermissions,
      })
    },
  },
}
</script>

<style scoped>
.form-check-input {
  margin-right: 0.5rem;
}
</style>
