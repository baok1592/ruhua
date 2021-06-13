<template>
	<div class="reseller_order">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<transition appear appear-active-class="animated fadeInLeft">
						<el-table :data="tableData" border style="width: 100%">
							<el-table-column type="index" label="序号">
							</el-table-column>
							<el-table-column prop="order_num" label="订单号">
							</el-table-column>
							<el-table-column label="购买类型">
								<template slot-scope="scope">
									<div v-if="scope.row.type==1">会员</div>
									<div v-if="scope.row.type==2">商品</div>
								</template>
							</el-table-column>
							<!-- <el-table-column prop="region" label="地区">
								<template slot-scope="scope" v-if="scope.row.region">
									{{region_list[scope.row.region]['region_name']}}
								</template>
							</el-table-column> -->
							<el-table-column label="用户昵称">
								<template slot-scope="scope" v-if="scope.row.user">
									{{scope.row.user.nickname}}
								</template>
							</el-table-column>
							<el-table-column label="用户手机号">
								<template slot-scope="scope" v-if="scope.row.user">
									{{scope.row.user.mobile}}
								</template>
							</el-table-column>
							<el-table-column prop="agent.nickname" label="代理商昵称">
							</el-table-column>
							<el-table-column label="代理商手机号">
								<template slot-scope="scope">
									{{scope.row.agent.mobile}}
								</template>
							</el-table-column>
							<el-table-column label="推荐人佣金">
								<template slot-scope="scope">
									{{scope.row.money}}
								</template>
							</el-table-column>
							<el-table-column label="反佣状态">
								<template slot-scope="scope">
									{{scope.row.status == 0?'未返':'已返'}}
								</template>
							</el-table-column>
						</el-table>

					</transition>
				</el-main>
			</el-container>
		</el-container>
	</div>
</template>
<script>
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		data() {
			return {
				tableData:''
			}
		},

		components: {
			NavTo,
			Header
		},
		mounted() {
			this._load()
		},
		methods: {
			_load(){
				this.http.get('fx/admin/get_record').then(res=>{
					this.tableData = res.data
				})
			}
		}
	}
</script>



<style lang="less">
	/* <style>   */
	.reseller_order {}
</style>
