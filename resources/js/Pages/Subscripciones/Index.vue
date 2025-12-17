<template>
  <GymLayout>
    <section id="planes" class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div
        v-for="m in membresias"
        :key="m.id"
        class="relative bg-gradient-to-br from-gray-800 to-gray-900
               rounded-2xl shadow-lg p-6
               hover:scale-105 hover:shadow-indigo-500/30
               transition transform"
      >
        <!-- Icono -->
        <div class="w-12 h-12 rounded-full bg-indigo-500 flex items-center justify-center mb-4 shadow">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16M6 10v4M18 10v4M9 9v6M15 9v6" />
          </svg>
        </div>

        <!-- Nombre -->
        <h4 class="text-xl font-bold mb-1">
          {{ m.nombre }}
        </h4>

        <!-- Duración badge -->
        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-400 mb-3">
          {{ m.duracion_dias }} días
        </span>

        <!-- Descripción -->
        <p class="text-gray-400 mb-4">
          {{ m.descripcion }}
        </p>

        <!-- Precio -->
        <div class="mb-5">
          <p class="text-3xl font-extrabold text-white">
            Bs {{ m.precio_base }}
          </p>
          <p class="text-sm text-gray-400">
            pago único
          </p>
        </div>

        <!-- Botón funcional usando Link -->
        <Link
          :href="`/paquetes-adicional/${m.id}`"
          class="w-full bg-indigo-500 py-2 rounded-xl font-semibold hover:bg-indigo-400 transition inline-block text-center"
        >
          Elegir membresía
        </Link>

        <!-- Glow decorativo -->
        <div class="absolute inset-0 rounded-2xl ring-1 ring-white/5 pointer-events-none"></div>
      </div>
    </section>
  </GymLayout>
</template>

<script>
import GymLayout from '@/Shared/GymLayout.vue'
import { Link } from '@inertiajs/vue3'

export default {
  components: { GymLayout, Link },
  props: {
    membresias: Array
  }
}
</script>
