<template>
    <GymLayout>
        <section class="max-w-7xl mx-auto p-6">
            <h2 class="text-3xl font-bold mb-6 text-center text-white">Mis Subscripciones</h2>

            <div v-if="subscripciones.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="s in subscripciones" :key="s.id"
                    class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 rounded-2xl shadow-lg hover:scale-105 transition transform relative">
                    <!-- Estado -->
                    <span :class="[
                        'absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-semibold',
                        s.estado === 'activa' ? 'bg-green-400 text-gray-900' :
                            s.estado === 'vencida' ? 'bg-red-500 text-white' :
                                'bg-yellow-400 text-gray-900'
                    ]">
                        {{ s.estado.toUpperCase() }}
                    </span>

                    <!-- Membresía -->
                    <h3 class="text-xl font-bold mb-1 text-white">{{ s.membresia.nombre }}</h3>
                    <p class="text-gray-400 mb-3">{{ s.membresia.descripcion }}</p>
                    <p class="text-indigo-400 font-bold mb-3">Bs {{ s.membresia.precio_base }}</p>
                    <p class="text-gray-500 mb-3">Desde: {{ s.fecha_inicio }} - Hasta: {{ s.fecha_fin }}</p>

                    <!-- Paquetes -->
                    <div v-if="s.paquetes && s.paquetes.length" class="mb-3">
                        <h4 class="text-sm font-semibold text-gray-300 mb-1">Paquetes adicionales:</h4>
                        <ul class="space-y-1">
                            <li v-for="p in s.paquetes" :key="p.id" class="text-gray-400 text-sm">
                                {{ p.nombre }} (+ Bs {{ p.precio_adicional }})
                            </li>
                        </ul>
                    </div>

                    <!-- Total -->
                    <p class="text-yellow-400 font-bold text-lg mb-4">
                        Total: Bs {{ calcularTotal(s) }}
                    </p>

                    <!-- Botón ver detalle -->
                    <Link :href="`/mis-subscripciones/${s.id}`" class="w-full bg-indigo-500 py-2 px-3 rounded-xl font-semibold hover:bg-indigo-400 transition">
                        Ver detalle
                    </Link>
                </div>
            </div>

            <p v-else class="text-center text-gray-400 mt-12 text-lg">
                No tienes subscripciones todavía.
            </p>
        </section>
    </GymLayout>
</template>

<script>
import GymLayout from '@/Shared/GymLayout.vue'
import { Link } from '@inertiajs/vue3';
export default {
    components: { GymLayout, Link },
    props: {
        subscripciones: Array // Cada subscripción incluye membresia y paquetes
    },
    methods: {
        calcularTotal(subscripcion) {
            const precioMembresia = Number(subscripcion.membresia.precio_base) || 0

            const totalPaquetes = (subscripcion.paquetes || []).reduce(
                (acc, p) => acc + (Number(p.precio_adicional) || 0),
                0
            )

            return precioMembresia + totalPaquetes
        }

    }
}
</script>
