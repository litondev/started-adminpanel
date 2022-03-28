<template>
  <nuxt /> 
</template>

<script>
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
}
</script>