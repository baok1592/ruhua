<template>
	<div class="list">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>

			<el-container style="" class="pro-list">
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">
					
					<el-main style="background-color: #F3F3F3;">
						<el-button @click="up" type="primary" size="medium" style="margin-bottom: 20px;">获取新版本</el-button>
						<el-table :data="list" stripe style="width: 100%"> 							
							<el-table-column type="index" label="序号" width="90"></el-table-column>
							<el-table-column label="当前系统版本" width="200">
								<template slot-scope="scope">
									{{version}}
								</template>
							</el-table-column>
							<el-table-column prop="version" label="最新系统版本" width="200">
							</el-table-column>
							<el-table-column prop="time" label="发布时间" width="200">
							</el-table-column>
							<el-table-column prop="content" label="版本描述" width="600">
								<template slot-scope="scope">
									<div v-html="scope.row.content"></div>
								</template>
							</el-table-column>
							<el-table-column prop="url" label="操作">
								<template slot-scope="scope">
									<a @click="download">下载</a>
								</template>
							</el-table-column>
						</el-table>
					</el-main>
				</transition>
			</el-container>
		</el-container>
	</div>
</template>

<script>
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";	
	import {Api_url} from "@/common/config";

	export default {
		data() {
			return {
				list:[],
				version:'1.23',
				new_version:''
			}
		},
		components: {

			NavTo,
			Header
		},
		//vue生命函数
		mounted() { 
			
		},
		methods: {
			up(){  
				this.http.get('update/get_version').then(res=>{
					console.log('xx:',res)
					if(res.status==200){ 
						this.$message({
							message:'获取成功',
							type: 'success'
						});
						this.list=[res.data]
						this.new_version=res.data.version
					}else{
						this.$message({
							message:'非授权站点，请购买商业授权，避免法律纠纷',
							type: 'error',
							duration:7000
						});
						this.list=[]
					}
					console.log(this.list)
				})
			},
			download(){
				this.http.get_show('update/get_url').then(res=>{
					if(res.status!=200){
						this.$message({
							message:res.msg,
							type: 'error',
							duration:7000
						});	
						return;
					}
					const aLink = document.createElement('a');
					aLink.href = res.data.data
					aLink.target = '_blank'
					aLink.download = this.new_version+'.zip';
					aLink.click();
					aLink.remove();
				})
			}
		}

		
	}
</script>

<style lang="less" scoped="">
	.pro-list {
		line-height: 30px;
		text-align: left;
	}
</style>
