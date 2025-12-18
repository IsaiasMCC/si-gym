<template>
  <div>
    <Bar 
      v-if="chartData.labels.length" 
      :data="chartData" 
      :options="chartOptions" 
    />
    <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
      No hay datos suficientes para mostrar el gráfico
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, watch } from 'vue'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default defineComponent({
  name: 'BarChart',
  components: { Bar },
  props: {
    subscripcions: { // Cambiado de reservas
      type: Array,
      required: true
    }
  },
  setup(props) {
    const chartData = ref({
      labels: [],
      datasets: []
    })

    const chartOptions = ref({
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { display: false },
        title: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1, precision: 0 }
        }
      }
    })

    const generateChartData = () => {
      if (!props.subscripcions || props.subscripcions.length === 0) {
        chartData.value = { labels: [], datasets: [] }
        return
      }

      const map = {}
      props.subscripcions.forEach(s => {
        const fecha = s.fecha_inicio
        if (fecha) {
          map[fecha] = (map[fecha] || 0) + 1
        }
      })

      const sorted = Object.fromEntries(
        Object.entries(map).sort(([a], [b]) => new Date(a) - new Date(b))
      )

      chartData.value = {
        labels: Object.keys(sorted),
        datasets: [
          {
            label: 'Subscripciones',
            data: Object.values(sorted),
            backgroundColor: '#4f46e5',
            borderColor: '#4338ca',
            borderWidth: 1,
            borderRadius: 4
          }
        ]
      }
    }

    watch(() => props.subscripcions, generateChartData, { immediate: true, deep: true })

    return { chartData, chartOptions }
  }
})
</script>
