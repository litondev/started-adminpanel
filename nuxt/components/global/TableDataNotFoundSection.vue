<template>
  <tbody v-if="!$parent.isLoadingPage && !$parent.items.data.length">
    <tr>
      <td colspan="100"
        class="text-center">
        <img
            src="/img/data-not-found.svg"
            style="height: 250px; object-fit: cover"
        />
        <div class="mt-3 mb-3">Data Tidak Ditemukan</div>
      </td>
    </tr>            
  </tbody>
</template>