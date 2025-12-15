<template>
  <div class="min-h-screen p-6 bg-gray-50">
    <Head title="Nueva Suscripción" />
    <h1 class="text-3xl font-bold mb-6">Elegir Membresía</h1>

    <form @submit.prevent="store" class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
      <div class="mb-4">
        <label class="block mb-2 font-semibold">Membresía*</label>
        <select v-model="form.membresia_id" class="w-full border rounded p-2">
          <option disabled value="">Seleccione una membresía</option>
          <option v-for="m in membresias" :key="m.id" :value="m.id">{{ m.nombre }} - ${{ m.precio_base }}</option>
        </select>
        <p v-if="form.errors.membresia_id" class="text-red-500 mt-1">{{ form.errors.membresia_id }}</p>
      </div>

      <div class="mb-4">
        <label class="block mb-2 font-semibold">Paquete Opcional</label>
        <select v-model="form.paquete_id" class="w-full border rounded p-2">
          <option value="">Ninguno</option>
          <option v-for="p in paquetes" :key="p.id" :value="p.id">{{ p.nombre }} - ${{ p.precio_adicional }}</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="block mb-2 font-semibold">Fecha de inicio*</label>
        <input type="date" v-model="form.fecha_inicio" class="w-full border rounded p-2" />
        <p v-if="form.errors.fecha_inicio" class="text-red-500 mt-1">{{ form.errors.fecha_inicio }}</p>
      </div>

      <button type="submit" class="w-full bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700">
        Continuar al pago
      </button>
    </form>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'

export default {
  props: { membresias: Array, paquetes: Array, user: Object },
  data() {
    return {
      form: this.$inertia.form({
        user_id: '',
        membresia_id: '',
        paquete_id: '',
        fecha_inicio: ''
      })
    }
  },
  methods: {
    store() {
      this.form.user_id = this.user.id
      this.form.post('/subscripciones')
    }
  }
}
</script>
