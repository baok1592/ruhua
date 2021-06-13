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
					<div class="article">
						<el-button type="primary" @click="add">新建运费模板</el-button>
						<div style="height:20px;">&nbsp;</div>
						<template>
							<el-table :data="list" border style="width: 100%">
								<el-table-column type="index" label="序号" width="50px"></el-table-column>
								<el-table-column prop="name" label="可配送区域"></el-table-column>
								<el-table-column prop="rule[0].first" label="首件（个）"></el-table-column>
								<el-table-column prop="rule[0].first_fee" label="运费（元）"></el-table-column>
								<el-table-column prop="goods_ids" label="续件（个）"></el-table-column>
								<el-table-column prop="full" label="续费（元）"></el-table-column>
								<el-table-column prop="operation" label="操作" width="300px">
									<template slot-scope="scope">
										<!-- <el-button @click="edit(scope.row)" type="success" size="small">修改</el-button> -->
										<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">删除</el-button>
									</template>
								</el-table-column><strong></strong>
							</el-table>
						</template>
					</div>
					<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="total" :page-size="size"
					 @current-change="jump_page">
					</el-pagination>
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
				size: 10,
				total: '',
				list: '',
			}
		},
		components: {
			NavTo,
			Header
		},
		mounted() {
			this.get_template();
		},
		methods: {
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.list = this.all.slice(start, end);
			},
			//获取
			get_template() {
				this.http.get('delivery/admin/get_delivery').then(res => {
					this.list = res.data
				})
			},
			//删除优惠券
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('delivery/admin/del_delivery', {
						id: id
					}).then(res => {
						if (res.status == 200) {
							that.$message({
								showClose: true,
								message: '删除成功',
								type: 'success'
							});
						} else {
							that.$message({
								showClose: true,
								message: res.msg,
								type: 'error'
							});
						}
						this.get_template()
						// that.list.splice(index, 1);
					});
				})
			},
			add() {
				this.$router.push({
					path: './addtemplate'
				})
			},
			edit(item) {
				localStorage.setItem("edit_data", JSON.stringify(item))
				this.$router.push({
					path: './edittemplate',
					query: {
						key: 2
					}
				});
			},
		}
	}
</script>

<style>
	.article {
		line-height: 30px;
		background-color: #fff;
		padding: 15px;
		text-align: left;
	}
</style>
