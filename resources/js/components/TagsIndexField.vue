<template>
    <span v-html="(typeof field.value === 'object' ? field.value.join(', ') : field.value) || '—'"></span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
};
</script>
