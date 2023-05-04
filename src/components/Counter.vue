<template>
    <div class="flex flex-row items-center justify-evenly align-middle">
        <button class="rounded-full text-4xl bg-muse aspect-square w-16" @click="count--">
            -
        </button>
        <p class="text-5xl font-bold">{{ count }}</p>
        <button class="rounded-full text-4xl bg-muse aspect-square w-16" @click="count++">
            +
        </button>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import confetti from 'canvas-confetti';

const count = ref(0)

const launchConfetti = (count: number) => {
    confetti({
            colors: ["#3399ff", "e4007f"],
            shapes: ["circle", "square", "star"],
            particleCount: count,
            spread: 360
    })
}

watch(count, (c) => {
    if(Number.isInteger(Math.log10(c))) {
        launchConfetti(c)
    }
})
</script>