<template>
    <GymLayout>
        <!-- Info Membresía -->
        <section class="mb-12 text-center">
            <h2 class="text-3xl font-extrabold mb-2">{{ membresia.nombre }}</h2>
            <p class="text-gray-400 mb-4">{{ membresia.descripcion }}</p>

            <div class="flex justify-center gap-6 text-lg mb-6">
                <span class="text-indigo-400 font-semibold">Bs {{ membresia.precio_base }}</span>
                <span class="text-gray-500">{{ membresia.duracion_dias }} días</span>
            </div>

            <p class="text-gray-300 text-lg">
                ¿Quieres mejorar tu experiencia? <strong>Agrega uno o más paquetes opcionales</strong> y obtén
                beneficios extra.
            </p>
        </section>

        <!-- Paquetes -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div v-for="p in paquetes" :key="p.id" :class="[
                'bg-gradient-to-br rounded-2xl p-6 shadow-lg transition transform relative',
                isSelected(p)
                    ? 'from-indigo-700 to-indigo-900 ring-4 ring-yellow-400'
                    : 'from-gray-800 to-gray-900 hover:scale-105 hover:shadow-indigo-500/40'
            ]">
                <!-- Badge recomendado -->
                <div v-if="p.recomendado"
                    class="absolute top-4 right-4 bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-xs font-semibold">
                    Recomendado
                </div>

                <h4 class="text-xl font-bold mb-2">{{ p.nombre }}</h4>
                <p class="text-gray-400 mb-4">{{ p.descripcion }}</p>
                <p class="text-2xl font-extrabold mb-6 text-indigo-400">+ Bs {{ p.precio_adicional }}</p>

                <!-- Botón para seleccionar/deseleccionar -->
                <button @click="togglePaquete(p)" :class="[
                    'w-full py-2 rounded-xl font-semibold transition',
                    isSelected(p)
                        ? 'bg-yellow-400 text-gray-900'
                        : 'bg-indigo-500 text-white hover:bg-indigo-400'
                ]">
                    {{ isSelected(p) ? 'Paquete seleccionado' : 'Agregar paquete' }}
                </button>
            </div>
        </section>

        <!-- Continuar -->
        <div class="text-center mt-12">
            <p class="mb-4 text-gray-400">O continúa solo con tu membresía</p>
            <button @click="continuar()"
                class="bg-indigo-500 text-white px-6 py-2 rounded-xl hover:bg-indigo-400 transition">
                Continuar
            </button>
        </div>
    </GymLayout>
</template>

<script>
import GymLayout from '@/Shared/GymLayout.vue'
// import { Inertia } from '@inertiajs/inertia'

export default {
    components: { GymLayout },
    props: {
        membresia: Object,
        paquetes: Array
    },
    data() {
        return {
            selectedPaquetes: [] // Array para varios paquetes
        }
    },
    methods: {
        // Saber si un paquete está seleccionado
        isSelected(paquete) {
            return this.selectedPaquetes.some(p => p.id === paquete.id)
        },
        // Agregar o quitar del array
        togglePaquete(paquete) {
            const index = this.selectedPaquetes.findIndex(p => p.id === paquete.id)
            if (index >= 0) {
                this.selectedPaquetes.splice(index, 1) // quitar
            } else {
                this.selectedPaquetes.push(paquete) // agregar
            }
        },
        // Continuar al resumen o pago
        continuar() {
            const paqueteIds = this.selectedPaquetes.map(p => p.id)

            this.$inertia.post(`/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones/${this.membresia.id}/resumen`, {
                paquetes: paqueteIds
            })
        }

    }
}
</script>
