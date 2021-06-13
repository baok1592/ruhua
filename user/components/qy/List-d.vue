<template>
  <div id="list-d"> 
        <div class="con_01">
          <div class='picss'> 
						<img src='@/imgs/9.jpg'></img>
          </div> 
          <div class='con_01_de'>123阿萨德会撒娇的爱神的箭好安顺角度看来</div>
          <div class='con_01_pri'>¥123</div>
        </div> 
  </div>
</template>

<script>
export default {
  name: 'List-d', 
  props: ['info','ucid'], 
  data() {
    return {
      id:""
    } 
  },
   mounted(){      
  }
}
</script>

<style lang="less">
#list-d{
  width: 30%;padding: 15px 1.5%;display: inline-block;
  .con_01{background-color:#fff;}
  .con_01 img{width: 100%;height: 90px;margin-bottom: 5px;}
  .con_01_time{color: #FC455E;font-size: 10px;border:1px solid #FC455E;display: inline;padding: 1px 5px;}
  .con_01_tit{height: 20px;overflow: hidden;line-height: 20px;font-size: 14px;padding-top: 3px;}
  .con_01_de{max-height: 36px;line-height: 18px;overflow: hidden;font-size: 10px;}
  .con_01_pri{color: #FC455E;}

  .blue{
    .con_01_time{color: #2A82E4;border:1px solid #2A82E4;}
    .con_01_pri{color: #2A82E4;}
  }
  .green{
    .con_01_time{color: #34B07A;border:1px solid #34B07A;}
    .con_01_pri{color: #34B07A;}
  }
  .pink{
    .con_01_time{color: #FF729A;border:1px solid #FF729A;}
    .con_01_pri{color: #FF729A;}
  }
  .orange{
    .con_01_time{color: #FF8D1B;border:1px solid #FF8D1B;}
    .con_01_pri{color: #FF8D1B;}
  }

  .picss{position: relative;}
  .maiguang{position: absolute;top: 50%;left: 50%;z-index: 199;width: 100px;height: 100px;margin:-50px 0 0 -50px;}
  .maiguang img{width: 100px;height: 100px;}
  .containerss{
      background-color:#000;
      opacity: 0.3;
      width: 100%;
      height: 100%;
      z-index: 99;
      position: absolute;top: 0;left: 0;
  } 
}
   
</style>