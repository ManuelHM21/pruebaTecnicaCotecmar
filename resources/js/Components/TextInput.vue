<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-b border-0 border-black/80 focus:border-black focus:ring-0 w-full p-2"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
