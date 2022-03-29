<template>
     <tbody v-if="$parent.isLoadingPage">
        <tr>
          <td colspan="100"                  
            style="height: 250px">
            <div class="d-flex align-items-center justify-content-center w-100">
              <i class="fa fa-circle-notch fa-spin fa-3x"></i>                       
            </div>
          </td>
        </tr>            
      </tbody>
</template>