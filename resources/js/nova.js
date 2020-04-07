Nova.booting(Vue => {
    Vue.component('index-nova-blog-tags-field', require('./fields/TagsIndexField'));
    Vue.component('detail-nova-blog-tags-field', require('./fields/TagsDetailField'));
    Vue.component('form-nova-blog-tags-field', require('./fields/TagsFormField'));

    Vue.component('index-nova-blog-keywords-field', require('./fields/KeywordsIndexField'));
    Vue.component('detail-nova-blog-keywords-field', require('./fields/KeywordsDetailField'));
    Vue.component('form-nova-blog-keywords-field', require('./fields/KeywordsFormField'));
});
