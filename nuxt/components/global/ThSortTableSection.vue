<template>
 <th @click="$parent.onSort(field,$parent.parameters.params.sort == 'asc' ? 'desc' : 'asc')"
	class="cursor-pointer">             
	 <div class="d-flex flex-row justify-content-between align-items-baseline">
	  <div>{{title}}</div>
	  <div>
	    <i class="fas fa-caret-up"
	      :class="$parent.parameters.params.order == field && $parent.parameters.params.sort == 'asc' ? '' : 'light-gray'"></i>
	    <i class="fas fa-caret-down"
	      :class="$parent.parameters.params.order == field && $parent.parameters.params.sort == 'desc' ? '' : 'light-gray'"></i>
	  </div>
	</div>
 </th>
</template>

<script>
export default {
	props : ['field','title']
}
</script>