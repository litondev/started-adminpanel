const webpack = require("webpack");
const fs = require('fs') ;

require('dotenv').config();

export default {
  ssr: false,

  server: {
    host: process.env.HOST,
    port : process.env.PORT
  },

  head: {
    title: 'Started Admin Panel',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],        
    link: [
      {rel: 'icon',type: 'image/x-icon',href: (process.env.LOGO_URL || '/img/') + 'default.jpeg'},
      {rel: 'stylesheet',href : '/dists/css/tabler.min.css'},
      {rel: 'stylesheet',href : '/dists/css/tabler-flags.min.css'},
      {rel: 'stylesheet',href : '/dists/css/tabler-payments.min.css'},
      {rel: 'stylesheet',href : '/dists/css/tabler-vendors.min.css'},
      {rel: 'stylesheet',href : '/dists/css/fontawesome/css/all.min.css'},
      {rel: 'stylesheet',href : '/dists/css/demo.min.css'}
    ],
    script: [
      { src: "/dists/js/tabler.min.js"},           
    ],
  },

  loading: {
    color: 'blue',
    height: '2px',
  },

  plugins: [
    '~/plugins/nuxt-client-init',
    "~/plugins/vee-validate",
    '~/plugins/toaster',
    '~/plugins/v-money',
    '~/plugins/vue-loading-overlay',
    '~/plugins/swal',
    
    '~/plugins/global-filters',
    '~/plugins/global-components',
  ],

  components: true,

  modules: [
    [
      '@nuxtjs/dotenv',{ 
        systemvars: false
      }
    ],
    'portal-vue/nuxt',
    '@nuxtjs/axios',
  ],

  axios: {
    baseURL: process.env.API_URL
  }
}