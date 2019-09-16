import Vue from 'vue';
import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {

    'use strict';

    let currentPage = 0;
    let infiniteScroll = true;

    const content = document.querySelector('.blog-content');

    const observer = new IntersectionObserver(items => {
        if (content.offsetHeight > window.outerHeight) {
            for (const item of items) {
                if (item.isIntersecting && infiniteScroll) {
                    console.log(fetchItems());
                    // fetchItems().then(items => {
                    //     content.items.push(...items);
                    //     if (infiniteScroll = items.length)
                    //         currentPage++;
                    // });
                }
            }
        }
    });

    observer.observe(
        document.querySelector('.blog-abyss')
    );

    function fetchItems() {
        return axios.get('.');
    }

});
