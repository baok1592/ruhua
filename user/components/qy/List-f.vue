<template>
  <view class="list-f" >
        <view class='f_01' >
          <view class='f_01_1'>
            <!-- <img :src='info.imgs'> -->
						<img src='@/imgs/detail.jpg'></img>
            <view class='f_01_1_f'>积分抵扣20元</view>
          </view>
          <view class='f_01_2'><!-- {{info.goods_name}} -->11</view>
          <view class='f_01_3'><!-- {{info.description}} -->12</view>
          <view class='f_01_4'>¥<!-- {{info.price}} -->23</view>
        </view>
  </view>
</template>

<script>
export default {
  name: 'List-f', 
  props: ['info'], 
  data() {
  	return {
  		id:""
  	}	
  }
}
</script>

<style lang="less">
.list-f{
  margin: 5px 2%;border-radius: 8px;padding-bottom: 10px;box-shadow: 4px 4px 5px #DEDEDE;display: inline-block;background-color: #fff;width: 46%;
  .f_01{font-size: 12px;}
  .f_01_1{position: relative;}
  .f_01_1 img{width: 100%;height: 180px;border-top-left-radius: 8px;border-top-right-radius: 8px;}
  .f_01_1_f{position: absolute;bottom: -5px;left: 14px;background-color: #FB5F52;color: #FBE7CF;font-size: 10px;padding: 3px 5px;border-top-right-radius: 8px;border-bottom-left-radius: 8px;}
  .f_01_2{margin: 8px 10px 0px;height: 18px;overflow: hidden;line-height: 18px;}
  .f_01_3{padding: 0 10px;height: 18px;color: #9D9B9B;overflow: hidden;line-height: 16px;}
  .f_01_4{padding: 0 10px;color: #ED6657;}

  .blue{
    .f_01_1_f{background-color: #2A82E4;}
    .f_01_4{color: #2A82E4 ;}
  }
  .green{
    .f_01_1_f{background-color: #34B07A ;}
    .f_01_4{color: #34B07A  ;}
  }
  .pink{
    .f_01_1_f{background-color: #FF729A  ;}
    .f_01_4{color: #FF729A;}
  }
  .orange{
    .f_01_1_f{background-color: #FF8D1B   ;}
    .f_01_4{color: #FF8D1B ;}
  }
  
}
   
</style>