<template>
 <div class="page-wrapper">
    <div class="page-body">
      <div class="container-fluid">
        <div class="card">        
          <div class="card-body py-3">
              <div class="d-flex flex-row">
                <div class="mx-2 mt-1 mb-1">
                  <h3 class="card-title">User</h3>
                </div>
                              
                <div class="mx-2 mt-1 mb-1">
                  Search:
                  <div class="ms-2 d-inline-block">
                    <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                  </div>
                </div>

                <div class="mx-2 d-inline-block mb-1 mt-1">
                  <select class="form-control form-control-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                  </select>
                </div>            

                <div class="mx-2 mt-1 mb-1">
                  <button class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"
                      style="margin-right: 5px"></i> Tambah
                  </button>              
                </div>

                <div class="mx-2 mt-1 mb-1">
                  <button class="btn btn-success btn-sm">
                    <i class="fa fa-list"
                    style="margin-right: 5px"></i> All
                  </button>
                  <button class="btn btn-primary btn-sm">
                    <i class="fa fa-circle"
                     style="margin-right: 5px"></i> Active
                  </button>
                  <button class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"
                     style="margin-right: 5px"></i> Deleted
                  </button>
                </div>

                <div class="ms-auto text-muted mt-3">
                  <button class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-align-left"
                     style="margin-right: 5px"></i> Filter
                  </button>
                  <button class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-redo"
                     style="margin-right: 5px"></i> Reset Filter
                  </button>
                  <button class="btn btn-outline-primary btn-sm">
                     <i class="fa fa-file"
                     style="margin-right: 5px"></i> Laporan
                  </button>
                </div>
              </div>
          </div>

          <!-- 
          <div class="border-bottom py-3 px-2">
            <button class="btn btn-danger btn-sm">
              <i class="fa fa-trash" 
                style="margin-right: 5px"></i> Hapus Semua
            </button>

            <button class="btn btn-success btn-sm">
              <i class="fa fa-redo"
                style="margin-right: 5px"></i> Pulihkan Semua
            </button>
          </div>
          -->

          <div class="table-responsive"
            style="min-height: 100px">
            <table class="table card-table table-vcenter text-nowrap datatable table-sm">
              <thead>
                <tr>
                  <th class="w-1">
                    <input class="form-check-input m-0 align-middle" 
                      type="checkbox" 
                      aria-label="Select all invoices">
                  </th>
                  <th>
                    No
                  </th>
                  <th>                  
                     <div class="d-flex flex-row justify-content-between align-items-baseline">
                      <div>Kode</div>
                      <div>
                        <i class="fas fa-caret-up"
                          :class="parameters.params.order == 'username' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                        <i class="fas fa-caret-down"
                          :class="parameters.params.order == 'username' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                      </div>
                    </div>
                  </th>
                  <th>
                    <div class="d-flex flex-row justify-content-between align-items-baseline">
                      <div>Nama</div>
                      <div>
                        <i class="fas fa-caret-up"
                          :class="parameters.params.order == 'username' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                        <i class="fas fa-caret-down"
                          :class="parameters.params.order == 'username' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                      </div>
                    </div>
                  </th>
                  <th>
                    <div class="d-flex flex-row justify-content-between align-items-baseline">
                      <div>Username</div>
                      <div>
                        <i class="fas fa-caret-up"
                          :class="parameters.params.order == 'username' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                        <i class="fas fa-caret-down"
                          :class="parameters.params.order == 'username' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                      </div>
                    </div>
                  </th>
                  <th>
                    <div class="d-flex flex-row justify-content-between align-items-baseline">
                      <div>Email</div>
                      <div>
                        <i class="fas fa-caret-up"
                          :class="parameters.params.order == 'username' && parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
                        <i class="fas fa-caret-down"
                          :class="parameters.params.order == 'username' && parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
                      </div>
                    </div>
                  </th>                      
                  <th></th>
                </tr>
              </thead>

              <tbody v-if="items.data.length && !isLoadingPage">
                <tr v-for="(item,index) in items.data">
                  <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                  <td><span class="text-muted">{{ index++ + 1 }}</span></td>
                  <td><span class="text-muted">{{ item.code }}</span></td>
                  <td>
                    {{item.name}}
                  </td>
                  <td>
                    {{item.username}}
                  </td>
                  <td>
                    {{item.email}}
                  </td>           
                  <td class="text-end">
                    <button class="btn btn-danger btn-sm"> 
                      <i class="fa fa-print"></i>
                    </button>
                    <button class="btn btn-primary btn-sm">
                      <i class="fa fa-info-circle"></i>
                    </button>
                    <button class="btn btn-success btn-sm">
                      <i class="fa fa-edit"></i>
                    </button>
                  </td>
                </tr>                  
              </tbody>

              <tbody v-if="!isLoadingPage && !items.data.length">
                <tr>
                  <td colspan="100"
                    class="text-center">
                    <img
                        src="/img/data-not-found.svg"
                        style="height: 250px; object-fit: cover"
                    />
                    <div class="mt-3">Data Tidak Ditemukan</div>
                  </td>
                </tr>            
              </tbody>
      
              <tbody v-if="isLoadingPage">
                <tr v-if="isLoadingPage">
                  <td colspan="100"                  
                    style="height: 250px">
                    <div class="d-flex align-items-center justify-content-center w-100">
                      <i class="fa fa-circle-notch fa-spin fa-3x"></i>                       
                    </div>
                  </td>
                </tr>            
              </tbody>
            </table>
          </div>

          <!-- PAGINATION -->
          <div class="d-flex flex-row justify-content-between m-3">
            <div class="text-muted">
              Menampilkan 1 sampai 10 dari 100.000.000.000 data
            </div>
            <div class="d-flex flex-row justify-content-around"
              style="width:30%">
              <button class="btn btn-outline-primary active pt-1 pb-1 btn-sm">
                Prev
              </button>

              <button class="btn btn-outline-dark pt-1 pb-1 btn-sm" 
                style="width:250px">
                100.000.000.000
              </button>

              <button class="btn btn-outline-primary active pt-1 pb-1 btn-sm">
                Next
              </button>              
            </div>
          </div>
        </div>    
      </div>
    </div>       
 </div>
</template>

<script>
export default {
  created(){
    setTimeout(() => {
      this.isLoadingPage = false;
    },5000);
  },

  data(){
    return {
      title : 'User',

      isLoadingPage : true,

      parameters : {
        url : "/master/user",
        params : {
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,

          start_date  : '',
          end_date    : ''
        },
        default_params : {
          soft_deleted : '',
          search      : '',
          order       : 'id',
          sort        : 'desc',
          all         : '',
          per_page    : 10,
          page        : 1,

          start_date  : '',
          end_date    : ''
        }
      },

      items : {
        data : [
          {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },
             {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },

             {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },   {
            code : '1',
            name : 'test',
            username : 'test',
            email : 'test@gmail.com'
          },
        ]
      }
    }
  },  
}
</script>