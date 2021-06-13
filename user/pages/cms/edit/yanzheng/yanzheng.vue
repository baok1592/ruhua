<template>
  <view class="yzm">
    <view> 
      <view class="head"> 
        <view class="yzms">
          <input placeholder-style="color:#fff;font-size: 24px;" v-model="code">
          <view class="del" @click="del()">
            <img src="@/imgs/del_left.png">
          </view>
        </view>
      </view>
      <view class="shuzi">
        <view class="sz_01">
          <block v-for="item of list">
            <view class="sz_01_1" @click="write(item)">{{item}}</view>
          </block>
        </view>
        <view class="sz_01">
          <view class="sz_01_1" @click="write(0)">0</view>
          <view class="sz_01_2" @click="sub()">验&emsp;证</view>
        </view>
      </view>
    </view>
  </view>
</template>

<script> 
export default {
  data() {
    return {
      code: '',
      list: [1, 2, 3, 4, 5, 6, 7, 8, 9]
    };
  }, 
  onLoad(options){
	if(options.code){
		this.code=options.code
		this.sub()
	}
  },
  methods: {
    del() {
      let length = this.code.length;
      if (length) {
        this.code = this.code.substr(0, length - 1);
        console.log(length);
      }
    },
    write(num) {
      this.code += num;
    },
    sub() {
      if (this.code.length < 5) {
        this.$api.msg("验证码长度不对");
        return;
      }
      
        if (res.order_num) {    
          if (res.drive_status==1) {
            this.$api.msg("已验证过");  
            return;
          }else{
            let id=res.order_id
          }          
        }else{
          if(res.msg){
            this.$api.msg(res.msg);
          }else{
            this.$api.msg("验证码不存在");
          }
          return;
        }
    
    }
  },
};
</script>

<style lang="less">
.yzm {
  background-color: #f5f5f5;
  min-height: 100vh;
  padding: 0 0 1px 0;
  .del {
    padding: 10px 10px 0;
    img {
      width: 32px;
      height: 32px;
    }
  }
  .head {
    background: linear-gradient(to bottom, #52c693, #27a26c);
    padding: 30px 25px 30px;
    color: #fff;
  }
  .yz_tit {
    font-size: 16px;
    text-align: center;
  }
  .yzms {
    background-color: #1c9b62;
    border-radius: 5px;
    padding: 0px 15px;
    height: 60px;
    line-height: 60px;
    display: flex;
    justify-content: space-between;

    input {
      width: 95%;
      background-color: #1c9b62;
      color: #fff;
      height: 60px;
      font-size: 26px;
    }
  }
  .shuzi {
    padding: 20px;
  }
  .sz_01 {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
  }
  .sz_01_1 {
    background-color: #ffffff;
    height: 80px;
    width: 80px;
    border-radius: 50px;
    text-align: center;
    display: flex;
    justify-content: center;
    flex-direction: column;
    font-size: 30px;
    border: 1px solid #ececec;
    margin: 15px 10px;
  }
  .sz_01_2 {
    background-color: #36b77e;
    border-radius: 65px;
    width: 60%;
    color: #fff;
    text-align: center;
    display: flex;
    justify-content: center;
    flex-direction: column;
    font-size: 30px;
    margin: 15px 0;
  }
  input::-webkit-input-placeholder {
    color: #fff;
    font-size: 24px;
  }
}
</style>
