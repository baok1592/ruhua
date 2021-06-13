<template>
	<view class="xieyi">
		<rich-text :nodes="content"></rich-text>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				type:'',
				content:''
			};
		},
		onLoad(options){
			this.type = options.type;
			this._load()
		},
		methods:{
			_load(){
				this.$api.http.get('index/get_file?type=' + this.type).then(res => {
					this.content = res
				})		
			}
		}
	}
</script>

<style lang="scss">
.xieyi{font-size: 14px;padding: 15px;
	.title{padding: 15px 10px;text-align: center;font-size: 18px;font-weight: 600;}
	.con{padding: 10px;}
}
</style>
