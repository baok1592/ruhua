<template>
	<view class="article">
		<view class="tit">{{art.title}}</view>
		<view class="time"><!-- {{art.create_time}} --></view>
		
		<view class="content">
			<rich-text :nodes="art.content"></rich-text>
			
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				art:'',
			};
		},
		onLoad(options) {
			this.get_art_detail(options.id)
		},
		methods:{
			jump(){
				
			},
			get_art_detail(id){
				this.$api.http.get('article/get_one_article?id='+ id).then(res=>{
					this.art = res.data
				})
			}
		},
		onPullDownRefresh() {
			//this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
.article{padding: 20px 10px;font-size: 14px;
	.tit{text-align: center;font-size: 16px;}
	.time{padding: 5px 0 15px;text-align: center;color: #D1D1D1;}
}
</style>
