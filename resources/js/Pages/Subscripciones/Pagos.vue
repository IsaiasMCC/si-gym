<template>
  <GymLayout>
    <section class="max-w-4xl mx-auto p-6">
      <h2 class="text-3xl font-bold mb-6 text-center text-white">
        Planes de Pago de {{ subscripcion.membresia.nombre }}
      </h2>

      <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 rounded-2xl shadow-lg">
        <!-- Información general -->
        <p class="text-gray-400 mb-2">{{ subscripcion.membresia.descripcion }}</p>
        <p class="text-indigo-400 font-bold mb-2">Precio Membresía: Bs {{ subscripcion.membresia.precio_base }}</p>
        <p class="text-gray-500 mb-4">Desde: {{ subscripcion.fecha_inicio }} - Hasta: {{ subscripcion.fecha_fin }}</p>

        <!-- Paquetes adicionales -->
        <div v-if="subscripcion.paquetes && subscripcion.paquetes.length" class="mb-4">
          <h4 class="text-sm font-semibold text-gray-300 mb-2">Paquetes adicionales:</h4>
          <ul class="space-y-1">
            <li v-for="p in subscripcion.paquetes" :key="p.id" class="text-gray-400 text-sm">
              {{ p.paquete?.nombre }} (+ Bs {{ p.paquete?.precio_adicional }})
            </li>
          </ul>
        </div>

        <!-- Planes de pago -->
        <div v-if="subscripcion.plan_pagos && subscripcion.plan_pagos.length">
          <h4 class="text-lg font-semibold text-white mb-2">Planes de pago:</h4>
          <ul class="space-y-2">
            <li v-for="plan in subscripcion.plan_pagos" :key="plan.id"
              class="bg-gray-900 p-4 rounded-lg flex justify-between items-center">
              <div>
                <p class="text-gray-300 text-sm">
                  Tipo: {{ plan.tipo_pago }} |
                  Monto: Bs {{ plan.monto }} |
                  Saldo: Bs {{ plan.saldo }} |
                  Estado: <span :class="{
                    'text-green-400': plan.estado === 'pagado',
                    'text-yellow-400': plan.estado === 'pendiente',
                    'text-red-500': plan.estado === 'vencido'
                  }">{{ plan.estado }}</span>
                </p>
                <p class="text-gray-400 text-xs">Vence: {{ plan.fecha_vencimiento }}</p>
              </div>
              <button v-if="plan.estado === 'pendiente' || plan.estado === 'vencido'" @click="openModal(plan)"
                class="bg-indigo-500 px-3 py-1 rounded hover:bg-indigo-400 text-white text-sm">
                Pagar
              </button>
            </li>
          </ul>
        </div>

        <!-- Total -->
        <p class="text-yellow-400 font-bold text-lg mt-4">
          Total subscripción: Bs {{ calcularTotal(subscripcion) }}
        </p>

        <!-- Volver -->
        <button @click="$inertia.visit('/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones')"
          class="mt-4 w-full bg-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-600 text-white">
          Volver
        </button>
      </div>

      <!-- Modal de pago -->
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-gray-900 rounded-xl shadow-lg max-w-md w-full p-6 relative">
          <h3 class="text-xl font-bold text-white mb-4">Realizar Pago</h3>
          <p class="text-gray-400 mb-2">Monto a pagar: Bs {{ selectedPlan.monto }}</p>

          <label class="block mb-2 text-gray-300">Método de pago:</label>
          <select v-model="metodoPago" class="w-full p-2 rounded bg-gray-800 text-white mb-4">
            <option disabled value="">Seleccione método</option>
            <option value="efectivo">Efectivo</option>
            <option value="qr">QR</option>
          </select>

          <!-- Comentado: QR será implementado luego -->
          <!-- <div v-if="metodoPago === 'qr'">
            <p class="text-gray-400 mb-2">Se generará un QR para el pago.</p>
            <QRCode :value="selectedPlan.id" />
          </div> -->

          <div class="flex justify-end space-x-2">
            <button @click="closeModal" class="px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 text-white">
              Cancelar
            </button>
            <button @click="confirmarPago" :disabled="!metodoPago || loadingQr"
              class="px-4 py-2 rounded bg-indigo-500 hover:bg-indigo-400 text-white">
              Pagar
            </button>

          </div>
        </div>
      </div>

      <!-- Modal de éxito -->
      <div v-if="showSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-gray-900 rounded-xl shadow-lg max-w-md w-full p-6 text-center">
          <h3 class="text-2xl font-bold text-green-400 mb-4">
            ✅ Pago realizado
          </h3>

          <p class="text-gray-300 mb-6">
            El pago se completó exitosamente.
          </p>

          <button @click="showSuccessModal = false"
            class="px-6 py-2 rounded bg-indigo-500 hover:bg-indigo-400 text-white font-semibold">
            Aceptar
          </button>
        </div>
      </div>

      <!-- Modal QR -->
      <div v-if="showQrModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div class="bg-gray-900 rounded-xl shadow-lg max-w-md w-full p-6 text-center">
          <h3 class="text-xl font-bold text-white mb-4">
            Escanea para pagar
          </h3>

          <img v-if="qrImage" :src="qrImage" class="mx-auto mb-4 w-64 h-64" />

          <!-- Estado del pago -->
          <p v-if="estadoPago" class="text-sm mb-3" :class="{
            'text-yellow-400': estadoPago === 'PENDIENTE',
            'text-green-400': estadoPago === 'COMPLETADO',
            'text-red-400': estadoPago === 'RECHAZADO' || estadoPago === 'ANULADO'
          }">
            Estado: {{ estadoPago }}
          </p>

          <!-- Botón consultar -->
          <button @click="consultarPago" :disabled="consultandoEstado"
            class="w-full mb-3 px-4 py-2 rounded bg-indigo-500 hover:bg-indigo-400 text-white font-semibold">
            {{ consultandoEstado ? 'Consultando...' : 'Consultar pago' }}
          </button>

          <p class="text-gray-400 mb-4 text-sm">
            Esperando confirmación del pago...
          </p>

          <button @click="cancelarQr" class="px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 text-white">
            Cancelar
          </button>
        </div>
      </div>

      <!-- Modal loading QR -->
      <div v-if="loadingQr" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div class="bg-gray-900 rounded-xl shadow-lg max-w-sm w-full p-6 text-center">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto mb-4"></div>

          <p class="text-gray-300 font-semibold">
            Generando código QR...
          </p>

          <p class="text-gray-500 text-sm mt-2">
            Por favor espere
          </p>
        </div>
      </div>



    </section>
  </GymLayout>
</template>

<script>
import GymLayout from '@/Shared/GymLayout.vue'
import axios from 'axios'

export default {
  components: { GymLayout },
  props: {
    subscripcion: Object
  },
  data() {
    return {
      showModal: false,
      showSuccessModal: false,
      showQrModal: false,
      loadingQr: false,
      consultandoEstado: false, // 👈 NUEVO
      estadoPago: null,         // 👈 NUEVO
      qrImage: null,
      qrTransaccionId: null,
      selectedPlan: null,
      metodoPago: ''
    }
  }
  ,


  methods: {
    calcularTotal(subs) {
      const precioMembresia = Number(subs.membresia.precio_base) || 0
      const totalPaquetes = (subs.paquetes || []).reduce(
        (acc, p) => acc + (Number(p.paquete?.precio_adicional) || 0),
        0
      )
      return precioMembresia + totalPaquetes
    },
    openModal(plan) {
      this.selectedPlan = plan
      this.metodoPago = ''
      this.showModal = true
    },
    closeModal() {
      this.showModal = false
      this.selectedPlan = null
    },
    confirmarPago() {
      if (!this.metodoPago) return

      if (this.metodoPago === 'efectivo') {
        this.pagarEfectivo()
      }

      if (this.metodoPago === 'qr') {
        this.generarQr()
      }
    },
    pagarEfectivo() {
      this.$inertia.post(
        `/inf513/grupo18sc/proyecto2/sis-gym/public/plan-pagos/${this.selectedPlan.id}/pagar`,
        {
          metodo_pago: 'efectivo',
          referencia: null
        },
        {
          onSuccess: () => {
            this.showModal = false
            this.showSuccessModal = true
            this.selectedPlan = null
            this.metodoPago = ''
          }
        }
      )
    },
    generarQr() {
      this.loadingQr = true
      this.showModal = false   // 👈 cerrar modal de pago inmediatamente

      axios.post(
        '/inf513/grupo18sc/proyecto2/sis-gym/public/pagofacil/generar-qr',
        {
          pago_id: this.selectedPlan.id,
          monto: this.selectedPlan.monto
        }
      )
        .then(response => {
          const data = response.data

          this.qrImage = data.qr
          this.qrTransaccionId = data.transaccion

          this.showQrModal = true
        })
        .catch(error => {
          console.error(error)
          alert('Error al generar el QR')
        })
        .finally(() => {
          this.loadingQr = false   // 👈 apagar loading
        })
    },

    cancelarQr() {
      this.showQrModal = false
      this.qrImage = null
      this.qrTransaccionId = null
      this.estadoPago = null
    },
    consultarPago() {
      if (!this.qrTransaccionId) return

      this.consultandoEstado = true

      axios.post(
        '/inf513/grupo18sc/proyecto2/sis-gym/public/pagofacil/consultar-estado',
        {
          tnTransaccion: this.qrTransaccionId
        }
      )
        .then(response => {
          console.log(response.data)
          this.estadoPago = response.data.estado

          // ✅ Si se completó el pago
          if (this.estadoPago === 'COMPLETADO') {
            this.showQrModal = false
            this.showSuccessModal = true

            // limpiar estado
            this.qrImage = null
            this.qrTransaccionId = null
            this.estadoPago = null
          }
        })
        .catch(error => {
          console.error(error)
          alert('Error al consultar el estado del pago')
        })
        .finally(() => {
          this.consultandoEstado = false
        })
    }




  }
}
</script>
