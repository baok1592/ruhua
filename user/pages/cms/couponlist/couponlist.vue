<template>
	<view class="coupon">
		<view class='po'>
			<!-- <view class='tab'>
				<view :class="c_index==0?'xz':'bb' " @click="num(0)">店铺优惠券</view>
				<view :class="c_index==1?'xz':'bb' " @click="num(1)">VIP特权券</view>
			</view> -->
			<None v-if="list_empty"></None>
			<view v-for="(item,index) of list" :key="index" v-else>
				<block v-if="state == 0">
					<view class='coupon' v-if="item.type == 3">
						<block v-if="time_over(item.end_time)">
							<view class="ysy"><img src="@/imgs/late.png"></img></view>
						</block>
						<view class='cou_t'>
							<view class='cou_t_l'>
								<view :class='class_name'><span>¥</span>{{item.reduce}}</view>
							</view>

							<view class='cou_t_r'>
								<block v-if="item.full == 0">
									<view class='cou_t_r_01'>{{item.name}}</view>
								</block>
								<block v-else>
									<view class='cou_t_r_01'>满{{item.full}}减{{item.reduce}}</view>
								</block>

								<view class='cou_t_r_02' v-if="item.day">有效期：{{item.day}}天</view>
								<view class='cou_t_r_02' v-if="item.start_time||item.end_time">有效期：{{item.start_time}}至{{item.end_time}}</view>
							</view>
							<view class="cou_t_rr" v-if="item.is_show == 0" @click="del_coupon(item.id)">删除</view>
						</view>
						<view class='cou_d'>描述信息</view>
					</view>
				</block>

				<!-- VIP-----------------------------------------------------------------------特权券  -->
				<block v-if="state == 1">
					<view class='coupon' v-if="item.type == 2">
						<view class='cou_t'>
							<view class='cou_t_l'>
								<view :class='class_name'><span>¥</span>{{item.reduce}}</view>
							</view>
							<view class='cou_t_r'>
								<block v-if="item.full == 0">
									<view class='cou_t_r_01'>{{item.name}}</view>
								</block>
								<block v-else>
									<view class='cou_t_r_01'>满{{item.full}}减{{item.reduce}}</view>
								</block>
								<view class='cou_t_r_02'>
									<view class='cou_t_r_02' v-if="item.day" style="display: flex;justify-content: space-between;width: 80%;">有效期：{{item.day}}天
										<view v-if="item.is_show == 0">待审核</view>
										<view v-if="item.is_show == 1">已入选</view>
										<view v-if="item.is_show == 2">等待撤销</view>
									</view>
								</view>
							</view>
							<view class="cou_t_rr" v-if="item.is_show == 0" @click="del_coupon(item.id)">删除</view>
							<view v-if="state==1">
								<view class="btn_grey" v-if="item.is_show == 1" @click="del_coupon(item.id)">撤销</view> 
								<view class="no-btn"  v-if="item.is_show == 2">撤销中...</view>
							</view>
						</view>
						<view class='cou_d'>描述信息</view>
					</view>
				</block>



			</view>
			<view class="H50"></view>
			<view class="coupon_btn">
				<navigator url="/pages/cms/coupon/coupon"><view class=" flex flex-direction">
					<button class="cu-btn bg-red margin-tb-sm lg">添加店铺优惠券</button>
				</view></navigator>
			</view>
		</view>
	</view>
</template>

<script>
	import None from "@/components/qy/none.vue"
	export default {
		components: {
			None
		},
		data() {
			return {
				btn_name: 'cou_t_rr',
				c_index: 0,
				list: [],
				class_name: 'cou_t_l_01',
				state: 0,
				list_empty: false,
				is_over:2
				
			};
		},
		onLoad() {
			this.get_all_coupon()
		},
		methods: {
			time_over(time) {
				if(!time){
					return false;
				}
				let a=new Date(time).getTime()
				let b=new Date().getTime()
				console.log(a)
				console.log(b)
				if(b>a){
					return true;
				} 
			},
			get_all_coupon() {
				this.list = this.$api.json_cms.get_coupon
			},
			del_coupon(id) {
				const that = this
				uni.showModal({
					title: '提示',
					content: '确认操作？',
					success: function(res) {
						if (res.confirm) {
							console.log('用户点击确定');
							that.$api.msg('操作成功')
							that.get_all_coupon()
						} else if (res.cancel) {
							console.log('用户点击取消');
						}
					}
				});
			},
			num(index) {
				if (index == 0) {
					this.c_index = index
					this.class_name = "cou_t_l_01"
					this.state = 0
				}
				if (index == 1) {
					this.c_index = index
					this.class_name = "cou_t_l_01"
					this.state = 1
				}
				if (index == 2) {
					this.c_index = index
					this.class_name = "cou_sss"
					this.state = 2
				}
			},

		},
		onPullDownRefresh() {
			this.get_all_coupon()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="scss">
	.coupon {
		background-color: #F8F8F8;
		min-height: 100vh;padding-top: 1px;

		.po {
			z-index: 99;
			width: 100%;
		}

		.tab {
			padding: 10px 10px 0;
			display: flex;
			width: 100%;
			font-size: 14px;
			background-color: #fff;
		}

		.bb {
			padding-bottom: 5px;
			text-align: center;
			width: 50%;
		}

		.xz {
			border-bottom: 2px solid red;
			padding-bottom: 5px;
			width: 50%;
			text-align: center;
		}

		.coupon {
			margin: 15px;
			background-color: #fff;
			border: 1px solid #EEEEEE;
			border-radius: 5px;
			box-shadow: 2px 2px 2px #EEEEEE;
			color: #9FA0A2;
			min-height: 10px;
			position: relative;

			.ysy {
				position: absolute;
				right: 10px;
				top: 0;

				img {
					width: 80px;
					height: 80px;
					z-index: 99;
					margin-right: 100px;
				}
			}
		}

		.cou_t {
			display: flex;
			padding: 20px 10px 10px;
			justify-content: space-between;
			font-size: 12px;
			width: 100%;
			box-sizing: border-box;
		}

		.cou_t_l {
			width: 20%;
			flex-shrink: 0;
		}

		.cou_t_r {
			width: 55%;
			box-sizing: border-box;
			padding-left: 10px;
			flex-grow: 1;
		}

		.cou_t_rr {
			width: 70px;
			background-color: #EB113E;
			color: #fff;
			height: 25px;
			line-height: 25px;
			font-size: 12px;
			text-align: center;
			margin: 15px 0 0 10px;
			border-radius: 20px;
		}
		.no-btn{
			width: 70px;  
			height: 25px;
			line-height: 25px;
			font-size: 12px;
			text-align: center;
			margin: 15px 0 0 10px; 
		}
		.btn_grey {
			width: 70px;
			background-color: #9FA0A2;
			color: #fff;
			height: 25px;
			line-height: 25px;
			font-size: 12px;
			text-align: center;
			margin: 15px 0 0 10px;
			border-radius: 20px;
		}

		.cou_t_l_01 {
			color: #FF4444;
			font-size: 26px;
			padding-top: 8px;
		}

		.cou_t_l_01 span {
			font-size: 12px;
		}

		.cou_sss {
			font-size: 26px;
			padding-top: 8px;
		}

		.cou_sss span {
			font-size: 12px;
		}

		.cou_t_r_01 {
			font-size: 18px;
			color: #323233;
			padding: 3px 0 5px;
		}

		.cou_d {
			background-color: #FAFAFA;
			padding: 6px 15px;
			border-top: 1px dashed #EBEDF0;
			font-size: 12px;
		}

		.H50 {
			height: 50px;
		}

		.coupon_btn {
			padding:0 10px;
			position: fixed;
			bottom: 0;
			z-index: 999;
			background-color: #F8F8F8;
			width: 100%;
			box-sizing: border-box;

			.btn {
				text-align: center;
				font-size: 16px;
				height: 45px;
				line-height: 45px;
				background-color: #EB113E;
				color: #FFFFFF;
				border-radius: 5px;
			}
		}

	}
</style>
