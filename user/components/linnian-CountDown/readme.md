### CountDown 倒计时

倒计时组件，组件名：``uni-countdown``，代码块： uCountDown。

**使用方式：**

在 ``script`` 中引用组件 

```javascript
import uniCountdown from "@/components/linnian-CountDown/uni-countdown.vue"
export default {
    components: {uniCountdown}
}
```

view中
```JavaScript
<uni-countdown :show-day="false" :hour="0" :minute="minute" :second="0" :reset="reset" @timeup="timeUp"> </uni-countdown>
```
然后在data中声明
```JavaScript
		data() {
			return {
				minute: 0,
				reset: false,
			}
		},
```
假如此时想更改分钟数
```JavaScript
onload(){
	//我的项目中只赋值一次, 所以直接设为true了
    this.reset = !this.reset;
    //如果还要设置天, 时, 秒, 在上面声明绑定后, 在这里赋值即可
    this.minute = 30;
}
```

不显示天数

```html
<uni-countdown 
    :show-day="false" 
    :hour="12" 
    :minute="12" 
    :second="12" :reset="false">
</uni-countdown>
```

修改颜色

```html
<uni-countdown 
    color="#FFFFFF" 
    background-color="#00B26A" 
    border-color="#00B26A" 
    :day="1" 
    :hour="2" 
    :minute="30" 
    :second="0" :reset="false">
</uni-countdown>
```

实际效果参考：[https://github.com/dcloudio/uni-ui](https://github.com/dcloudio/uni-ui)

**uniCountDown 属性说明：**

|属性名|类型|默认值	|说明|
|---|----|---|---|
|background-color|String|#FFFFFF|背景色|
|border-color|String|#000000|边框颜色|
|color	|String	|#000000|文字颜色|
|splitor-color|String|#000000|割符号颜色|
|day|Number|0|天数|
|hour|Number|0|小时|
|minute|Number|0|分钟|
|second|Number|0|秒|
|show-day|Boolean|true|是否显示天数|
|show-colon|Boolean|true|是否以冒号为分隔符|

**uniCountDown 事件说明：**

|事件称名|说明|返回参数|
|---|----|---|
|timeup|倒计时时间到触发事件|-|
