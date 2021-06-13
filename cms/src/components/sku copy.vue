<template>
  <div class="">
    <div class="form-group">
      <div class="form-h">商品规格</div>
      <div class="form-item"  v-for="(attr,index) in attrs" :key="`attr${index}`">
        <div class="form-title">
          <input type="text" name="" value="" v-model="attr.pName" placeholder="规格名">
          <span class="delete" @click="toDelete(index)">×</span>
        </div>
        <ul class="form-list">
          <li v-for="(item,index2) in attr.spec" :key="`item${index2}`">
            <input class="spec-item" type="text" name="" value="" v-model="item.cName">
          </li>
        </ul>
        
      </div>
      <div class="form-btn-group"><button class="btn" type="button" name="" @click="addItem">添加规格项目</button></div>
      <div class="form-table" v-show="tableData">
        <div class="stock-title">商品库存</div>
        <table class="table-sku" border="1px solid #ccc">
          <thead>
            <tr>
              <td v-for="(list,index) in tableData" :key="`list${index}`">{{list['pName']}}</td>
              <td>价格</td>
              <td>数量</td>
            </tr>
          </thead>
          <tbody>
              <tr v-for="(row,index) in rows" :key="`row${index}`">
                  <td v-for="(item,index2) in tableData" v-if="!((row-1)%item['rowspan'])" :rowspan="item['rowspan']" :key="`item${index2}`">
                      {{item|getName(row)}}
                  </td>
                  <td><input type="text" v-model="tableList[row-1]['price']"></td>
                  <td><input type="text" v-model="tableList[row-1]['number']"></td>
              </tr>
          </tbody>
        </table>
        <div class="form-btn-group"><button class="btn" type="button" name="" @click="toConfirm">确认</button></div>
    </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      attrs:[]
    }
  },
  mounted () {
     //console.log(this.attrs);
     //console.log(this.tableData);
     //console.log(this.rows);
     //console.log(this.tableList);
  },
  filters:{
    getName:function(obj,index){
        if(obj){
          var r = parseInt((index-1)/obj['rowspan']);
          var l = obj['specLen']||1;
          var key = r%l;
          return obj['spec']&&obj['spec'][key]&&obj['spec'][key]['cName'];
        }
    }
  },
  computed:{
    tableData:function(){
      var attrs = this.attrs;
      var len = attrs.length;
      if(len==0){
        return;
      }
      var tData = [];
      //初始化tableData
      for (var i = 0; i < len; i++) {
        var row = {};
        row['pName'] = attrs[i]['pName'];
        row['spec'] = [];
        row['price'] = {};
        row['number'] = {};
        var len2 = attrs[i]['spec'].length;
        var specLen = 0;
        for (var j = 0; j < len2; j++) {
            var spe = {};
            var cName = attrs[i]['spec'][j]['cName'];
            if(!cName){
              continue;
            }
            ++specLen;
            spe['cName'] = cName;
            row['spec'].push(spe);
        }
        row['specLen'] = specLen;
        tData.push(row);
      }
      //获取rowspan
      for (var k = 0,len3=tData.length; k < len3; k++) {
          var rowspan = 1;
          for (var k1 = k+1; k1 < len3; k1++) {
              var kSpecLen = tData[k1]['specLen']||1;
              rowspan*=kSpecLen;
          }
          tData[k]['rowspan'] = rowspan;
      }
      return tData;
    },
    rows:function(){
      if(!this.tableData){
        return;
      }
      var rows = 1;
      var tableData = this.tableData;
      var len = tableData.length;
      for (var i = 0; i < len; i++) {
         var specLen = tableData[i]['specLen']||1;
         rows*=specLen;
      }
      //每条rowspan都为1情况
      if(rows==1){
        return tableData[0]['spec'].length;
      }
      return rows;
    },
    tableList:function(){
        if(!this.tableData){
          return;
        }
       var rows = this.rows;
       var tList = [];
       var srcData = this.tableData;
       //console.log(srcData);
       for (var i = 0; i < rows; i++) {
          var listItem = {};
          //构建动态项
          for (var j = 0; j < srcData.length; j++) {
              //构造第一类目
              var key = srcData[j]['pName'];
              var rowspan = srcData[j]['rowspan'];
              var len = srcData[j]['specLen'];
              if(!len){
                continue;
              }
              var spec = srcData[j]['spec'];
              var index = parseInt(i/rowspan)%len;
              listItem[key] = spec[index]['cName'];
          }
          //构建固定项(price,number)
          listItem['price'] = "";
          listItem['number'] = "";
          tList.push(listItem);
       }
       return tList;
    }
  },
  components: {
  },
  methods: {
    addItem:function(){
      var obj = {
          pName: '',
          rowspan:1,
          spec: [
            {cName:''},
            {cName:''},
            {cName:''},
            {cName:''},
            {cName:''},
            {cName:''},
            {cName:''},
            {cName:''}
          ]
      };
      if(this.attrs.length>=3){
        return;
      }
      this.attrs.push(obj);

    },
    toDelete:function(index){
        this.attrs.splice(index,1);
    },
    toConfirm:function(){
        // console.log(this.tableList);
        alert(JSON.stringify(this.tableList))
    }
  }
}
</script>

<style lang="css">
/**reset*/
button,input{ 
   -webkit-tap-highlight-color: rgba(0, 0, 0, 0);    
   outline: none;
}
.btn{
  padding: 4px 12px;
  margin-bottom: 0;
  font-size: 14px;
  color: #333;
  vertical-align: middle;
  cursor: pointer;
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  -webkit-border-radius: 2px;
  -moz-border-radius: 2px;
  border-radius: 2px;
}
.btn.active, .btn:active, .btn:focus, .btn:hover {
    text-decoration: none;
    color: #333;
    background-color: #fcfcfc;
    border-color: #ccc;
}
/*table*/
table{
  border:0;
}
table.table-sku {
    width: 100%;
    background-color: #fff;
    text-align: left;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
table.table-sku td {
    border: 1px solid #e5e5e5;
    padding: 8px;
}
table.table-sku td input{
    padding: 10px;
    border: 1px solid #ccc;
}
/**/
.form-title{
    background: #f8f8f8;
    padding: 10px;
    position: relative;
}
.form-title .label{
    color:#999;
}
.form-title .delete{
    width: 20px;
    height: 20px;
    line-height: 20px;
    border: 1px solid #ccc;
    border-radius: 50%;
    position: absolute;
    right: 15px;
    top: 50%;
    margin-top: -10px;
    text-align: center;
    color: #fff;
    background: #ccc;
    cursor: pointer;
}
.form-title input{
    background: #fff;
    border: 1px solid #ccc;
    padding: 10px;
}
.form-list{
    padding:10px;
    margin-top:0;
}
.form-list li{
    display: inline-block;
    margin-top:10px;
}
.spec-item{
    width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.form-list,.form-title{
    text-align:left;
}
.form-list  input{
    background: #fff;
    margin-right: 10px;
    border: 1px solid #ccc;
    padding: 10px;
  }
.form-group{
  border: 1px solid #ccc;
  padding: 10px;
}
.form-table{
  margin-top: 50px;
}
.form-btn-group{
  margin-top:20px;
  background: #f8f8f8;
  padding: 10px;
}
.stock-title,.form-h{
  height: 40px;
  line-height: 40px;
}
</style>
