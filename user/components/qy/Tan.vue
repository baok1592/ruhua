<template>
	<view class="AddTan">
		<view class='' v-if="add">
		    <view class="two" >
				<view class='two_con'><img src="../../imgs/12.png"></img></view>
		      <view  class='t_tit'>恭喜抢单成功</view>
		      <view class='t_btn'>
				  <view class="t_btn_l">取消</view>
				  <view class="t_btn_r">确定</view>
			  </view>
		      <view class='two_close' @click="close_add"><uni-icon type="close" size="30" color="#fff"></uni-icon></view>
		    </view>
		    <Black></Black>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import Black from './Black'
	export default {
		data() {
			return {
				add:true
			};
		},
		props:{
			name:String
		},
		components: {
			Black,
			uniIcon
		},
		methods:{
			close_add(){
				this.$emit("close_add");
			}
		},
		mounted() {
		}
	}
</script>

<style lang="less">
.AddTan{
	.two{position: fixed;top: 100px;left: 8%;width: 84%;border-radius: 15px;background-color: #fff;box-sizing: border-box;
	z-index: 10001;text-align: center;padding: 45px 0 35px;}
	.t_tit{font-size: 16px;text-align: center;padding: 15px 10px 40px;}
	.two_con img{width: 70px ;height: 70px;margin-bottom: 10px}
	.t_btn{display: flex;justify-content: space-around;}
	.t_btn_l{background-color: #BFBCBC;color: #fff;padding: 13px 0px;border-radius: 20px;width: 40%;}
	.t_btn_r{background-color: #E0451D;color: #fff;padding: 13px 0px;border-radius: 20px;width: 40%;}
	.two_close{position: absolute;bottom: -60px;left: 42%;color: #BDB4AD;}

}
</style>
