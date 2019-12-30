<template>
    <div>
        <div class="keywords-input w-full form-control form-input form-input-bordered flex items-center" :class="{ 'border-danger': errors.has(name) }" @click="focusInput">
            <span v-for="keyword in keywords" :key="keyword" class="keywords-input-keyword mr-1">
                <span>{{ keyword }}</span>
                <button
                    type="button"
                    class="keywords-input-remove"
                    @click.prevent.stop="remove(keyword)"
                >
                    &times;
                </button>
            </span>
            <input
                ref="input"
                class="keywords-input-text"
                v-bind:value="input"
                v-on:input="handleInput"
                v-on:keydown="handleKeydown"
                v-on:blur="handleBlur"
            >
        </div>
    </div>
</template>

<script>
export default {
    props: {
        name: String,
        keywords: { type: Array, required: true },
        limit: { type: Number, default: 8 },
        errors: Object,
    },

    model: {
        prop: 'keywords',
    },

    data() {
        return {
            input: '',
        };
    },

    methods: {
        focusInput() {
            this.$refs.input.focus();
        },

        select(keyword) {
            this.emitInput(keyword);
        },

        add() {
            const input = this.input.replace('/,/', ' ').trim();

            if (input.length && !this.keywords.includes(input)) {
                this.emitInput([...this.keywords, input]);
                this.clear();
            }
        },

        remove(keyword) {
            this.emitInput(this.keywords.filter(k => k !== keyword));
        },

        clear() {
            this.input = '';
        },

        emitInput(keywords) {
            this.$emit('input', keywords);
            this.focusInput();
        },

        handleInput(e) {
            this.input = e.target.value;
        },

        handleKeydown(e) {
            switch (e.key) {
                case 'Backspace':
                    if (!this.input.trim().length)
                        this.$emit('input', this.keywords.slice(0, -1));
                    break;
                case 'Enter':
                    if (e.target.value) {
                        this.add();
                        e.preventDefault();
                    }
                    break;
                case ',':
                    this.add();
                    e.preventDefault();
                    break;
            }
        },

        handleBlur(e) {
            if (e.target.value)
                this.add();
        },
    },

    mounted() {
        this.keywords = this.keywords || [];
    },
};
</script>
