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
					<div class="evaluate">
						<el-button type="primary" @click="add">添加虚拟评价</el-button>
						<div style="height:20px;">&nbsp;</div>
						<div class="liat-head-03" style="margin-bottom: 20px;">
							<el-row>
								<el-input placeholder="请输入商品名或用户名" style='width: 20%' size='small' v-model="search_key"></el-input>
								<el-button style="margin-left: 10px;" type="info" size='small' @click='search(search_key)'>搜索</el-button>
								<el-button style="margin-left: 10px;" type="info" size='small' @click='reset'>重置</el-button>
							</el-row>
						</div>
						<!-- 弹窗--回复-->
						<el-dialog title="" :visible.sync="editbox" width="35%" center>
							<el-input type="textarea" :autosize="{ minRows: 3, maxRows: 4}" placeholder="请输入内容" v-model="textarea2">
							</el-input>
							<div slot="footer" class="dialog-footer" style='text-align: center'>
								<el-button @click="cancel">取 消</el-button>
								<el-button type="primary" @click="onSubmit()">确 定</el-button>
							</div>
						</el-dialog>

						<template>
							<el-table :data="evaluate" border style="width: 100%">

								<el-table-column type="index" label="序号" width="80">
								</el-table-column>
								<el-table-column prop="goods.goods_name" label="商品名称" width="450">
								</el-table-column>
								<el-table-column prop="user.nickname" label="用户名" width="160">
									<template slot-scope="scope">
										<template v-if="!scope.row.user">{{scope.row.nickname}}</template>
										<template v-else>{{scope.row.user.nickname}}</template>
									</template>
								</el-table-column>
								<el-table-column prop="content" label="评语">
								</el-table-column>
								<el-table-column prop="reply_content" label="回复">
									<template slot-scope="scope">
										<template v-if="scope.row.reply_content">{{scope.row.reply_content}}</template>
									</template>
								</el-table-column>
								<el-table-column prop="rate" label="评分" width="80">
								</el-table-column>
								<el-table-column prop="level" label="操作" width="180">
									<template slot-scope="scope">
										<template v-if="!scope.row.reply_content">
											<el-button type="success" size="small" @click="onedit(scope.row.id)">回复</el-button>
										</template>
										<el-button type="danger" size="small" @click="del(scope.row.id,scope.$index)">删除</el-button>
									</template>
								</el-table-column>
							</el-table>
							
								<el-pagination background layout="prev, pager, next" :total="total" :page-size="size" @current-change="jump_page" style="display: flex;margin-top: 20px; justify-content: center;">
								</el-pagination>
							
							<!-- <el-pagination background layout="prev, pager, next" :total="total" :page-size="size" @current-change="jump_page">
							</el-pagination> -->
						</template>
					</div>
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
				total:'',
				size:10,
				evaluate: '',
				editbox: false,
				search_key: '',
				all: '',
				textarea2: '',
				rid: ''
			}
		},
		components: {
			NavTo,
			Header
		},
		mounted() {
			this.getEvaluate();
		},
		methods: {
			onSubmit() {
				this.http.post_show('rate/admin/add_reply', {
					id: this.rid,
					reply_content: this.textarea2
				}).then(res => {
					this.$message({
						message: '回复成功',
						type: 'success'
					})
					this.textarea2 = ''
					this.editbox = false
					this.getEvaluate()
				})
			},
			cancel(){
				this.editbox = false
				this.textarea2 = ''
			},
			search(key) {
				let arr = []
				console.log(this.all)
				for (let s in this.all) {
					let k = this.all[s]
					let a = k.goods.goods_name
					if (a.indexOf(key) >= 0) {
						arr.push(k)
					}
					if (k.user) {
						if (k.user.nickname.indexOf(key) >= 0) {
							arr.push(k)
						}
					} else {
						if (k.nickname.indexOf(key) >= 0) {
							arr.push(k)
						}
					}


				}
				this.evaluate = arr
			},
			reset() {
				this.evaluate = this.all
				this.search_key = ''
			},
			add() {
				this.$router.push({
					path: './addevaluate'
				})
			},
			getEvaluate() {
				var that = this;
				//获取所有评价	
				this.http.get('rate/admin/get_all_rate')
					.then((res) => {
						that.evaluate = res.data;
						that.all = res.data
						this.total = this.all.length
						this.evaluate = this.all.slice(0,this.size)
						console.log(that.evaluate);
					});
			},

			//删除评价
			del(id, index) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('rate/admin/del_rate', {
						id: id
					}).then((res) => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						that.evaluate.splice(index, 1);
						return false;
					})
				})
			},
			//回复
			onedit(id) {
				this.rid = id
				this.editbox = true;
			},
			jump_page(e) {
				console.log(e)
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.evaluate = this.all.slice(start, end);
			},
		}
	}
</script>

<style lang="less">
	.evaluate {
		line-height: 30px;
		background-color: #fff;
		padding: 15px;
		text-align: left;
	}
</style>
