<template>
  <aside id="sidebar-wrapper">
    <app-sidebar-brand></app-sidebar-brand>
    <app-sidebar-menu></app-sidebar-menu>
  </aside>
</template>
<script>
import SidebarBrand from '@/components/aside/SidebarBrand.vue'
import SidebarMenu from '@/components/aside/SidebarMenu.vue'
export default {
  components : {
    'app-sidebar-menu' : SidebarMenu,
    'app-sidebar-brand' : SidebarBrand,
  },
}
</script>