<template>
  <nuxt /> 
</template>

<script>
export default {
  created(){
    let myScript = [
      "/js/stisla.js",
      "/js/scripts.js",
    ];

    myScript.forEach((item,index) => {      
      let script = document.createElement('script')
      script.setAttribute('src', item)
      document.body.appendChild(script)    
    });
  }
}
</script>