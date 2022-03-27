<template>
  <div>
    <li class="dropdown">
      <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <img alt="image" src="/img/avatar/avatar-1.png" class="rounded-circle mr-1">
        <div class="d-sm-none d-lg-inline-block">Hi, {{$auth.user.fullname}}</div>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <!--
          <div class="dropdown-title">Logged in 5 min ago</div>
        -->
        <nuxt-link to="/profil" class="dropdown-item has-icon"><i class="far fa-user"></i> Profil </nuxt-link>
        <!--
          <a href="" class="dropdown-item has-icon"><i class="fas fa-bolt"></i> Activities </a> -->
        <!-- <div class="dropdown-divider"></div> -->
        <a href="#" @click="logout" class="dropdown-item has-icon text-danger">
          <span v-if="isLoadingLogout" class="text-center">
            <i class="fas fa-circle-notch fa-spin"></i>
          </span>
          <span v-else>
            <i class="fas fa-sign-out-alt"></i> Logout
          </span>
        </a>
      </div>
    </li>
  </div>
</template>
<script>
export default {
  data(){
    return {
      isLoadingLogout : false
    }
  },

  methods : {
    logout(){
      if(this.isLoadingLogout) return;

      this.isLoadingLogout = true;

      this.$auth.logout()
      .then(() => {
        window.location.reload()
        // this.$router.push("/login");
      })
      .catch(err => {
        console.log(err);
        this.$globalErrorToaster(this.$toaster,err);
      })
      .finally(() => {
        this.isLoadingLogout = false;
      })
    }
  }
}
</script>
