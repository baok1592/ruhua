<template>
	<div class="list">
	    <el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			
			<el-container style="" class="pro-list" >
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">
					<el-main style="background-color: #F3F3F3;">
						<list-a :down="down"></list-a>
					</el-main>
				</transition>
			</el-container>
		</el-container>  
	</div>
</template>

<script> 
	import ListA from '../../components/list/List-a.vue'
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";

	export default {
		data() {
			return {
				activeName: 'first',
				visible2: false,
				product: [],
				addShow: false,
				down: false,
				eid: 0,
				page: [],
				drawer:false
			}
		},
		components: {
			ListA,
			NavTo,
			Header
		},
		methods: {
			emit_one() {
				console.log(123)
			},
			
		},

		//vue生命函数
		mounted() {

		}
	}
</script>

<style lang="less" scoped="">
	.pro-list {
		line-height: 30px;
		text-align: left;
	}
</style>
