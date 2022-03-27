<template>
    <tr v-if="dataLoading">
      <td colspan="100" class="text-center">

        <img
          src="/img/data-not-found.svg"
          style="height: 250px; object-fit: cover"/>

        <div class="mt-3">Loading Data ....</div>
      </td>
    </tr>
</template>

<script>
export default {
  props: {
    self: Object,

  },
  data (){
    return {
      color :'#bada55',
    }
  },
  computed : {
    dataLoading() {
      return !this.self.data.length && this.self.isLoadingData;
    },
  }
};
</script>

<style scoped>
  .loading-page {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 1000;
    padding: 1rem;
    text-align: center;
    font-size: 3rem;
    font-family: sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .loading {
    display: inline-block;
    width: 1.5rem;
    height: 1.5rem;
    border: 4px solid rgba(9, 133, 81, 0.705);
    border-radius: 50%;
    border-top-color: #158876;
    animation: spin 1s ease-in-out infinite;
  }

  @keyframes spin {
    to {
      -webkit-transform: rotate(360deg);
    }
  }

  #overlay {
    position: fixed; /* Sit on top of the page content */
    display: none; /* Hidden by default */
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5); /* Black background with opacity */
    z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
    cursor: pointer; /* Add a pointer on hover */
  }
</style>
