<!-- src/Layouts/GymLayout.vue -->
<template>
  <div :class="themeClass" class="min-h-screen flex flex-col font-sans transition-colors duration-300">
    <!-- Header -->
    <header class="shadow" :class="headerClass">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <h1 class="text-2xl font-bold text-indigo-400">
          PowerGym
        </h1>

        <!-- Nav -->
        <nav class="flex items-center space-x-6">
          <Link href="/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones"
                class="hover:text-indigo-400 transition">Inicio</Link>
          <Link href="/inf513/grupo18sc/proyecto2/sis-gym/public/catalogos"
                class="hover:text-indigo-400 transition">Membresias</Link>
          <a href="#clases" class="hover:text-indigo-400 transition">Paquetes</a>

          <!-- Separador -->
          <span class="h-6 w-px bg-gray-600 dark:bg-gray-400"></span>

          <!-- Usuario -->
          <div v-if="authUser" class="flex items-center space-x-3">
            <!-- Avatar -->
            <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-white">
              {{ authUserInitials }}
            </div>

            <!-- Nombre -->
            <span class="text-sm font-medium text-gray-100 dark:text-gray-200">
              {{ authUser.name }} {{ authUser.lastname }}
            </span>

            <!-- Botón auth -->
            <Link href="/inf513/grupo18sc/proyecto2/sis-gym/public/logout" method="delete" as="button"
                  class="bg-red-500 px-3 py-1 rounded text-sm hover:bg-red-400 transition text-white">
              Cerrar sesión
            </Link>
          </div>

          <!-- Si no está logueado -->
          <div v-else>
            <Link href="/login"
                  class="bg-indigo-500 px-3 py-1 rounded text-sm hover:bg-indigo-400 transition text-white">
              Iniciar sesión
            </Link>
          </div>
        </nav>
      </div>
    </header>

    <!-- Hero -->
    <section class="py-20 px-6 text-center text-white bg-indigo-600">
      <h2 class="text-4xl font-bold mb-4">
        Transforma tu cuerpo hoy
      </h2>
      <p class="text-lg mb-6">
        Únete a PowerGym y alcanza tus objetivos de fitness con los mejores entrenadores.
      </p>
      <button class="bg-yellow-400 text-gray-900 font-semibold px-6 py-3 rounded hover:bg-yellow-300 transition">
        Únete Ahora
      </button>
    </section>

    <!-- Contenido dinámico -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-12">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center" :class="footerClass">
      © 2025 PowerGym. Todos los derechos reservados.
    </footer>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

export default {
  name: 'GymLayout',
  props: {
    auth: Object,
    theme: {
      type: String,
      default: 'light' // puede ser light/dark según tu lógica de useTheme()
    }
  },
  components: { Link },
  setup(props) {
    const authUser = computed(() => props.auth?.user ?? null);
    const authUserInitials = computed(() => {
      if (!authUser.value) return '';
      const names = authUser.value.name.split(' ');
      const lastnames = authUser.value.lastname.split(' ');
      return (names[0][0] + (lastnames[0]?.[0] || '')).toUpperCase();
    });

    const themeClass = computed(() => props.theme === 'dark' ? 'bg-gray-900 text-gray-100' : 'bg-gray-100 text-gray-900');
    const headerClass = computed(() => props.theme === 'dark' ? 'bg-gray-800 text-gray-100' : 'bg-gray-50 text-gray-900');
    const footerClass = computed(() => props.theme === 'dark' ? 'bg-gray-800 text-gray-400' : 'bg-gray-100 text-gray-600');

    return { authUser, authUserInitials, themeClass, headerClass, footerClass };
  }
}
</script>

<style scoped>
/* Asegura que footer se quede al final aunque el contenido sea corto */
html, body, #app {
  height: 100%;
}

.min-h-screen {
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}
</style>
