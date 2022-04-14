<template>
  <portal to="modal-detail">
    <div class="modal fade" 
      aria-hidden="true" 
      id="modal-detail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Detail Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
     
            <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">                  
                    <div class="form-groups p-2">
                      <label for="code">Kode</label>
                      <div>{{$parent.detail_form.code}}</div>
                    </div>                  
                  </div>

                  <div class="col-md-6">
                    <div class="form-group p-2">
                      <label for="name">Nama</label>
                      <div>{{$parent.detail_form.name}}</div>
                    </div>
                  </div>                             
              </div>              

              <div class="form-group p-2">
                <label for="user_id">User</label>
                <div>{{$parent.detail_form.user ? $parent.detail_form.user.name : '-'}}</div>
              </div>

             <div class="row p-2">
               <div class="col-md-4 form-group p-2">
                 <label for="price">Price</label>
                 <div>{{$parent.detail_form.price | fromatPrice}}</div>
               </div>
               <div class="col-md-4 form-group p-2">
                 <label for="stock">Stok</label>
                 <div>{{$parent.detail_form.stock}}</div>
               </div>
               <div class="col-md-4 form-group p-2">
                 <label for="sold">Terjual</label>
                 <div>{{$parent.detail_form.sold}}</div>
               </div>
             </div>

             <div class="form-group p-2">
               <img 
                v-for="(itemImage,indexImage) in $parent.detail_form.images"
                :key="indexImage"
                :src="getImage(itemImage)"
                style="width:50px;height:50px;object-fit:cover"/>
             </div>
            </div>            
        </div>
      </div>
    </div>
  </portal>
</template>

<script>
export default {
  methods : {
    getImage(item){
      return process.env.IMAGES_URL + '/products/' + item
    }
  }
}
</script>
