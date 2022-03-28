<template>
  <thead class="bg-none">
    <tr>
      <th v-if="isShowCheckBox"
        style="width:5%">
        <input type="checkbox"
          @click="checkAll"
          v-if="isShowBox"
          :checked="isCheckBoxCheked">
      </th>

      <th class="text-left"
        v-for="(item, index) in self.table_header_options"
        :key="index"
        v-bind:style="[!item.width ? '' : { width: item.width }]">
        <div class="d-flex flex-row justify-content-between">
          <div v-if="!item.column">
            <b class="align-self-left">
              {{ item.title }}
            </b>
          </div>
          <div
            class="cursor-pointer w-100 d-flex"
            @click="
              self.onSort(
                item.column,
                self.search.sort === 'asc' ? 'desc' : 'asc'
              )
            "
            v-else>
            <b class="align-self-center">{{ item.title }}</b>
          </div>

          <div class="d-flex flex-shrink-1 flex-column" 
            v-if="item.column">
            <i
              class="fas fa-caret-up cursor-pointer"
              :class="
                self.search.order != item.column || self.search.sort != 'asc'
                  ? 'light-gray'
                  : ''
              "
              @click="self.onSort(item.column, 'asc')"
            ></i>
            <i
              class="fas fa-caret-down cursor-pointer"
              :class="
                self.search.order != item.column || self.search.sort != 'desc'
                  ? 'light-gray'
                  : ''
              "
              @click="self.onSort(item.column, 'desc')"
            ></i>
          </div>
        </div>
      </th>

      <th v-if="isHideAction" 
        style="width:15%">
        <b>Aksi</b>
      </th>
    </tr>
  </thead>
</template>

<script>
export default {
  props: {
    self: Object
  },

  computed: {
    isShowCheckBox() {  
      return ![
        "history-bandwidth-customer",
        "history-bandwidth-reseller",
        "billing-payment-reseller",
        "billing-payment-customer",
        "reseller",
        "customer"
      ].includes(this.self.name);
    },

    isShowBox(){  
      return this.self.pagination.data.length;
    },

    isCheckBoxCheked(){
      return this.self.checkboxs.length;
    },

    isHideAction() {
      return ![
        "history-bandwidth-customer",
        "history-bandwidth-reseller",
      ].includes(this.self.name);
    }
  },

  methods: {
   /*
    isLightGrayAsc(item) {
      return (
        this.self.search.order != item.column || this.self.search.sort != "asc"
      );
    },

    isLightGrayDesc(item) {
      return (
        this.self.search.order != item.column || this.self.search.sort != "desc"
      );
    },
    */
    
    checkAll(e){    
      let allCheckboxs = document.querySelectorAll(".all-checkboxs");      

      if(!allCheckboxs.length){
        return false;
      }

      this.self.checkboxs = [];

      for(let i=0;i<allCheckboxs.length;i++){
        allCheckboxs[i].checked = e.target.checked;

        if(e.target.checked){
          this.self.checkboxs.push(allCheckboxs[i].value);
        }
      }
    }
  }
};
</script>