export default {
	methods : {
		 onDestroyAll(){
       Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Menghapus Semua Data Ini",
        icon: "warning",
        confirmButtonColor: "#f1261f",
        confirmButtonText: "Delete",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {          
            return this.$axios.delete(this.url + "/destroy-all",{
              data : {
                checkboxs : this.checkboxs
              }
            })
            .catch(err => {                  
              console.log(err);     

              if(err.response && err.response.status === 500){
                Swal.fire("Failed",err.response.data.message || 'Terjadi Kesalahan',"warning");
              }else{
                Swal.fire("Failed","Terjadi Kesalahan","warning");
              }                    

              return false;
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then(({value}) => {        
        if (value) {          
            this.onLoad();            
            Swal.fire("Deleted!", "Data Berhasil Di Hapus", "success");
        }
      });
    },
	}
}