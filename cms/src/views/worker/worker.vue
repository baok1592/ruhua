<template>
	<div class="user">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container style="background-color: #F3F3F3;">
				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<div class="list-head">
							<div class="liat-head-02">
								<el-input placeholder="名字、电话" style='width: 98%' size='small' v-model="value"></el-input>
							</div>
							<div class="liat-head-03">
								<el-row>
									<el-button type="info" size='small' @click='serach(value)'>搜索</el-button>
									<el-button type="info" size='small' @click='reset'>刷新</el-button>
								</el-row>
							</div>
						</div>
						<div class="article">

							<template>
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="user_name" label="姓名"></el-table-column>
									<el-table-column prop="id_card" label="身份证号"></el-table-column>
									<el-table-column prop="user_mobile" label="联系电话"></el-table-column>
									<el-table-column prop="headpic" label="身份证照片">
										<template slot-scope="scope">
											<template v-for="item of scope.row.id_card_url">
												<a :href="url+item" target="_blank"><img class="headpic" :src="url+item" /></a>&nbsp;
											</template>
										</template>
									</el-table-column>
									<el-table-column prop="bank_name" label="开户行"></el-table-column>
									<el-table-column prop="bank_card" label="银行卡号"></el-table-column>
									<el-table-column prop="status" label="状态">
										<template slot-scope="scope">
											{{scope.row.status == 1?'已审核':'待审核'}}
										</template>
									</el-table-column>
									<el-table-column label="操作">
										<template slot-scope="scope">
											<el-button v-if="scope.row.status == 0" type="success" @click="pass(scope.row.id)">通过</el-button>
										</template>
									</el-table-column>
								</el-table>
							</template>

						</div>
						<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="total" :page-size="size"
						 @current-change="jump_page">
						</el-pagination>
					</el-main>
				</transition>
			</el-container>
		</el-container>
	</div>

</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import {
		Api_url
	} from "@/common/config";

	import NavTo from '@/components/navTo.vue'
	export default {
		data() {
			return {
				value:'',
				list: [],
				all:'',
				url:this.img_url,
			}
		},
		components: {
			NavTo
		},
		mounted() {
			this._load()
		},
		methods: {
			_load() {
				this.http.get('worker/get_all_worker').then(res => {
					this.list = res.data
					this.all = res.data
				})
			},
			pass(id) {
				const that = this
				this.$confirm('确定通过审核', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					that.http.post('worker/examine_worker', {
						id: id
					}).then(res => {
						this.$message({
							type: 'success',
							message: '通过审核!'
						});
						this._load()
					})
				})
			},
			serach(key){
				let arr = []
				for (let k in this.all) {
					let v = this.all[k]
					if(v.user_name.indexOf(key) >= 0){
						arr.push(v)
					}
					if(v.user_mobile.indexOf(key) >= 0){
						arr.push(v)
					}
				}
				this.list = arr
				this.value = ''
			},
			reset(){
				this.value = ''
				this._load()
			}
		}
	}
</script>

<style lang="scss">
	.list-head {
		padding-bottom: 10px;
		display: flex;
	}

	.liat-head-02 {
		font-size: 14px;
		padding-right: 10px
	}

	.user {
		background-color: #F3F3F3;

		.el-table__row {
			line-height: 40px !important;

			img {
				width: 50px !important;
				height: 50px !important;
			}
		}

		.el-main {
			height: auto !important;
			background-color: #F3F3F3;
			padding: 15px;

			.el-table {
				height: auto !important;
			}

			.el-table__body-wrapper,
			.is-scrolling-none,
			.el-table__body {
				height: auto !important;
			}
		}

		.user_sear {
			line-height: 40px;
			text-align: left;
			color: #6B6B6B;
			font-size: 14px;
			padding: 15px 15px 10px;
			border-top: 1px solid #F0F0F0;

			.sear_01 {
				display: flex;
				margin-bottom: 20px;

				.sear_01_01 {
					display: flex;
					margin-right: 30px;

					.sear_01_01_1 {
						flex-shrink: 0;
					}

					.el-input__inner {
						width: 200px;
					}
				}
			}

			.sea_02_02_r_2_1 {
				background-color: #008DE1;
				color: #fff;
				padding: 0 10px;
				margin-right: 10px;
				border-radius: 3px;
				width: 45px;
			}
		}

		.article {
			background-color: #fff;
			padding: 15px;
			line-height: 30px;
			min-height: 650px;
			text-align: left;
		}

		.list-head {
			padding-bottom: 10px
		}

		.el-form-item__content {
			display: flex;
			justify-content: flex-start;
		}
	}
</style>
