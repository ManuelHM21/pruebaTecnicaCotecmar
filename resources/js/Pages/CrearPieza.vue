<template>
  <AppLayout title="Crear Pieza">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Crear Pieza
      </h2>
    </template>
    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-lg mt-8">
      <form @submit.prevent="enviarPieza">
        <div class="mb-4">
          <label class="block mb-1 font-semibold">ID de la Pieza</label>
          <input v-model="id" class="w-full border rounded px-3 py-2" required placeholder="Ej: 001" />
          <span v-if="errores.id" class="text-red-500 text-sm">{{ errores.id }}</span>
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Nombre</label>
          <input v-model="nombre" class="w-full border rounded px-3 py-2" required placeholder="Nombre de la pieza" />
          <span v-if="errores.nombre" class="text-red-500 text-sm">{{ errores.nombre }}</span>
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Peso Teórico</label>
          <input v-model="peso_teorico" type="number" step="any" min="0" class="w-full border rounded px-3 py-2" required placeholder="Peso teórico" />
          <span v-if="errores.peso_teorico" class="text-red-500 text-sm">{{ errores.peso_teorico }}</span>
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Bloque</label>
          <select v-model="bloque_id" class="w-full border rounded px-3 py-2" required>
            <option value="">Seleccione un bloque</option>
            <option v-for="b in bloques" :key="b.id" :value="b.id">{{ b.nombre }}</option>
          </select>
          <span v-if="errores.bloque_id" class="text-red-500 text-sm">{{ errores.bloque_id }}</span>
        </div>
        <div class="flex justify-center">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
            Crear Pieza
          </button>
        </div>
      </form>
      <div v-if="success" class="mt-4 p-3 bg-green-100 text-green-700 rounded">
        {{ success }}
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const bloques = usePage().props.bloques || []
const id = ref('')
const nombre = ref('')
const peso_teorico = ref('')
const bloque_id = ref('')
const errores = reactive({})
const success = ref('')

function enviarPieza() {
  Object.keys(errores).forEach(k => delete errores[k])
  if (!id.value) errores.id = 'Ingrese el ID'
  if (!nombre.value) errores.nombre = 'Ingrese el nombre'
  if (!peso_teorico.value || isNaN(peso_teorico.value)) errores.peso_teorico = 'Ingrese un peso válido'
  if (!bloque_id.value) errores.bloque_id = 'Seleccione un bloque'
  if (Object.keys(errores).length) return

  router.post('/piezas', {
    id: id.value,
    nombre: nombre.value,
    peso_teorico: peso_teorico.value,
    bloque_id: bloque_id.value
  }, {
    onSuccess: () => {
      success.value = 'Pieza creada correctamente'
      id.value = ''
      nombre.value = ''
      peso_teorico.value = ''
      bloque_id.value = ''
    },
    onError: (err) => {
      Object.assign(errores, err)
    }
  })
}
</script>