<template>
  <div :class="themeClass" class="min-h-screen flex flex-col font-sans transition-colors duration-300">
    <!-- Header -->
    <header class="shadow-md" :class="headerClass">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <h1 class="text-2xl font-bold text-indigo-500 tracking-wide">
          PowerGym
        </h1>

        <!-- Nav -->
        <nav class="flex items-center space-x-6">
          <Link :href="route('subscripciones.index')"
            class="hover:text-indigo-300 transition font-medium">Inicio</Link>
          <Link :href="route('subscripciones.catalogo')"
            class="hover:text-indigo-300 transition font-medium">Membresias</Link>
          <a href="#clases" class="hover:text-indigo-300 transition font-medium">Paquetes</a>

          <!-- Separador -->
          <span class="h-6 w-px bg-indigo-400/50"></span>

          <!-- Usuario -->
          <div v-if="auth?.user" class="flex items-center space-x-3">
            <!-- Avatar -->
            <!-- <div
              class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center font-bold text-white shadow-md">
              {{ authUserInitials }}
            </div> -->

            <!-- Nombre -->
            <span class="text-sm font-medium text-gray-100">
              {{ auth.user.name }} {{ auth.user.lastname }}
            </span>

            <!-- Botón auth -->
            <Link :href="route('logout')" method="delete" as="button"
              class="bg-red-500 px-3 py-1 rounded text-sm hover:bg-red-400 transition text-white shadow-sm">
            Cerrar sesión
            </Link>
          </div>

          <!-- Si no está logueado -->
          <div v-else>
            <Link :href="route('login')"
              class="bg-indigo-500 px-3 py-1 rounded text-sm hover:bg-indigo-400 transition text-white shadow-sm">
              Iniciar sesión
            </Link>
          </div>
        </nav>
      </div>
    </header>

    <!-- Hero -->
    <section class="py-20 px-6 text-center text-white bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-500">
      <h2 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
        Transforma tu cuerpo hoy
      </h2>
      <p class="text-lg md:text-xl mb-6 drop-shadow-sm">
        Únete a PowerGym y alcanza tus objetivos de fitness con los mejores entrenadores.
      </p>
      <button
        class="bg-yellow-400 text-gray-900 font-semibold px-6 py-3 rounded-lg shadow-lg hover:bg-yellow-300 transition">
        Únete Ahora
      </button>
    </section>

    <!-- Contenido dinámico -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-12">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center bg-indigo-700 text-indigo-100">
      <span class="text-sm md:text-base">© 2025 PowerGym. Todos los derechos reservados.</span>
    </footer>
  </div>
</template>

<script>
import { Link, usePage  } from '@inertiajs/vue3';
import { computed } from 'vue';

export default {
  name: 'GymLayout',
  props: {
    auth: Object,
    theme: {
      type: String,
      default: 'light'
    }
  },
  components: { Link },
  setup(props) {
     const { auth } = usePage().props
    const themeClass = computed(() => 'bg-indigo-900 text-gray-100');
    const headerClass = computed(() => 'bg-indigo-800 text-gray-100');
    const footerClass = computed(() => 'bg-indigo-700 text-indigo-100');

    // solo devolvemos todo el prop auth
    return { themeClass, headerClass, footerClass, auth: auth };
  },
  data() {
    return {
      route,
    };
  },

}
</script>

<style scoped>
html,
body,
#app {
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
