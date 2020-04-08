<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <tags-input
                :name="field.attribute"
                :placeholder="field.name"
                :class="errorClasses"
                :limit="field.limit"
                :errors="errors"
                v-model="tags"
            ></tags-input>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import TagsInput from './TagsInput.vue';

export default {
    mixins: [
        FormField,
        HandlesValidationErrors,
    ],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            tags: this.field.value || [],
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
