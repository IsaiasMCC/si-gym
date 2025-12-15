<template>
  <GymLayout>
    <section class="max-w-3xl mx-auto bg-gray-900 rounded-2xl p-8 shadow-lg">
      <h2 class="text-3xl font-bold text-center mb-6">Resumen de tu Suscripción</h2>

      <!-- Membresía -->
      <div class="mb-6 p-4 bg-gray-800 rounded-lg">
        <h3 class="text-xl font-semibold mb-2">{{ membresia.nombre }}</h3>
        <p class="text-gray-400 mb-1">{{ membresia.descripcion }}</p>
        <p class="text-indigo-400 font-bold text-lg">Bs {{ membresia.precio_base }}</p>
      </div>

      <!-- Paquetes seleccionados -->
      <div v-if="paquetes.length" class="mb-6">
        <h3 class="text-xl font-semibold mb-3">Paquetes adicionales</h3>
        <ul class="space-y-3">
          <li
            v-for="p in paquetes"
            :key="p.id"
            class="flex justify-between items-center p-3 bg-gray-800 rounded-lg"
          >
            <span>{{ p.nombre }} - <small class="text-gray-400">{{ p.descripcion }}</small></span>
            <span class="text-indigo-400 font-bold">+ Bs {{ p.precio_adicional }}</span>
          </li>
        </ul>
      </div>

      <!-- Plan de pago -->
      <div class="mb-6 p-4 bg-gray-800 rounded-lg">
        <h3 class="text-xl font-semibold mb-3">Selecciona un plan de pago</h3>
        <div class="flex gap-6">
          <label class="flex items-center gap-2">
            <input type="radio" value="contado" v-model="tipo_pago" />
            Contado (pago único)
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" value="credito" v-model="tipo_pago" />
            Crédito (varias cuotas)
          </label>
        </div>
      </div>

      <!-- Total -->
      <div class="flex justify-between items-center bg-gray-800 p-4 rounded-lg mb-6">
        <span class="font-semibold text-lg">Total a pagar:</span>
        <span class="text-2xl font-extrabold text-yellow-400">Bs {{ total }}</span>
      </div>

      <!-- Confirmar Pago -->
      <div class="text-center">
        <button
          @click="confirmarPago"
          class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-xl font-semibold hover:bg-yellow-300 transition"
          :disabled="!tipo_pago"
        >
          Confirmar y Pagar
        </button>
      </div>
    </section>
  </GymLayout>
</template>

<script>
import GymLayout from '@/Shared/GymLayout.vue'

export default {
  components: { GymLayout },
  props: {
    membresia: Object,
    paquetes: Array,
    total: Number
  },
  data() {
    return {
      tipo_pago: null // Contado o crédito
    }
  },
  methods: {
    confirmarPago() {
      if (!this.tipo_pago) return

      const paqueteIds = this.paquetes.map(p => p.id)
      this.$inertia.post(`/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones/${this.membresia.id}/confirmar`, {
        paquetes: paqueteIds,
        tipo_pago: this.tipo_pago
      })
    }
  }
}
</script>
