<template>
	<div class="black">
		<div class="container"></div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		},
		props:{
			name:String
		},
		components: {
		},
		methods:{
			
		},
		mounted() {
		}
	}
</script>

<style lang="less">
.black{
	.container {
	    background-color: #000000;
	    position: fixed;
	    top: 0;
	    opacity: 0.6;
	    width: 100%;
	    height: 100%;
	    z-index: 999;
	} 

}
</style>
