<template>
  <div class="space-y-2">
    <div v-for="(group, index) in filteredMenu" :key="index">
      <!-- Grupo -->
      <div class="flex items-center justify-between cursor-pointer px-3 py-2 rounded transition-colors" :style="{
        backgroundColor: openGroups[index]
          ? 'var(--color-primary)'
          : themeState.mode === 'night'
            ? '#1f2937'
            : '#e5e7eb'
      }" @click="toggleGroup(index)">
        <span class="uppercase text-xs font-bold tracking-wider text-gray-900 dark:text-gray-100">
          {{ group.label }}
        </span>
        <svg :class="['w-4 h-4 transition-transform duration-200', openGroups[index] ? 'rotate-90' : '']" fill="none"
          stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>

      <!-- Items del grupo -->
      <transition name="fade">
        <div v-show="openGroups[index]" class="ml-0 mt-2 space-y-1">
          <div v-for="item in group.items" :key="item.href">
            <Link class="group flex items-center w-full py-2 px-3 rounded-lg transition-colors" :href="item.href"
              :style="{
                backgroundColor: isUrl(item.href)
                  ? 'var(--color-primary)'
                  : themeState.mode === 'night'
                    ? '#374151'
                    : '#f3f4f6',
                color: isUrl(item.href) ? 'white' : themeState.mode === 'night' ? 'white' : 'black'
              }">
            <Icon :name="item.icon" class="mr-2 w-5 h-5" :style="{
              fill: isUrl(item.href) ? 'white' : themeState.mode === 'night' ? 'white' : 'black'
            }" />
            <div class="flex-1 truncate">{{ item.label }}</div>
            </Link>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from "@/Shared/Icon.vue"
import useTheme from '@/Composables/useTheme'

export default {
  components: { Link, Icon },
  setup() {
    const { themeState } = useTheme()
    return { themeState }
  },
  data() {
    return {
      openGroups: {},
      menu: [
        {
          label: "Gestión de Usuarios",
          items: [
            { label: "Roles", href: "/roles", icon: "shield-check", permission: "roles visualizar" },
            { label: "Usuarios", href: "/usuarios", icon: "users", permission: "usuarios visualizar" },
          ],
        },
        {
          label: "Gestión de Membresias y Paquetes",
          items: [
            { label: "Membresias", href: "/membresias", icon: "credit-card", permission: "membresias visualizar" },
            { label: "Paquetes", href: "/paquetes", icon: "archive", permission: "paquetes visualizar" },
            { label: "Rutinas", href: "/rutinas", icon: "clock", permission: "rutinas visualizar" },
          ],
        },
        {
          label: "Gestión de Subscripciones / Clientes",
          items: [
            { label: "Mis Membresias", href: "/subscripciones", icon: "calendar", permission: "rutinas cliente visualizar" },
          ],
        },
        {
          label: "Gestión de Rutina / Entrenador",
          items: [
            { label: "Mis Rutinas Asignadas", href: "/rutinas-usuarios", icon: "calendar-check", permission: "rutinas entrenador visualizar" },
            // { label: "Seguimientos Cliente", href: "/seguimientos", icon: "document-text", permission: "reservas cliente visualizar" },
          ],
        },
        {
          label: "Reportes",
          items: [
            // { label: "Seguimientos", href: "/seguimientos-reportes/reportes", icon: "document-report", permission: "reservas visualizar" },
            { label: "Pagos", href: "/pagos-reportes/reportes", icon: "cash", permission: "reporte pagos visualizar" },
          ],
        },
      ]


    }
  },
  computed: {
    filteredMenu() {
      const userPermissions = this.$page.props.auth?.user?.permissions || [];
      return this.menu
        .map(group => ({
          ...group,
          items: group.items.filter(item => userPermissions.includes(item.permission))
        }))
        .filter(group => group.items.length > 0);
    }
  },
  methods: {
    isUrl(url) {
      const currentUrl = this.$page.url.substr(1)
      if (url === "") return currentUrl === ""
      return currentUrl.startsWith(url.substr(1))
    },
    toggleGroup(index) {
      this.openGroups[index] = !this.openGroups[index]
    },
  }
}
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: all .2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>
