<template>
	<view class="invoices">
		<view class="title">增值税电子普通发票</view>
		<view class="ttlx">
			<view class="ttlx-l">抬头类型</view>
			<view class="ttlx-r">
				<view :class="fapiao==1?'ttlx-r-1':'ttlx-r-2'" @click="fapiao=1">不开发票</view>
				<view :class="fapiao==2?'ttlx-r-1':'ttlx-r-2'" @click="fapiao=2">个人</view>
				<view :class="fapiao==3?'ttlx-r-1':'ttlx-r-2'" @click="fapiao=3">单位</view>
			</view>
		</view>
		<view v-if="fapiao!=1">
			<view class="ttlx" v-if="fapiao==3">
				<view class="ttlx-l">发票抬头</view>
				<view class="ttlx-m">
					<input placeholder="请填写单位名称" />
				</view>
			</view>
			<view class="ttlx" v-if="fapiao==3">
				<view class="ttlx-l">税号</view>
				<view class="ttlx-m">
					<input placeholder="请填写纳税人识别号" />
				</view>
			</view>
			<view class="ttlx">
				<view class="ttlx-l">发票内容</view>
				<view class="ttlx-m">
					<!-- 商品明细<span>(详细商品名称和价格)</span> -->
					<picker @change="bindPickerChange" :value="index" :range="array">
						<view >{{array[index]}}</view>
					</picker>
					<text class="yticon icon-you" ></text>
				</view>
			</view>
			<view class="ttlx">
				<view class="ttlx-l">电子邮件</view>
				<view class="ttlx-m">
					<input placeholder="请填写接受电子发票的邮箱" />
				</view>
			</view>
		</view>
		<view class="B10" v-if="fapiao!=1"></view>
		<view class="fpxz" v-if="fapiao!=1">
			
			<view class="fpxz-tit">发票须知</view>
			1、电子发票与纸质发票具备同等法律效力，支持报销入账。<br/>
			2、开票金额按订单实付计。<br/>
			3、订单完成后可开具发票。<br/>
			<span v-if="fapiao==3">4、请确保抬头和纳税人识别号或统一社会信用代码准确，开票成功后无法更改。</span>
		</view>
		<view class="btn" @click="msg">确定</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				fapiao:1,
				array: ['商品明细(请选择)', '美国', '巴西', '日本'],
				index: 0,
			};
		},
		methods:{
			msg(){
				this.$api.msg('开发中...')
			},
			bindPickerChange: function(e) {
				console.log('picker发送选择改变，携带值为', e.target.value)
				this.index = e.target.value
			},
		}
	}
</script>

<style lang="scss">
.invoices{font-size: 14px;
	.title{padding: 20px 10px 10px;font-size: 16px;}
	.ttlx{display: flex;border-bottom: 1px solid #F6F6F6;padding: 10px 10px;
		.ttlx-l{width: 70px;padding: 4px 0;}
		.ttlx-r{display: flex;
			.ttlx-r-1{border: 1px solid #EF5C80;color: #EF5C80;padding: 5px 10px;border-radius: 3px;margin-right: 10px;
			min-width: 60px;text-align: center;}
			.ttlx-r-2{border: 1px solid #E0E0E0;padding: 5px 10px;border-radius: 3px;margin-right: 10px;min-width: 60px;
			text-align: center;}
		}
		.ttlx-m{padding: 4px 0;flex-grow: 1;display: flex;justify-content: space-between;
			span{color: #888888;}
			input{width: 100%;}
		}
	}
	.fpxz{padding: 10px;color: #7D7D7D;font-size: 12px;line-height: 25px;
		.fpxz-tit{padding: 10px 0 5px;color: #000;font-size: 14px;}
	}
	.B10{height: 10px;background-color: #F5F5F5;}
	.btn{position: fixed;bottom: 0;left: 0;margin: 10px 3%;background-color: #E64340;width: 94%;color: #fff;height: 40px;
	line-height: 40px;border-radius: 3px;text-align: center;}
}
</style>
