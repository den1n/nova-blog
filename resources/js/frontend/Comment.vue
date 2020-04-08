<template>
    <div class="blog-comment" v-if="!deleted">
        <img class="blog-comment-avatar" :src="gravatarUrl" :alt="t('Avatar')">
        <div class="blog-comment-body" v-if="!updating">
            <span class="blog-comment-anchor" :id="commentAnchor"></span>
            <div class="blog-comment-info">
                <span class="blog-comment-author">{{ comment.author.name }}</span>
                <a class="blog-comment-date" :title="dateHint" :href="'#' + commentAnchor">
                    {{ comment.readable_created_at }}
                </a>

            </div>
            <div class="blog-comment-content" v-html="comment.content"></div>
            <div class="blog-comment-controls" v-if="user.id">
                <a href="#reply" v-if="!isAuthor" @click.prevent="handleReply">{{ t('Reply') }}</a>
                <a href="#quote" @click.prevent="handleQuote">{{ t('Quote') }}</a>
                <a href="#update" v-if="isAuthor || user.can_update_comments" @click.prevent="handleUpdating">{{ t('Update') }}</a>
                <a href="#remove" v-if="isAuthor || user.can_delete_comments" @click.prevent="handleRemove">{{ t('Remove') }}</a>
            </div>
        </div>
        <nova-blog-comment-form v-if="updating"
            :postId="post.id"
            :commentId="comment.id"
            :commentContent="comment.content"
            :locale="locale"
            @updated="handleUpdated"
            @cancel="handleCancel"
        ></nova-blog-comment-form>
    </div>
</template>

<script>
import Lang from './Lang';
import EventBus from './EventBus';

export default {
    props: {
        post: Object,
        comment: Object,
        user: Object,
        createForm: Object,
    },

    data() {
        return {
            updating: false,
            deleted: false,
        };
    },

    mixins: [
        Lang,
        EventBus,
    ],

    computed: {
        commentAnchor() {
            return `comment-${this.comment.id}`;
        },

        isAuthor() {
            return this.comment.author.id === this.user.id;
        },

        gravatarUrl() {
            return `https://www.gravatar.com/avatar/${this.comment.gravatar_id}?s=512`;
        },

        dateHint() {
            if (this.comment.is_updated)
                return `${this.t('Has been updated')}: ${this.comment.readable_updated_at}`;
        },

        commentContent() {
            return this.$el.querySelector('.blog-comment-content');
        },
    },

    methods: {
        handleReply(e) {
            this.eventBus.$emit('nova-blog.comments.reply', {
                commentAuthor: this.comment.author.name,
                commentId: this.comment.id,
            });
        },

        handleQuote(e) {
            const selection = window.getSelection();
            const range = selection.rangeCount ? selection.getRangeAt(0) : document.createRange();
            if (selection.isCollapsed || !this.commentContent.contains(selection.anchorNode))
                range.selectNodeContents(this.commentContent);

            this.eventBus.$emit('nova-blog.comments.quote', {
                quote: range.cloneContents(),
                commentAuthor: this.comment.author.name,
                commentId: this.comment.id,
            });
        },

        handleUpdating(e) {
            this.updating = true;
        },

        handleUpdated(comment) {
            this.updating = false;
            this.comment.content = comment.content;
            this.comment.updated_at = comment.updated_at;
            this.comment.readable_updated_at = comment.readable_updated_at;
        },

        handleRemove(e) {
            if (confirm(this.t('Remove the comment?'))) {
                const data = { comment_id: this.comment.id };
                window.axios.delete('/vendor/nova-blog/comments', { data })
                    .then(response => {
                        this.deleted = true;
                    });
            }
        },

        handleCancel() {
            this.updating = false;
        }
    },
};
</script>
