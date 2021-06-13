<template>
  <div class="title-c" id="title-c">
    <div>
      <div class="cx">
        <div class='cx_l'></div>
        <div class='cx_m'>{{title}}</div>
        <div class='cx_l'></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Title-c',
  props: ['title'],
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
#title-c{
  .cx{display: flex;padding: 20px 25px 15px;color: #E85067;}
  .cx_l{width: 35%;height: 10px;border-bottom: 1px solid #E85067;}
  .cx_m{width: 40%;text-align: center;font-weight: bold;line-height: 20px;font-size: 16px;}
} 
</style>
