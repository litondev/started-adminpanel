<template>
  <div class="clearfix mt-3"
    v-if="self.isPaginate">
    <div class="float-left">
      Showing {{ from }} to {{ to }} of {{ total }} records
    </div>
    <div class="float-right">
      <ul class="pagination" >
          <li v-bind:class="['page-item',{disabled:disable_prive_page}]">
            <a class="page-link" @click="privousPage()" tabindex="-1">
              <i class="fas fa-chevron-left"></i>
            </a>
          </li>

          <li v-for="i in pages" :key=i
            v-bind:class="['page-item',{active : i == active_page}]"
            @click="self.onLoad(i)">
            <a class="page-link page">{{ i }}</a>
          </li>

          <li v-if="total_page > 5" class="page-item disabled" >
            <a class="page-link" href="#">...Of</a>
          </li>

          <li v-if="total_page > 5" class="page-item disabled">
            <a class="page-link" href="#"> {{ total_page }}</a>
          </li>

          <li v-bind:class="['page-item',{disabled:disable_next_page}]">
            <a class="page-link" @click="nextPage()">
              <i class="fas fa-chevron-right"></i>
            </a>
          </li>
        </ul>
    </div>
  </div>
</template>

<script>
import { mapState} from 'vuex'

export default {
  props: {
    self: Object,
  },

  data () {
    return {
      pages               : [],
      click_counter       : 1,
      disable_next_page   : false,
      disable_prive_page  : true,
      active_page         : '',
      end_page            : '',
    }
  },
  computed : {
    ...mapState('pagination',{
      from         : state => state.from,
      to           : state => state.to,
      total_page   : state => state.total_page,
      current_page : state => state.current_page,
      total        : state => state.total,
      per_page     : state => state.per_page,
    }),
  },

  methods: {
     generatePage(){
        var page_count  = [];
        var total_pages = this.total_page  > 5 ? 5 : this.total_page;

        for(var i = 0;i <= total_pages -1 ;i++){
          page_count.push(i+1);
        }

        this.pages              = page_count;
        this.click_counter      = 1;
        this.disable_next_page  = this.total_page <= 5;

     },

     nextPage(){
        var counter       = this.click_counter +1;
        var start_page    = (counter * 5) - 5;
        var rest_page     = 0;
        var page_count    = [];
        this.active_page  = start_page + 1;

        if (this.total_page - (counter * 5) + 5 >= 5 ){
           rest_page = (counter * 5);
        }else{
          rest_page  = this.total_page;
          this.disable_next_page  = true;

        }

         for (var i = start_page+1, len =rest_page; i <= len; i++) {
            page_count.push(i);
        }

       this.pages             = page_count;
       this.disable_prive_page= false;
       this.click_counter     = this.click_counter + 1;
     },

     privousPage(){
        var counter    = this.click_counter - 1;
        var start_page = this.pages.pop();
        var prive_page = 0;
        var rest_page  = 0;
        var page_count = [];

        this.active_page = start_page +1;
        rest_page        = start_page - (counter * 5);

        if (rest_page <= 5) {
           prive_page = start_page - rest_page;
           this.disable_prive_page  = prive_page <= 5;
        }

        for (var i = (prive_page+1)-5, len = prive_page ; i <= len; i++) {
            page_count.push(i);
        }

        this.pages              = page_count;
        this.click_counter      = this.click_counter - 1;
        this.disable_next_page  = this.total_page <= 5;

    },

 },
};
</script>
