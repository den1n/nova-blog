<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <keywords-input
                :name="field.attribute"
                :errors="errors"
                v-model="keywords"
            ></keywords-input>
        </template>
    </default-field>
</template>

<script>
import KeywordsInput from './KeywordsInput.vue';
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
            keywords: this.field.value,
        };
    },

    components: {
        KeywordsInput,
    },

    methods: {
        fill(formData) {
            formData.append(this.field.attribute, this.keywords.join('|'));
        },

        handleChange(value) {
            this.value = value;
        },
    },
};
</script>
