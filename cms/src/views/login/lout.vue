<template>
	<div>
		 
	</div>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		},
		mounted() {
			localStorage.clear();               
			let ht=document.location.protocol;
			let host=document.location.host
			let ww=ht+'//'+host;
			window.location.href=ww; 
		}
	}
</script>

<style>

</style>
