<template>
    <div>
        <div class="tags-input w-full form-control form-input form-input-bordered flex items-center" :class="{ 'border-danger': errors.has(name) }" @click="focusInput">
            <span v-for="tag in tags" :key="tag" class="tags-input-tag mr-1">
                <span>{{ tag }}</span>
                <button
                    type="button"
                    class="tags-input-remove"
                    @click.prevent.stop="remove(tag)"
                >
                    &times;
                </button>
            </span>
            <input
                ref="input"
                class="tags-input-text"
                v-bind:value="input"
                v-on:input="handleInput"
                v-on:keydown="handleKeydown"
                v-on:blur="handleBlur"
            >
        </div>
        <ul v-if="suggestions.length" class="tags-input-suggestions">
            <li v-for="suggestion in suggestions" :key="suggestion" class="mr-1">
                <button
                    class="tags-input-tag"
                    @mousedown.prevent
                    @click.prevent="suggest(suggestion)"
                >
                    {{ suggestion }}
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        name: String,
        tags: { required: true },
        limit: { default: 8 },
        errors: Object,
    },

    model: {
        prop: 'tags',
    },

    data() {
        return {
            input: '',
            suggestions: [],
            timeout: 0,
        };
    },

    methods: {
        focusInput() {
            this.$refs.input.focus();
        },

        fetch() {
            if (this.input && this.limit) {
                let query = `?tag=${this.input}&limit=${this.limit}`;
                window.axios.get(`/nova-vendor/den1n/nova-blog/tags/field${query}`).then(response => {
                    if (this.input) {
                        this.suggestions = response.data.filter(suggestion => {
                            return !this.tags.find(tag => tag === suggestion);
                        });
                    } else
                        this.suggestions = [];
                });
            } else
                this.suggestions = [];
        },

        suggest(suggestion) {
            this.emitInput([...this.tags, suggestion]);
            this.clear();
        },

        select(tag) {
            this.emitInput(tag);
        },

        add() {
            const input = this.input.trim();
            if (input.length && !this.tags.includes(input)) {
                this.emitInput([...this.tags, input]);
                this.clear();
            }
        },

        remove(tag) {
            this.emitInput(this.tags.filter(t => t !== tag));
        },

        clear() {
            this.input = '';
            this.suggestions = [];
        },

        emitInput(tags) {
            this.$emit('input', tags);
            this.focusInput();
        },

        handleInput(e) {
            clearTimeout(this.timeout);
            this.input = e.target.value;
            this.timeout = setTimeout(this.fetch, 300);
        },

        handleKeydown(e) {
            switch (e.key) {
                case 'Backspace':
                    if (!this.input.trim().length)
                        this.$emit('input', this.tags.slice(0, -1));
                    break;
                case 'Enter':
                    if (e.target.value) {
                        this.add();
                        e.preventDefault();
                    }
                    break;
                case 'Tab':
                    if (this.suggestions.length === 1) {
                        this.input = this.suggestions[0];
                        this.add();
                        e.preventDefault();
                    }
                    break;
            }
        },

        handleBlur(e) {
            if (e.target.value)
                this.add();
        },
    },
};
</script>
