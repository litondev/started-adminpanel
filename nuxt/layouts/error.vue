<template>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="page-error">

          <div class="page-inner" 
            v-if="error.statusCode == 404">
            <img
             src="/img/404.svg" 
             style="width: 400px; object-fit: cover" 
            />
            <div class="page-description mt-3">
              The page you were looking for could not be found.
            </div>
            <div class="page-search">
              <a href="/">Back to Home</a>
            </div>
          </div>

          <div class="page-inner" 
            v-else-if="error.statusCode == 500">
            <img 
              src="/img/500.svg" 
              style="width: 400px; object-fit: cover" 
              />

            <div class="page-description text-danger mt-3" v-if="isDebug">
              {{ error.message }}
            </div>

            <div class="page-description mt-3 text-danger" v-else>Terjadi Kesalahan</div>

            <div class="page-search">
              <a href="#" @click="onReload">Reload Halaman</a>
            </div>
          </div>

          <div class="page-inner" v-else>
            <img
              src="/img/something-wrong.svg"
              style="width: 400px; object-fit: cover"
            />
            
            <div class="page-description">Something Wrong</div>
          </div>
        </div>
        <div class="simple-footer mt-5">Copyright &copy; Stisla 2018</div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  props: ["error"],

  layout: "empty",

  head() {
    return {
      title: this.error.statusCode,
    };
  },

  created() {
    let myScript = ["/js/stisla.js", "/js/scripts.js"];

    myScript.forEach((item, index) => {
      let script = document.createElement("script");
      script.setAttribute("src", item);
      document.body.appendChild(script);
    });

    window.$(".modal.in").modal("hide");
    window.$(".modal-backdrop").hide();
    window.$("body").removeClass("modal-open");

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
