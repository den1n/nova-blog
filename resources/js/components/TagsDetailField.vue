<template>
    <panel-item :field="field">
        <span slot="value" v-html="(typeof field.value === 'object' ? field.value.join(', ') : field.value) || '—'"></span>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
};
</script>
