<template>  
   <div class="d-flex flex-row justify-content-between m-3"
    v-if="$parent.items.data.length && !$parent.isLoadingPage">
    <div class="text-muted">
      Menampilkan {{$parent.items.from}} sampai {{$parent.items.to}} dari {{$parent.items.total}} data
    </div>
    <div class="d-flex flex-row justify-content-around"
      style="width:30%;margin-right: 50px">
      <button class="btn btn-outline-primary active pt-1 pb-1 btn-sm"
        @click="$parent.parameters.params.page -= 1;$parent.onLoad()"
        v-if="$parent.items.current_page > 1">
        Prev
      </button>

      <button class="btn btn-outline-dark pt-1 pb-1 btn-sm" 
        style="max-width:250px;min-width: 100px">
        {{$parent.items.current_page}}
      </button>

      <button class="btn btn-outline-primary active pt-1 pb-1 btn-sm"
        @click="$parent.parameters.params.page += 1;$parent.onLoad()"
        v-if="$parent.items.last_page > $parent.items.current_page">
        Next
      </button>              
    </div>
  </div> 
</template>