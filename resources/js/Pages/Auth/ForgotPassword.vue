<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue'; // Asegúrate que este es tu componente personalizado

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="flex justify-center items-center min-h-screen w-full bg-[url('/img/login/bgLogin.jpg')] bg-cover bg-center backdrop-blur-md animate-fade animate-duration-[800ms]">
        <div class="w-full h-screen flex justify-center items-center backdrop-blur-md bg-black/20">
            <div class="flex flex-col gap-6 max-w-md w-full shadow-xl bg-white rounded-xl overflow-hidden p-6 md:p-8 animate-fade-up animate-duration-[800ms] animate-ease-in-out">
                <div class="flex flex-col items-center text-center mb-4">
                    <img src="/img/CotecmarLogo.png" alt="Cotecmar Logo" class="w-20 mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Has olvidado tu contraseña</h1>
                </div>

                <div class="mb-4 text-sm text-gray-600 text-center">
                    ¿Olvidaste tu contraseña? No hay problema. Solo indícanos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y podrás elegir una nueva.
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md text-center">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="you@example.com"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing, 'bg-[#003882] text-white hover:bg-[#002c66]': !form.processing }"
                            :disabled="form.processing"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Enviando...' : 'Enviar correo' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
