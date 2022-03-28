<template>
 <div class="navbar-expand-md">
    <div class="collapse navbar-collapse" 
      id="navbar-menu">
      <div class="navbar navbar-light">
        <div class="container-fluid">        
          <main-navbar-layout />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MainNavbarLayout from "@/components/layouts/main-navbar.vue";

export default {
  components : {
    MainNavbarLayout
  }
}
</script>