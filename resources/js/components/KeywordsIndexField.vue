<template>
    <span v-html="field.value.join(', ') || '—'"></span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
};
</script>
