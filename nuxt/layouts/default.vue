<template>
  <div id="app">
    <Nuxt/>

    <portal-target name="form"></portal-target>
    <portal-target name="form-child"></portal-target>
    <portal-target name="filter"></portal-target>

    <button @click="topScroll"
      id="topBtn" title="Go to top">
      <i class="fas fa-caret-up"></i>
    </button>
  </div>
</template>

<script>
export default {
  created(){
    // let myScript = [
    //   "/js/stisla.js",
    //   "/js/scripts.js",
    // ];

    // myScript.forEach((item,index) => {
    //   let script = document.createElement('script')
    //   script.setAttribute('src', item)
    //   document.body.appendChild(script)
    // });
  },

  mounted(){  
    window.addEventListener("scroll", this.onScroll());  
  },

  methods : {
    onScroll(){
      window.onscroll = () => {
        let btn = document.getElementById("topBtn");

        if(btn){
          btn.style.display = (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
            ? "block"
            : "none";
        }
      };
    },
    topScroll(){
      window.scroll({
        top: 0,
        behavior: 'smooth'
      });
    }
  },
};
</script>

<style>
* {
  font-size: 12px;  
}
</style>