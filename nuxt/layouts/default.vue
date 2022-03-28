<template>
  <div class="page">     
      <!--
      <header-layout /> 
      
      <navbar-layout /> 
      -->

      <navbar-header-layout />

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
import NavbarLayout from "@/components/layouts/navbar.vue";
import HeaderLayout from "@/components/layouts/header.vue";
import NavbarHeaderLayout from "@/components/layouts/navbar-header.vue";

export default {
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

  mounted(){  
    window.addEventListener("scroll", this.onScroll());  
  },

  components : {
    NavbarLayout,
    HeaderLayout,
    NavbarHeaderLayout
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


/* BTN BOTTOM TO TOP STYLE */
#topBtn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color:#3abaf4 !important;
  color: white;
  cursor: pointer;
  padding: 10px;
  padding-left:15px;
  padding-right:15px;
  border-radius: 4px;
  scroll-behavior: smooth;
}

/* LIGHT GRAY GLOBAL STYLE */
.light-gray{
  color:lightgray
}

/* CURSOR POINTER GLOBAL STYLE */
.cursor-pointer{
  cursor:pointer
}
</style>