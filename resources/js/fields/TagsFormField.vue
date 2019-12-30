<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <tags-input
                :name="field.attribute"
                :limit="field.limit"
                :errors="errors"
                v-model="tags"
            ></tags-input>
        </template>
    </default-field>
</template>

<script>
import TagsInput from './TagsInput.vue';
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
    inheritAttrs: false,

    mixins: [
        FormField,
        HandlesValidationErrors,
    ],

    props: [
        'field',
    ],

    data() {
        return {
            tags: this.field.value,
        };
    },

    components: {
        TagsInput,
    },

    methods: {
        fill(formData) {
            formData.append(this.field.attribute, this.tags.join('|'));
        },

        handleChange(value) {
            this.value = value;
        },
    },
};
</script>
