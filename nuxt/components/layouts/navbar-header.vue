<template>
  <div>
 <header class="navbar navbar-expand-md navbar-light d-print-none">
    <div class="container-fluid">
      <button class="navbar-toggler" 
        type="button" 
        data-bs-toggle="collapse" 
        data-bs-target="#navbar-menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <span class="d-none d-lg-block d-md-block d-xl-block">
        <main-navbar-layout/>
      </span>
    
      <h1 class="navbar-brand navbar-brand-auto dark d-none-navbar-horizontal pe-0 pe-md-3">
        <nuxt-link to="/">
          <img src="" 
            width="110" 
            height="32" 
            alt="Tabler"             
            id="navbar-brand-image"
            class="navbar-brand-image">
        </nuxt-link>
      </h1>

      <div class="navbar-nav flex-row order-md-last">  
        <div class="d-none d-md-flex">
          <a href="#"
            @click="setTheme('dark')"
            class="nav-link px-0 hide-theme-dark" 
            title="Enable dark mode" 
            data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <i class="fa fa-moon"></i>
          </a>
          <a href="#"
            @click="setTheme('light')" 
            class="nav-link px-0 hide-theme-light" 
            title="Enable light mode" 
            data-bs-toggle="tooltip" 
            data-bs-placement="bottom">
            <i class="fa fa-sun"></i>
          </a>          
        </div>
        <div class="nav-item dropdown">
          <a href="#" class="nav-link d-flex lh-1 text-reset p-0" 
            data-bs-toggle="dropdown" 
            aria-label="Open user menu">
            <span class="avatar avatar-sm" 
              style="background-image: url(/img/default.jpeg)"></span>
            <div class="d-none d-xl-block ps-2">
              <div>Litoninot</div>
              <div class="mt-1 small text-muted">User</div>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <a href="#" class="dropdown-item">Profile</a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">Settings</a>
            <a href="#" class="dropdown-item">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <navbar-layout class="d-block d-lg-none d-md-none d-xl-none"/>
</div>
</template>

<script>
import NavbarLayout from "@/components/layouts/navbar.vue";
import MainNavbarLayout from "@/components/layouts/main-navbar.vue";

export default {
  components : {
    NavbarLayout,
    MainNavbarLayout
  },

  mounted(){      
   this.changeLogo();
  },

  methods : {
    setTheme(theme){
      localStorage.setItem("tablerTheme",theme);

      document.body.classList.remove('theme-dark', 'theme-light');
      document.body.classList.add("theme-"+theme);    

      this.changeLogo()
    },

    changeLogo(){
      if(localStorage.getItem("tablerTheme") == "dark"){
        document.getElementById("navbar-brand-image").src = "/img/logo-white.svg";
      }else{
        document.getElementById("navbar-brand-image").src = "/img/logo.svg";    
      }
    }
  }
}
</script>