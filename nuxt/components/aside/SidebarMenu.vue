<template>
  <div>
    <ul class="sidebar-menu">

    <!-- DASHBOARD -->
    <li class="nav-item dropdown">
      <nuxt-link to="/"
        class="nav-link">
        <i class="fas fa-columns"></i>
        <span>
          Dashboard
        </span>
      </nuxt-link>
    </li>
    <!-- END DASHBOARD -->

    <template v-for="item in menus">
      <li class="menu-header"
      :key="item.id"
      v-if="item.childs.length">
        {{ item.title }}
      </li>

      <li class="nav-item dropdown"
      :key="item.id + 'child' ">    
        <nuxt-link class="nav-link" :to=" '/' + item.name + '/' + itemChild.name"     
          v-for="itemChild in item.childs"
          :key="itemChild.id">
          <i :class="'fas fa-' + itemChild.icon"></i>
          <span> {{ itemChild.title }} </span>
        </nuxt-link>
      </li>
    </template> 

    <!-- PENGATURAN -->
    <li class="menu-header">
      SETTING
    </li>

    <li class="nav-item dropdown">
      <nuxt-link class="nav-link" to="/setting/user"
        v-if="$auth.user && !$auth.user.parent_id">
        <i class="fas fa-users-cog"></i>
        <span> Akun </span>
      </nuxt-link>
      <nuxt-link class="nav-link" to="/setting/group_role"
        v-if="$auth.user && !$auth.user.parent_id">
        <i class="fas fa-users"></i>
        <span>Hak Akses Group</span>
      </nuxt-link>
      <nuxt-link class="nav-link" to="/setting/role"
        v-if="$auth.user && !$auth.user.parent_id">
        <i class="fas fa-user-cog"></i> 
        <span> Hak Akses </span>
      </nuxt-link>
      <nuxt-link class="nav-link" to="/setting"
        v-if="$auth.user && !$auth.user.parent_id">
        <i class="fas fa-cogs"></i>
        <span> Aplikasi</span>
      </nuxt-link>
      <nuxt-link class="nav-link" to="/setting/activity"
        v-if="$auth.user && !$auth.user.parent_id">
        <i class="fas fa-sun"></i>
        <span> Log Aktifitas </span>
      </nuxt-link>
      <nuxt-link class="nav-link" to="/setting/change_password">
        <i class="fas fa-key"></i>
        <span> Ganti Password </span>
      </nuxt-link>     
    </li>
    <!-- END PENGATURAN -->
  </ul>
  </div>
</template>

<script>
export default {  
  computed : {
    menus(){
      let menus = this.$store.state.setting.settings.find(item => item.name == "roles").value;            

      if(this.$auth.user.parent_id){
        let roles_id = this.$auth.user.group_role.roles.reduce((ids,item) => [...ids,item.id],[]);
        
        menus.forEach(element => {        
          element.childs = element.childs.filter(item => roles_id.includes(item.id));
        });
      }

      return menus;
    }
  }
}
</script>

<style scoped>
.sidebar-menu > .nav-item > a > span {
  font-size: 12px;
}
</style>
