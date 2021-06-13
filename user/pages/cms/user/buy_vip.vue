<template>
	 
	<view class="head">
		<view class="head_l">
			<view class="head_l_01"><span><uni-icon type="star-filled" size="15" color="#BBA176"></uni-icon></span>某某会员</view>
			<view class="head_l_02">优享会员 享受 7 大特权</view>
		</view>
		<view class="head_r">立即开通</view>
	</view>
	<view class="teq"><img src="../../imgs/3.jpg" /></view>
	<view class="tequan">
		<view><Titlec title="会员专属特权"></Titlec ></Titlec></view>
		<template v-for="item of list"> <Listd></Listd></template>
	</view>
	<view class="tequan">
		<view><Titlec title="会员任务红包"></Titlec ></Titlec></view>
		<view class="t_tie">做专属任务，领取专属红包</view>
		<view class="hongbao">
			<view class="hb_01">
				<view class="hb_01_1"><img src="../../imgs/6.jpg" /></view>
				<view class="hb_01_2">已完成</view>
			</view>
			<view class="hb_02">——</view>
			<view class="hb_01">
				<view class="hb_01_1"><img src="../../imgs/6.jpg" /></view>
				<view class="hb_01_2">已完成</view>
			</view>
			<view class="hb_02">——</view>
			<view class="hb_01">
				<view class="hb_01_1"><img src="../../imgs/6.jpg" /></view>
				<view class="hb_01_2">已完成</view>
			</view>
			<view class="hb_02">——</view>
			<view class="hb_01">
				<view class="hb_01_1"><img src="../../imgs/6.jpg" /></view>
				<view class="hb_01_2">已完成</view>
			</view>
		</view>
		<view class="t_btn">立即参与</view>
	</view>
	<view class="tequan">
		<Titlec title="专人专线客服"></Titlec ></Titlec>
		<view class="kf"><img src="../../imgs/1.jpg" /></view>
	</view>
</template>

<script>
</script>

<style>
</style>
