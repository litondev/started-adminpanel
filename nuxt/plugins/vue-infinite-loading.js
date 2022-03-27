import Vue from "vue";
import InfiniteLoading from 'vue-infinite-loading';

Vue.use(InfiniteLoading, {
  slots: {
    // keep default styles
    noResults: 'No results message',


    // hide slot
    error: {
      render: h => h('div'),
    },
  }
});
