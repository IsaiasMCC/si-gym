// resources/js/Composables/useTheme.js
import { reactive, watch } from 'vue'

const themeState = reactive({
  theme: 'adult',       // kids | youth | adult
  mode: 'day',          // day | night
  fontSize: 'medium',   // small | medium | large
  contrast: 'normal'    // normal | high
})

// Guardar en localStorage y aplicar clases y estilos
watch(themeState, (newVal) => {
  localStorage.setItem('theme', newVal.theme)
  localStorage.setItem('mode', newVal.mode)
  localStorage.setItem('fontSize', newVal.fontSize)
  localStorage.setItem('contrast', newVal.contrast)

  // Modo día/noche
  document.documentElement.classList.toggle('dark', newVal.mode === 'night')

  // Contraste
  document.documentElement.classList.toggle('high-contrast', newVal.contrast === 'high')

  // Tamaño de letra
  document.documentElement.style.fontSize =
    newVal.fontSize === 'small' ? '0.875rem' :
    newVal.fontSize === 'medium' ? '1rem' : '1.25rem'
}, { deep: true })

// Inicializar desde localStorage (solo si existe)
const savedTheme = localStorage.getItem('theme')
if (savedTheme) themeState.theme = savedTheme
const savedMode = localStorage.getItem('mode')
if (savedMode) themeState.mode = savedMode
const savedFont = localStorage.getItem('fontSize')
if (savedFont) themeState.fontSize = savedFont
const savedContrast = localStorage.getItem('contrast')
if (savedContrast) themeState.contrast = savedContrast

export default function useTheme() {
  const setTheme = (theme) => themeState.theme = theme
  const setMode = (mode) => themeState.mode = mode
  const setFontSize = (size) => themeState.fontSize = size
  const setContrast = (contrast) => themeState.contrast = contrast

  return { themeState, setTheme, setMode, setFontSize, setContrast }
}
