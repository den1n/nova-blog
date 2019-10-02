<template>
    <span v-html="field.value.join(', ') || '—'"></span>
</template>

<script>
export default {
    inheritAttrs: false,

    props: ['field'],
};
</script>
