<template>
    <form class="blog-comment-form">
        <div class="blog-comment-form-toolbar" @click.prevent="handleToolbar">
            <button href="#bold" data-command="bold" :title="t('Bold') + ' (Ctrl + B)'">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M16.591 11.359c0.879-1.044 1.409-2.391 1.409-3.859 0-3.308-2.692-6-6-6h-7.5v21h9c3.308 0 6-2.692 6-6 0-2.179-1.167-4.090-2.909-5.141zM9 4.5h2.379c1.312 0 2.379 1.346 2.379 3s-1.067 3-2.379 3h-2.379v-6zM12.727 19.5h-3.727v-6h3.727c1.37 0 2.484 1.346 2.484 3s-1.114 3-2.484 3z"></path>
                </svg>
            </button>
            <button href="#italic" data-command="italic" :title="t('Italic') + ' (Ctrl + I)'">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M21 1.5v1.5h-3l-7.5 18h3v1.5h-10.5v-1.5h3l7.5-18h-3v-1.5z"></path>
                </svg>
            </button>
            <button href="#underline" data-command="underline" :title="t('Underline') + ' (Ctrl + U)'">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M16.5 1.5h3v9.75c0 3.728-3.358 6.75-7.5 6.75s-7.5-3.022-7.5-6.75v-9.75h3v9.75c0 0.941 0.427 1.845 1.204 2.543 0.865 0.778 2.035 1.207 3.296 1.207s2.432-0.429 3.296-1.207c0.776-0.698 1.204-1.602 1.204-2.543v-9.75zM4.5 19.5h15v3h-15z"></path>
                </svg>
            </button>
            <span></span>
            <button href="#link" data-command="link" :title="t('Insert link')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M10.318 14.901c-0.312 0-0.624-0.119-0.862-0.357-2.23-2.23-2.23-5.858 0-8.088l4.5-4.5c1.080-1.080 2.516-1.675 4.044-1.675s2.964 0.595 4.044 1.675c2.23 2.23 2.23 5.858 0 8.088l-2.057 2.057c-0.476 0.476-1.248 0.476-1.724 0s-0.476-1.248 0-1.724l2.057-2.057c1.279-1.279 1.279-3.361 0-4.64-0.62-0.62-1.444-0.961-2.32-0.961s-1.7 0.341-2.32 0.961l-4.5 4.5c-1.279 1.279-1.279 3.361 0 4.64 0.476 0.476 0.476 1.248 0 1.724-0.238 0.238-0.55 0.357-0.862 0.357z"></path>
                    <path d="M6 23.719c-1.528 0-2.964-0.595-4.044-1.675-2.23-2.23-2.23-5.858 0-8.088l2.057-2.057c0.476-0.476 1.248-0.476 1.724 0s0.476 1.248 0 1.724l-2.057 2.057c-1.279 1.279-1.279 3.361 0 4.64 0.62 0.62 1.444 0.961 2.32 0.961s1.7-0.341 2.32-0.961l4.5-4.5c1.279-1.279 1.279-3.361 0-4.64-0.476-0.476-0.476-1.248 0-1.724s1.248-0.476 1.724 0c2.23 2.23 2.23 5.858 0 8.088l-4.5 4.5c-1.080 1.080-2.516 1.675-4.044 1.675z"></path>
                </svg>
            </button>
            <button href="#image" data-command="image" :title="t('Insert image')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M22.497 3c0.001 0.001 0.002 0.002 0.003 0.003v17.995c-0.001 0.001-0.002 0.002-0.003 0.003h-20.995c-0.001-0.001-0.002-0.002-0.003-0.003v-17.995c0.001-0.001 0.002-0.002 0.003-0.003h20.995zM22.5 1.5h-21c-0.825 0-1.5 0.675-1.5 1.5v18c0 0.825 0.675 1.5 1.5 1.5h21c0.825 0 1.5-0.675 1.5-1.5v-18c0-0.825-0.675-1.5-1.5-1.5v0z"></path>
                    <path d="M19.5 6.75c0 1.243-1.007 2.25-2.25 2.25s-2.25-1.007-2.25-2.25 1.007-2.25 2.25-2.25 2.25 1.007 2.25 2.25z"></path>
                    <path d="M21 19.5h-18v-3l5.25-9 6 7.5h1.5l5.25-4.5z"></path>
                </svg>
            </button>
            <button href="#video" data-command="video" :title="t('Embed video')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M22.997 3.753c-3.366-0.484-7.086-0.753-10.997-0.753s-7.63 0.269-10.997 0.753c-0.645 2.525-1.003 5.314-1.003 8.247s0.358 5.723 1.003 8.247c3.367 0.484 7.086 0.753 10.997 0.753s7.63-0.269 10.997-0.753c0.645-2.525 1.003-5.314 1.003-8.247s-0.358-5.723-1.003-8.247zM9 16.5v-9l7.5 4.5-7.5 4.5z"></path>
                </svg>
            </button>
            <span></span>
            <button href="#quote" data-command="quote" :title="t('To quote')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M18.75 15c-2.899 0-5.25-2.351-5.25-5.25s2.351-5.25 5.25-5.25 5.25 2.351 5.25 5.25l0.023 0.75c0 5.799-4.701 10.5-10.5 10.5v-3c2.003 0 3.887-0.78 5.303-2.197 0.273-0.273 0.522-0.563 0.746-0.868-0.268 0.042-0.543 0.064-0.823 0.064zM5.25 15c-2.899 0-5.25-2.351-5.25-5.25s2.351-5.25 5.25-5.25 5.25 2.351 5.25 5.25l0.023 0.75c0 5.799-4.701 10.5-10.5 10.5v-3c2.003 0 3.887-0.78 5.303-2.197 0.273-0.273 0.522-0.563 0.746-0.868-0.268 0.042-0.543 0.064-0.823 0.064z"></path>
                </svg>
            </button>
        </div>
        <div class="blog-comment-form-editor" contenteditable="true" @keydown="handleShortcut" v-html="comment ? comment.content : ''">
        </div>
        <div class="blog-comment-form-notice" v-if="notice">{{ notice }}</div>
        <button type="submit" class="btn btn-primary" title="Ctrl + Enter" :disabled="busy" @click.prevent="handleSubmit">
            {{ comment ? t('Update') : t('Submit') }}
        </button>
        <button type="reset" class="btn btn-secondary" v-if="comment" @click.prevent="handleCancel">
            {{ t('Cancel') }}
        </button>
    </form>
</template>

<script>
import Lang from '../Mixins/Lang';
import EventBus from '../Mixins/EventBus';

export default {
    props: {
        post: Object,
        comment: Object,
    },

    data() {
        return {
            busy: false,
            notice: '',
        };
    },

    mixins: [
        Lang,
        EventBus,
    ],

    computed: {

        editor() {
            return this.$el.querySelector('[contenteditable]');
        },
    },

    methods: {
        focus(scrollToEditor) {
            if (scrollToEditor)
                this.editor.scrollIntoView({ behavior: 'smooth' });
            this.editor.focus();
        },

        create() {
            const data = { post_id: this.post.id, content: this.editor.innerHTML };
            return window.axios.put('/vendor/nova-blog/comments', data)
                .then(response => {
                    this.$emit('created', response.data);
                    this.editor.innerHTML = '';
                });
        },

        update() {
            const data = { post_id: this.post.id, comment_id: this.comment.id, content: this.editor.innerHTML };
            return window.axios.post('/vendor/nova-blog/comments', data)
                .then(response => {
                    this.$emit('updated', response.data);
                });
        },

        handleToolbar(e) {
            const target = e.target.closest('[data-command]');
            if (target) {
                const command = target.dataset.command;
                const methodName = 'handleToolbar' + command.charAt(0).toUpperCase() + command.slice(1);
                if (typeof this[methodName] === 'function') {
                    const selection = window.getSelection();
                    this[methodName]({
                        inBounds: this.editor.contains(selection.anchorNode),
                        range: selection.rangeCount ? selection.getRangeAt(0) : document.createRange(),
                        selection,
                    });
                    this.focus();
                }
            }
        },

        handleToolbarBold(options) {
            if (options.inBounds)
                document.execCommand('bold');
        },

        handleToolbarItalic(options) {
            if (options.inBounds)
                document.execCommand('italic');
        },

        handleToolbarUnderline(options) {
            if (options.inBounds)
                document.execCommand('underline');
        },

        handleToolbarQuote(options) {
            if (options.inBounds && options.selection.toString()) {
                const br = document.createElement('br');
                options.range.surroundContents(document.createElement('blockquote'));
                options.range.collapse();
                options.range.insertNode(br);
                options.range.selectNodeContents(br);
                options.selection.removeAllRanges();
                options.selection.addRange(options.range);
                options.range.collapse();
            }
        },

        handleToolbarLink(options) {
            const link = prompt(this.t('Enter link URL to insert'));
            if (link && link.trim()) {
                const a = document.createElement('a');
                a.target = '_blank';
                a.href = link;
                a.append(options.range.collapsed ? document.createTextNode(link) : options.range.extractContents());
                options.range.insertNode(a);
                options.range.collapse();
            }
        },

        handleToolbarImage(options) {
            const image = prompt(this.t('Enter image URL to insert'));
            if (image && image.trim()) {
                const img = document.createElement('img');
                img.src = image;
                options.range.extractContents();
                options.range.insertNode(img);
                options.range.collapse();
            }
        },

        handleToolbarVideo(options) {
            const video = prompt(this.t('Enter the video code to insert'));
            if (video && video.trim()) {
                const div = document.createElement('div');
                div.insertAdjacentHTML('beforeend', video);
                const iframe = div.querySelector('iframe');
                if (iframe && iframe instanceof HTMLIFrameElement) {
                    iframe.removeAttribute('height');
                    iframe.removeAttribute('width');
                }
                options.range.extractContents();
                options.range.insertNode(iframe);
                options.range.collapse();
            }
        },

        handleReply(options) {
            let reply = this.editor.querySelector('a[href="#comment-anchor-' + options.comment.id + '"]')
            if (!reply) {
                reply = document.createElement('a');
                reply.className = 'blog-comment-reply-link';
                reply.innerText = '@' + options.comment.author.name;
                reply.href = '#comment-anchor-' + options.comment.id;

                const links = this.editor.querySelectorAll('.blog-comment-reply-link');
                if (links && links.length) {
                    links[links.length - 1].after(reply);
                } else
                    this.editor.prepend(reply);
            }

            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(reply);
            selection.removeAllRanges();
            selection.addRange(range);
            range.collapse();
            this.focus(true);
        },

        handleQuote(options) {
            const reply = document.createElement('a');
            reply.className = 'blog-comment-quote-reply-link';
            reply.innerText = '@' + options.comment.author.name;
            reply.href = '#comment-anchor-' + options.comment.id;

            const br = document.createElement('br');
            const quote = document.createElement('blockquote');
            quote.append(reply);
            quote.append(options.quote);
            this.editor.append(quote);
            this.editor.append(br);

            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(br);
            selection.removeAllRanges();
            selection.addRange(range);
            range.collapse();
            this.focus(true);
        },

        handleShortcut(e) {
            if (!this.busy && e.ctrlKey && e.key === 'Enter') {
                this.handleSubmit();
                e.preventDefault();
            }
        },

        handleSubmit() {
            this.notice = '';
            const hasContent = this.editor.innerText.trim() || this.editor.querySelectorAll('img, .blog-comment-video').length;
            if (!this.busy && hasContent) {
                this.busy = true;
                const methodName = this.comment ? 'update' : 'create';
                if (typeof this[methodName] === 'function') {
                    this[methodName]()
                        .catch(e => this.notice = this.t('Failed to send your comment. Please try again later.'))
                        .finally(() => this.busy = false);
                } else
                    throw new Error('Invalid method name');
            }
        },

        handleCancel() {
            this.$emit('cancel');
        },
    },

    mounted() {
        if (!this.comment) {
            this.eventBus.$on('nova-blog.comments.reply', this.handleReply);
            this.eventBus.$on('nova-blog.comments.quote', this.handleQuote);
        }
    },
};
</script>
