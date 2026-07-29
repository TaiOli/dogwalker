<script setup lang="ts">
defineProps<{
  variant: variantTypes;
  weight?: weightTypes;
  muted?: boolean;
}>();

type variantTypes =
  | 'h1'
  | 'h2'
  | 'h3'
  | 'h4'
  | 'h5'
  | 'h6'
  | 'subtitle-1'
  | 'subtitle-2'
  | 'body-1'
  | 'body-2'
  | 'caption'
  | 'overline';

type weightTypes = 'black' | 'bold' | 'medium' | 'regular' | 'light' | 'thin';
</script>

<template>
  <div
    :class="`${
      muted ? 'text-medium-emphasis' : ''
    } text-${variant} font-weight-${weight || 'regular'}`">
    <slot />
  </div>
</template>