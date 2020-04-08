<template>
    <panel-item :field="field">
        <span slot="value" v-html="field.value.join(', ') || '—'"></span>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
};
</script>
