<template>
  <div id="app">
    <div class="container-fluid mt-5">
      <div class="d-flex justify-content-center">

        <div class="text-center" 
          v-if="error.statusCode == 404">
          <img
           src="/img/404.svg" 
           style="width: 400px; object-fit: cover" 
          />
          <div class="mt-3">
            The page you were looking for could not be found.
          </div>
          <div class="mt-2">
            <a href="/">Back to Home</a>
          </div>
        </div>

        <div class="text-center" 
          v-else-if="error.statusCode == 500">
          <img 
            src="/img/500.svg" 
            style="width: 400px; object-fit: cover" 
            />

          <div class="text-danger mt-3" 
            v-if="isDebug">
            {{ error.message }}
          </div>

          <div class="mt-3 text-danger" 
            v-else>
            Terjadi Kesalahan
          </div>

          <div class="mt-2">
            <a href="#" @click="onReload">Reload Halaman</a>
          </div>
        </div>

        <div class="text-center" v-else>
          <img
            src="/img/something-wrong.svg"
            style="width: 400px; object-fit: cover"
          />
          
          <div class="mt-3">Something Wrong</div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  props: ["error"],

  layout: "empty",

  created(){
    let myScript = [
      "/dists/js/demo.min.js",    
    ];

    myScript.forEach((item,index) => {
      let script = document.createElement('script')
      script.setAttribute('src', item)
      document.body.appendChild(script)
    });
  },

  head() {
    return {
      title: this.error.statusCode,
    };
  },

  created() {    
    if (this.isDebug) {
      this.$toaster.error(this.error.message);
      console.log(this.error.message);
    }
  },

  computed : {
    isDebug(){
      return parseInt(process.env.DEBUG);
    }
  },

  methods: {    
    onReload() {
      window.location.reload();
    },
  },
};
</script>


