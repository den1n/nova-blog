const lang = {};
const files = require.context('../../lang', false, /\.json$/i);
files.keys().map(key => {
    lang[key.split('/').pop().split('.')[0]] = files(key);
});

export default {
    props: {
        locale: String,
    },

    data() {
        return {
            lang,
        };
    },

    methods: {
        t(text) {
            if (this.lang[this.locale]) {
                return this.lang[this.locale][text] || text;
            } else
                return text;
        },
    },
}
