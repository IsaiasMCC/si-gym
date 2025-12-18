<template>
  <div :class="[
    themeState.theme,
    themeState.mode === 'night' ? 'dark' : '',
    themeState.contrast === 'high' ? 'high-contrast' : ''
  ]" class="md:flex md:flex-col min-h-screen">
    <div class="md:flex md:flex-col md:h-screen">
      <!-- Top bar -->
      <div class="md:flex md:shrink-0">
        <div :style="{ backgroundColor: 'var(--color-primary)', color: 'var(--color-text)' }"
          class="flex items-center justify-between px-6 py-4 md:shrink-0 md:justify-center md:w-56">
          <Link class="mt-1" :href="route('dashboard')">
          <Logo class="fill-white" width="120" height="28" />
          </Link>

          <!-- MENU MOVIL -->
          <Dropdown class="md:hidden" placement="bottom-end">
            <template #default>
              <svg class="w-6 h-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
              </svg>
            </template>
            <template #dropdown>
              <div class="mt-2 px-8 py-4 bg-indigo-800 rounded shadow-lg">
                <MainMenu />
              </div>
            </template>
          </Dropdown>
        </div>

        <!-- TOP RIGHT USER MENU -->
        <div class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0">
          <div class="relative w-full md:w-72 mr-4">
            <input v-model="searchQuery" @keyup.enter="search" type="text" placeholder="Buscar..."
              class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
            <button @click="search"
              class="absolute right-1 top-1 px-3 py-1 bg-indigo-600 text-white rounded shadow-sm hover:bg-indigo-700">
              🔍
            </button>
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
              </svg>
            </span>
          </div>

          <div class="flex items-center gap-4">
            <!-- Controles de Tema -->
            <div class="flex gap-2 relative">
              <!-- TEMA -->
              <div class="relative">
                <button class="flex items-center gap-1 px-3 py-1 bg-gray-200 rounded shadow text-sm"
                  @click.stop="openMenu('theme')">
                  <i class="fas fa-palette"></i>
                  Tema
                  <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div v-show="menus.theme" class="absolute bg-white shadow rounded mt-1 w-36 z-20">
                  <button @click="setTheme('kids')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left flex items-center gap-2">
                    <i class="fas fa-child"></i> Niños
                  </button>
                  <button @click="setTheme('youth')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left flex items-center gap-2">
                    <i class="fas fa-user"></i> Jóvenes
                  </button>
                  <button @click="setTheme('adult')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left flex items-center gap-2">
                    <i class="fas fa-user-tie"></i> Adultos
                  </button>
                </div>
              </div>

              <!-- MODO -->
              <div class="relative">
                <button class="flex items-center gap-1 px-3 py-1 bg-gray-200 rounded shadow text-sm"
                  @click.stop="openMenu('mode')">
                  <i class="fas fa-moon"></i>
                  Modo
                  <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div v-show="menus.mode" class="absolute bg-white shadow rounded mt-1 w-36 z-20">
                  <button @click="setMode('day')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left flex items-center gap-2">
                    🌞 Día
                  </button>
                  <button @click="setMode('night')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left flex items-center gap-2">
                    🌙 Noche
                  </button>
                </div>
              </div>

              <!-- LETRA -->
              <div class="relative">
                <button class="flex items-center gap-1 px-3 py-1 bg-gray-200 rounded shadow text-sm"
                  @click.stop="openMenu('font')">
                  <i class="fas fa-font"></i>
                  Letra
                  <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div v-show="menus.font" class="absolute bg-white shadow rounded mt-1 w-36 z-20">
                  <button @click="setFontSize('small')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left">Pequeño</button>
                  <button @click="setFontSize('medium')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left">Medio</button>
                  <button @click="setFontSize('large')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left">Grande</button>
                </div>
              </div>

              <!-- CONTRASTE -->
              <div class="relative">
                <button class="flex items-center gap-1 px-3 py-1 bg-gray-200 rounded shadow text-sm"
                  @click.stop="openMenu('contrast')">
                  <i class="fas fa-adjust"></i>
                  Contraste
                  <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div v-show="menus.contrast" class="absolute bg-white shadow rounded mt-1 w-36 z-20">
                  <button @click="setContrast('normal')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left">Normal</button>
                  <button @click="setContrast('high')"
                    class="w-full px-3 py-2 hover:bg-gray-100 text-left">Alto</button>
                </div>
              </div>
            </div>

            <!-- User dropdown -->
            <Dropdown class="mt-1" placement="bottom-end">
              <template #default>
                <div class="group flex items-center cursor-pointer select-none">
                  <div class="mr-1 text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 whitespace-nowrap">
                    <span>{{ auth.user.name }}</span>
                    <span class="hidden md:inline">&nbsp;{{ auth.user.lastname }}</span>
                  </div>
                  <icon class="w-5 h-5 fill-gray-700 group-hover:fill-indigo-600 focus:fill-indigo-600"
                    name="cheveron-down" />
                </div>
              </template>

              <template #dropdown>
                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                  <Link class="block px-6 py-2 w-full text-left hover:text-white hover:bg-indigo-500" :href="route('logout')"
                    method="delete" as="button">
                  Logout
                  </Link>
                </div>
              </template>
            </Dropdown>
          </div>
        </div>
      </div>

      <!-- SIDEBAR + CONTENT -->
      <div class="md:flex md:grow md:overflow-hidden min-h-screen">
        <!-- Sidebar -->
        <MainMenu class="hidden shrink-0 p-4 w-56 bg-indigo-800 overflow-y-auto md:block" />

        <!-- Contenido + Footer -->
        <div class="flex flex-col flex-1 overflow-hidden">
          <!-- Contenido scrollable -->
          <div class="flex-1 overflow-y-auto px-4 py-8 md:px-12 md:py-8">
            <FlashMessages />
            <slot />
          </div>
          <!-- Footer fijo abajo -->
          <Footer class="flex-shrink-0" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import Logo from '@/Shared/Logo.vue'
import Dropdown from '@/Shared/Dropdown.vue'
import MainMenu from '@/Shared/MainMenu.vue'
import FlashMessages from '@/Shared/FlashMessages.vue'
import useTheme from '@/Composables/useTheme'
import Footer from './Footer.vue'
import { router } from '@inertiajs/vue3';

export default {
  components: { Link, Logo, Dropdown, MainMenu, FlashMessages, Footer },
  props: { auth: Object },
  setup() {
    const { themeState, setTheme, setMode, setFontSize, setContrast } = useTheme()
    const searchQuery = ref('')

    function search() {
      if (!searchQuery.value) return
      router.get(route('search'), { q: searchQuery.value })
    }

    const menus = ref({ theme: false, mode: false, font: false, contrast: false })
    function openMenu(menu) {
      Object.keys(menus.value).forEach(k => menus.value[k] = false)
      menus.value[menu] = true
    }

    function handleClickOutside(e) {
      if (!document.querySelector('#layout')?.contains(e.target)) {
        Object.keys(menus.value).forEach(k => menus.value[k] = false)
      }
    }
    document.addEventListener('click', handleClickOutside)

    return { themeState, setTheme, setMode, setFontSize, setContrast, searchQuery, search, menus, openMenu }
  },
  data() {
    return {
      route,
    }
  },
}
</script>
