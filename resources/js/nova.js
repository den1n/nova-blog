Nova.booting(Vue => {
    Vue.component('index-nova-blog-tags-field', require('./components/TagsIndexField'));
    Vue.component('detail-nova-blog-tags-field', require('./components/TagsDetailField'));
    Vue.component('form-nova-blog-tags-field', require('./components/TagsFormField'));

    Vue.component('index-nova-blog-keywords-field', require('./components/KeywordsIndexField'));
    Vue.component('detail-nova-blog-keywords-field', require('./components/KeywordsDetailField'));
    Vue.component('form-nova-blog-keywords-field', require('./components/KeywordsFormField'));
});
