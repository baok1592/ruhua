

<template>
	<div class="user">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container style="background-color: #F3F3F3;">
				<!-- <el-header>
					<Header></Header>
				</el-header> -->

				<transition appear appear-active-class="animated fadeInLeft">
					<el-main >	
						
						<div class="article" >
							<div class="tab-btn" >
								<el-button size="medium" type="primary" @click="back">返回</el-button>
							</div>
							<template>
								<el-table :data="list" border style="width: 100%" >
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="user_name" label="姓名"></el-table-column>
									<el-table-column prop="id_card" label="身份证号"></el-table-column>
									<el-table-column prop="user_mobile" label="电话"></el-table-column>
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
	import Header from '@/components/header.vue'
	export default {
		data() {
			return {
				id:'',
				all:'',
				form:{nickname:'',goods_id:'',type:[]},
				list: [],
				size: 10,
				total: '',
				options: [],
				value: '',
			}
		},
		components: {
			Header,
			NavTo
		},
		mounted() {
			this.id=this.$route.query.id
			this.getCompany();
		},
		methods: {
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				this.list = this.all.slice(start, end);
				console.log(start, end,this.list)
			},
			//获取列表
			getCompany() {
				var that = this;
				// let loadingInstance = Loading.service({
				// 	fullscreen: true
				// }); //显示加载 
				this.http.get('worker/get_worker?pid='+this.id).then((res) => {
					//loadingInstance.close(); //关闭加载  
					this.all = res.data; 
					that.total = res.data.length;
					this.jump_page(1)
						
				});
			},
			back(){
				this.$router.push({
					path: '/yonggong?id='+this.id
				})
			},
			close_fun(done) {
				this.clear_data()
				done(); //官方实例用法
			},

			clear_data() {
				this.dialogFormVisible = false
			},
		}
	}
</script>

<style lang="scss">
.tab-btn{text-align: left;margin-bottom: 15px;}
.list-head {
		padding-bottom: 10px;
		display: flex;
	}

	.liat-head-02 {
		font-size: 14px;
		padding-right: 10px
	}
	.user {background-color: #F3F3F3; 
		.el-table__row{
			line-height: 40px  !important;
			img{
				width:50px !important;
				height:50px !important;
			}
		} 
		.el-main {
			height: auto !important;
			background-color: #F3F3F3;padding:15px;
			.el-table {
				height: auto !important;
			}

			.el-table__body-wrapper,
			.is-scrolling-none,
			.el-table__body {
				height: auto !important;
			}
		}
		.user_sear{line-height: 40px;text-align: left;color:#6B6B6B;font-size: 14px;padding:15px 15px 10px;border-top: 1px solid #F0F0F0;
			.sear_01{display: flex;margin-bottom:20px;
				.sear_01_01{display: flex;margin-right:30px;
					.sear_01_01_1{flex-shrink: 0;}
					.el-input__inner{width:200px;}
				}
			}
			.sea_02_02_r_2_1{background-color: #008DE1;color:#fff;padding:0 10px;margin-right:10px;border-radius: 3px;width:45px;}
		}
		.article {background-color: #fff;padding:15px;
			line-height: 30px;min-height: 650px;
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
