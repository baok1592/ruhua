<template>
	<div class="root">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					
				</el-main>
			</el-container>
		</el-container>
	</div>
</template>

<script>
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		data(){
			return{
				
			}
		},
		components: {
			NavTo,
			Header
		},
		mounted() {
			
		},
		methods:{
			
		}
	}
	
</script>

<style>
</style>
