<template>
	<view class="manage">
		<uni-segmented-control :current="current" :values="items" @clickItem="onClickItem" style-type="text" active-color="#F65256"></uni-segmented-control>
		<view class="content">
			<!-- <view v-show="current === 0"></view> --> 
			<view>
				<view class="one">
				  <block v-for="(item,index) of list" :key="index">
					<view class="container" v-if="item.state!=current" >
					  <view class="pic"><img :src="getimg+item.imgs" /></view>
					  <view class="desc">
						<view class="title">
						  <view class='title_l'>{{item.goods_name}}</view>
						</view>    
						<view class="price">¥ {{item.price}}</view> 
						<view class="kc">  
							<view>库存：{{item.stock}}</view>
							<view class="xiaol" >销量：{{item.sales}}</view>  
						</view>
						<view class="btn">
							<view class="btn_01" @click="jump_edit(item.goods_id)">编辑</view>
							<view class="btn_01"  @click="pull(item.goods_id)"   v-if="item.state==1">下架</view>
							<view class="btn_01"  @click="push(item.goods_id)" v-if="item.state==0">上架</view>
							<view class="btn_01" @click="del(item.goods_id)" v-if="item.state==0">删除</view>
						</view>
					  </view>
					</view>
				  </block>
				  
				  <view class="tjsp">
					 <navigator url="/pages/cms/edit/product/product"><view class="flex flex-direction">
					 	<button class="cu-btn bg-red margin-tb-sm lg">添加商品</button>
					 </view></navigator>
				  	<!-- <view class="tjsp_btn">添加商品</view> -->
				  </view>
				</view>
			</view>
			
		</view>
		
		<view style="height:100px;"></view>
	</view>
</template>

<script>
	import uniSegmentedControl from "@/components/uni/uni-segmented-control/uni-segmented-control.vue"
	export default {
		components: {uniSegmentedControl},
		data() {
			return {
				getimg:this.$getimg,
				list:[],
				items: ['上架商品','下架商品'],
				current: 0
			}
		},
		onLoad() {
			this.get_pro()
			
		},
		onShow() {
			this.get_pro()
		},
		methods: {
			get_pro(){
				this.$api.http.post('product/mcms/all_goods_info').then(res=>{
					this.list = res.data
				})
			},
			_request(){
				this.list=this.$api.json_cms.my_product
			},
			pull(id){
				const that=this
				uni.showModal({
					title:'确定下架？',
					success(res){
						if(res.confirm==true){
							that.$api.http.put('/mcms/update',{
								id:id,
								db:'goods',
								field:'state'
							}).then(res=>{
								that.$api.msg('下架成功')
								that.get_pro()
							})  
							
						}
					}
				})				
			},
			del(id){
				const that=this
				uni.showModal({
					title:'确定删除？',
					success(res){
						if(res.confirm==true){
							that.$api.http.put('product/mcms/del_product',{
								id:id,
							}).then(res=>{
								that.$api.msg('删除成功')
								that.get_pro()
							})
						}
					}
				})
			},
			push(id){
				const that=this
				uni.showModal({
					title:'确定上架？',
					success(res){
						if(res.confirm==true){
							that.$api.http.put('/mcms/update',{
								id:id,
								db:'goods',
								field:'state'
							}).then(res=>{
								that.$api.msg('上架成功')
								that.get_pro()
							})
						}
					}
				})				
			},
			jump_edit(id){
				console.log(id)
				uni.navigateTo({
					url:'/pages/cms/edit/product/product?id='+id 
				})
			},
			onClickItem(index) {
				if (this.current !== index) {
					this.current = index;
				}
			}
		},
		onPullDownRefresh() {
			this.get_pro()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
.manage{font-size:14px;
	.one{
		.container { display: flex;
			  flex-direction: row;border-bottom: 1px solid #F2F2F2;
			  justify-content: space-between;
			  height: 240rpx;
			  padding: 5px 11px;
			  margin: 0 0 10px;
			  background: #fff;
			  .pic {
				display: flex;
				flex-direction: column;
				justify-content: center;
				width: 25%;
				height: 100%;
				vertical-align: middle;
			  }
			  .pic img{
				width: 100%;
				height: 80%;
				border-radius: 10rpx;
			  }
			  .desc {
				color: #8B8B8B;
				width: 70%;
				padding: 20rpx;
				text-align: left;font-size: 12px;
				.title {display: flex;flex-wrap: wrap;}
				.title_l{font-weight: bold;color: #333;height: 36px;overflow: hidden;font-size: 14px;line-height: 18px;}
				.price{color: red;font-size: 16px;font-weight: 900;}
				.kc {
				  display: flex;
				  flex-direction: row; 
				  justify-content: space-between;
				  align-items: center;
				  line-height: 18px;
				  .xiaol { 
					color: #8B8B8B;
				  }
				}
				.btn{display: flex;justify-content: flex-end;
					.btn_01{border: 1px solid #8B8B8B;border-radius: 3px;text-align: center;margin-left: 10px;padding: 1px 8px;}
				}
			  } 
		}
		.tjsp{padding:0 10px 10px;position: fixed;bottom: 0;left:0; width: 100%;box-sizing: border-box; z-index: 99; 
		background-color: #fff;
			.tjsp_btn{background-color: #E5E5E5;
				padding: 10px;
				text-align: center;
				border-radius: 20px;
				background-color: #DF421D;
				color: #fff;
				width: 100%;}
		}
		.bg-red {
		    background-color: #e54d42;
		    color: #ffffff;
		}
		
	}
}
</style>
