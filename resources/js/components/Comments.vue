<template>
    <div class="blog-comments">
        <nova-blog-comment
            v-for="comment in comments"
            :key="comment.id"
            :post="post"
            :comment="comment"
            :user="user"
            :locale="locale"
        ></nova-blog-comment>
        <div class="blog-comment">
            <img class="blog-comment-avatar" :src="gravatarUrl" :alt="t('Avatar')">
            <nova-blog-comment-form v-if="hasUser" :post="post" :user="user" :locale="locale" @created="handleCreated"></nova-blog-comment-form>
            <slot v-if="!hasUser"></slot>
        </div>
    </div>
</template>

<script>
import Lang from '../Mixins/Lang';
import md5 from 'js-md5';

export default {
    props: {
        post: Object,
        user: Object,
        comments: { type: Array, default: () => [] },
        pollTimeout: { type: Number, default: 10000 },
    },

    mixins: [
        Lang,
    ],

    computed: {
        hasUser() {
            return this.user && this.user.id;
        },

        gravatarUrl() {
            if (this.hasUser) {
                return `https://www.gravatar.com/avatar/${md5(this.user.email)}?s=512`;
            } else
                return `https://www.gravatar.com/avatar?s=512`;
        },
    },

    methods: {
        fetch(params) {
            return window.axios.get('/vendor/nova-blog/comments', {
                params: Object.assign({
                    post_id: this.post.id,
                }, params),
            });
        },

        handleCreated(comment) {
            this.comments.push(comment);
            this.$forceUpdate();
        },
    },

    mounted() {
        if (!this.comments.length) {
            this.fetch().then(response => {
                this.comments.splice(0, this.comments.length, ...response.data);
            });
        }

        setInterval(() => {
            this.fetch().then(response => {
                this.comments.splice(0, this.comments.length, ...response.data);
                this.$forceUpdate();
            });
        }, this.pollTimeout);
    },
};
</script>
