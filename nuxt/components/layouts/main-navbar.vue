<template>  
  <ul class="navbar-nav">
    <li class="nav-item">
      <nuxt-link 
        class="nav-link" 
        to="/">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
          <i class="fa fa-home"></i>
        </span>
        <span class="nav-link-title">
          Home
        </span>
      </nuxt-link>
    </li>

    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" 
        href="#navbar-extra" 
        data-bs-toggle="dropdown" 
        data-bs-auto-close="outside" 
        role="button" 
        aria-expanded="false" >
        <span class="nav-link-icon d-md-none d-lg-inline-block">
          <i class="fa fa-th"></i>
        </span>
        <span class="nav-link-title">
          Master
        </span>
      </a>
      <div class="dropdown-menu">
        <nuxt-link class="dropdown-item"
          to="/master/user">
          User
        </nuxt-link>
        <nuxt-link class="dropdown-item" 
          to="/master/product">
          Produk
        </nuxt-link>
        <nuxt-link class="dropdown-item" 
          to="/master/customer">
          Pelanggan
        </nuxt-link>                  
      </div>
    </li>              

    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" 
        href="#navbar-extra" 
        data-bs-toggle="dropdown" 
        data-bs-auto-close="outside" 
        role="button" 
        aria-expanded="false" >
        <span class="nav-link-icon d-md-none d-lg-inline-block">
         <i class="fa fa-dollar-sign"></i>
        </span>
        <span class="nav-link-title">
          Transaksi
        </span>
      </a>
      <div class="dropdown-menu">                   
        <nuxt-link class="dropdown-item" 
          to="/transaction/product">
          Produk
       </nuxt-link>           
      </div>
    </li>       
  </ul>    
</template>