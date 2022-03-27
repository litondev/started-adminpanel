<template>
  <div>
    <div class="navbar-bg" :style="header_color"></div>
    <nav class="navbar navbar-expand-lg main-navbar">

      <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" id="toggle-sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li> -->
        </ul>
        <!-- <app-search-form></app-search-form>  -->
      </form>

      <ul class="navbar-nav navbar-right">
        <!-- messages -->
        <!-- <app-messages></app-messages> -->
        <!-- notifications -->
        <!-- <app-notifications></app-notifications> -->
        <!-- user options -->
        <app-user-options></app-user-options>
      </ul>
    </nav>
  </div>
</template>
<script>
import SearchForm from "@/components/anavbar/SearchForm.vue";
import Messages from "@/components/anavbar/Messages.vue";
import Notifications from "@/components/anavbar/Notifications.vue";
import UserOptions from "@/components/anavbar/UserOptions.vue";
export default {
  components: {
    'app-search-form' : SearchForm,
    'app-messages' : Messages,
    'app-notifications' : Notifications,
    'app-user-options' : UserOptions,
  },
  computed: {
    header_color(){
      let header_color = this.$store.state.setting.settings.find(item => item.name == "header_color");
      return header_color ? "background:"+header_color.value : "";
    }
  }
}
</script>
