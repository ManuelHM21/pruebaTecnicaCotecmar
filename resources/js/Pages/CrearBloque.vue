<template>
  <AppLayout title="Crear Bloque">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Crear Bloque
      </h2>
    </template>
    <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-lg mt-8">
      <form @submit.prevent="enviarBloque">
        <div class="mb-4">
          <label class="block mb-1 font-semibold">ID del Bloque</label>
          <input v-model="id" class="w-full border rounded px-3 py-2" required placeholder="Ej: 130-1110" />
          <span v-if="errores.id" class="text-red-500 text-sm">{{ errores.id }}</span>
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Nombre</label>
          <input v-model="nombre" class="w-full border rounded px-3 py-2" required placeholder="Nombre del bloque" />
          <span v-if="errores.nombre" class="text-red-500 text-sm">{{ errores.nombre }}</span>
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Proyecto</label>
          <select v-model="proyecto_id" class="w-full border rounded px-3 py-2" required>
            <option value="">Seleccione un proyecto</option>
            <option v-for="p in proyectos" :key="p.id" :value="p.id">{{ p.nombre }}</option>
          </select>
          <span v-if="errores.proyecto_id" class="text-red-500 text-sm">{{ errores.proyecto_id }}</span>
        </div>
        <div class="flex justify-center">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
            Crear Bloque
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

const proyectos = usePage().props.proyectos || []
const id = ref('')
const nombre = ref('')
const proyecto_id = ref('')
const errores = reactive({})
const success = ref('')

function enviarBloque() {
  Object.keys(errores).forEach(k => delete errores[k])
  if (!id.value) errores.id = 'Ingrese el ID'
  if (!nombre.value) errores.nombre = 'Ingrese el nombre'
  if (!proyecto_id.value) errores.proyecto_id = 'Seleccione un proyecto'
  if (Object.keys(errores).length) return

  router.post('/bloques', { id: id.value, nombre: nombre.value, proyecto_id: proyecto_id.value }, {
    onSuccess: () => {
      success.value = 'Bloque creado correctamente'
      id.value = ''
      nombre.value = ''
      proyecto_id.value = ''
    },
    onError: (err) => {
      Object.assign(errores, err)
    }
  })
}
</script>