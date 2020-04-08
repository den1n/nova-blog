<template>
    <default-field :field="field" :errors="errors" class="justify-content-stretch">
        <template slot="field">
            <keywords-input
                :name="field.attribute"
                :placeholder="field.name"
                :class="errorClasses"
                :errors="errors"
                v-model="keywords"
            ></keywords-input>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import KeywordsInput from './KeywordsInput.vue';

export default {
    mixins: [
        FormField,
        HandlesValidationErrors,
    ],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            keywords: this.field.value || [],
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
