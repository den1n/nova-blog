<template>
    <div>
        <div class="keywords-input w-full form-control form-input form-input-bordered flex items-center" :class="{ 'border-danger': errors.has(name) }" @click="focusInput">
            <span v-for="keyword in keywords" :key="keyword" class="keywords-input-keyword mr-1">
                <span>{{ keyword }}</span>
                <button type="button"  class="keywords-input-remove" @click="handleRemove(keyword)">&times;</button>
            </span>
            <input ref="input" class="keywords-input-text" :value="input" @input="handleInput" @keydown="handleKeydown" @blur="handleBlur">
        </div>
    </div>
</template>

<script>
export default {
    props: {
        name: String,
        keywords: { type: Array, default: () => [] },
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

        add() {
            const input = this.input.replace('/,/', ' ').trim();

            if (input.length && !this.keywords.includes(input)) {
                this.$emit('input', [...this.keywords, input]);
                this.clear();
                return true;
            } else
                this.clear();
        },

        clear() {
            this.input = '';
        },

        handleInput(e) {
            this.input = e.target.value;
        },

        handleRemove(keyword) {
            this.$emit('input', this.keywords.filter(k => k != keyword));
            this.focusInput();
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
            if (e.target.value && this.add())
                this.focusInput();
        },
    },
};
</script>

<style scoped>
.keywords-input {
    flex-wrap: wrap;
    height: auto;
    min-height: 36px;
    padding-top: 0.25rem;
}

.keywords-input:focus-within {
    box-shadow: 0 0 8px var(--primary);
}

.keywords-input-keyword {
    align-items: center;
    background-color: var(--primary);
    border-radius: 0.25rem;
    color: white;
    display: inline-flex;
    font-size: 0.875rem;
    line-height: 1;
    margin-bottom: 0.25rem;
    padding: 0.125rem 0.375rem;
    user-select: none;
}

.keywords-input-remove {
    color: white;
    font-size: 1.125rem;
    margin-left: 0.25rem;
}

.keywords-input-remove:focus {
    outline: none;
}

.keywords-input-text {
    margin-bottom: 0.25rem;
    margin-left: 0.25rem;
    min-width: 8rem;
    outline: 0;
}
</style>
