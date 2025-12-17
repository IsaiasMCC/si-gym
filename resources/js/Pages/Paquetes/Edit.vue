<template>
  <div class="min-h-screen p-6" :style="{ color: 'var(--color-text)', backgroundColor: 'var(--color-bg)' }">

    <Head :title="form.nombre" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/paquetes">
      Paquetes</Link>
      <span class="text-indigo-400 font-medium">/</span> Editar
      {{ form.nombre }}
    </h1>

    <div class="max-w-3xl rounded-md shadow overflow-hidden"
      :style="{ backgroundColor: 'var(--color-card-bg)', color: 'var(--color-text)' }">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.nombre" :error="form.errors.nombre" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Nombre*" />
          <text-input v-model="form.descripcion" :error="form.errors.descripcion" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Descripción" />
          <text-input v-model="form.precio_adicional" :error="form.errors.precio_adicional"
            class="pb-8 pr-6 w-full lg:w-1/2" label="Precio Adicional" type="number" />
          <select-input v-model="form.estado" :error="form.errors.estado" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Estado*">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
          </select-input>
          <select-input v-model="form.membresia_id" :error="form.errors.membresia_id" class="pb-8 pr-6 w-full lg:w-1/2"
            label="Membresía*">
            <option disabled value="">Seleccione una membresía</option>
            <option v-for="m in membresias" :key="m.id" :value="m.id">{{ m.nombre }}</option>
          </select-input>
        </div>

        <div class="flex items-center px-8 py-4 border-t">
          <button v-if="canAny" class="text-red-600 hover:underline" type="button" @click="destroy">Eliminar Paquete</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Actualizar
            Paquete</loading-button>
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
  props: { paquete: Object, membresias: Array },
  data() {
    return {
      form: this.$inertia.form({
        nombre: this.paquete.nombre,
        descripcion: this.paquete.descripcion,
        precio_adicional: this.paquete.precio_adicional,
        estado: this.paquete.estado,
        membresia_id: this.paquete.membresia_id
      }),
    }
  },
  methods: {
    update() { this.form.put(`/paquetes/${this.paquete.id}`) },
    destroy() { if (confirm('¿Seguro que quieres eliminar este paquete?')) this.$inertia.delete(`/paquetes/${this.paquete.id}`) },
  },
  setup() {
    const { can } = useCan()

    const canAny = computed(() =>
      can('membresias eliminar')
    )

    return {
      can,
      canAny,
    }
  },
}
</script>
