<template>
  <AppLayout title="Crear Proyecto">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Crear Proyecto
      </h2>
    </template>
    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-lg mt-8">
      <form @submit.prevent="enviarProyecto">
        <div class="mb-4">
          <label class="block mb-1 font-semibold">ID del Proyecto</label>
          <input v-model="id" class="w-full border rounded px-3 py-2" required placeholder="Ej: BICM" />
          <span v-if="errores.id" class="text-red-500 text-sm">{{ errores.id }}</span>
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Nombre</label>
          <input v-model="nombre" class="w-full border rounded px-3 py-2" required placeholder="Nombre del proyecto" />
          <span v-if="errores.nombre" class="text-red-500 text-sm">{{ errores.nombre }}</span>
        </div>
        <div class="flex justify-center">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
            Crear Proyecto
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
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const id = ref('')
const nombre = ref('')
const errores = reactive({})
const success = ref('')

function enviarProyecto() {
  Object.keys(errores).forEach(k => delete errores[k])
  if (!id.value) errores.id = 'Ingrese el ID'
  if (!nombre.value) errores.nombre = 'Ingrese el nombre'
  if (Object.keys(errores).length) return

  router.post('/proyectos', { id: id.value, nombre: nombre.value }, {
    onSuccess: () => {
      success.value = 'Proyecto creado correctamente'
      id.value = ''
      nombre.value = ''
    },
    onError: (err) => {
      Object.assign(errores, err)
    }
  })
}
</script>