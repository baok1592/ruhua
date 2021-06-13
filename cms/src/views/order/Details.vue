<template>
	<div id="detail_page">
		<div>
			<div class="details">
				<div class="details-l fl">
					<div class="details-l-01">
						<div class="fl">订单信息</div>
						<!-- <div class="fr"><span>{{details.type?'普通商品':'拼团商品'}}</span></div> -->
						<div class="clear"></div>
					</div>
					<div class="details-l-02">
						<div class="details-l-02-l">订单编号 ：</div>
						<div class="details-l-02-r">{{details.order_num}}</div>
					</div>

					<div class="details-l-02">
						<div class="details-l-02-l">付款方式 ：</div>
						<div class="details-l-02-r">{{details.payment_type=='wx'?'微信':'其他'}}</div>
					</div>
					<div class="details-l-02">
						<div class="details-l-02-l">买家 ：</div>
						<div class="details-l-02-r">{{details.users.nickname}}</div>
					</div>
					<div class="details-l-02">
						<div class="details-l-02-l">买家IP ：</div>
						<div class="details-l-02-r">{{details.user_ip}}</div>
					</div>
					<div class="details-l-02">
						<div class="details-l-02-l">购买时间 ：</div>
						<div class="details-l-02-r">{{details.create_time}}</div>
					</div>
					<div class="details-l-02">
						<div class="details-l-02-l">使用时间 ：</div>
						<div class="details-l-02-r">{{details.create_time}}</div>
					</div>

					<hr style="height:1px;border:none;border-top:1px dashed #f0f0f0;">
					<div class="details-l-02">
						<div class="details-l-02-l">收货信息 ：</div>
						<div class="details-l-02-r">{{details.receiver_name}},{{details.receiver_mobile}},{{details.receiver_province}}{{details.receiver_city}}{{details.receiver_district}}{{details.receiver_address}}</div>
					</div>
					<div class="details-l-02">
						<div class="details-l-02-l">买家留言 ：</div>
						<div class="details-l-02-r">{{details.message}}</div>
					</div>
				</div>

				<div class="details-r fl">
					<div class="details-r-01">
						<el-row :gutter="20">
							<el-col :span="6">
								<i class="el-icon-warning"></i>
								订单状态：
								<span v-if="details.payment_state == 1">
									已支付
									<span v-if="details.shipment_state == 0">- 待发货</span>
									<span v-else>已发货</span>
								</span>
								<span v-else>未支付</span>
							</el-col>
							<el-col :span="8">
								<el-button size="small" @click="form_shipping = true">快递单号</el-button>
								<!-- <el-button size="small" @click="remark = true">添加订单备注</el-button> -->
							</el-col>
						</el-row>

						<el-row>
							<p>{{details.remark}}</p>
						</el-row>

						<el-table ref="singleTable" :data="details.ordergoods" highlight-current-row style="width: 100%;margin-top:20px">
							<el-table-column type="index" width="50"></el-table-column>
							<el-table-column label="商品名称" width="400">
								<template slot-scope="scope">
									<div style="width:80%;">{{scope.row.goods_name | ellipsis}}</div>
								</template>
							</el-table-column>
							<el-table-column label="规格" width="200">
								<template slot-scope="scope">
									{{scope.row.sku_name}}
								</template>
							</el-table-column>
							<el-table-column property="num" label="数量" width="120"></el-table-column>
							<el-table-column property="price" label="单价" width="120"></el-table-column>
							<el-table-column property="price" label="合计">
								<template slot-scope="scope">{{scope.row.price * scope.row.num}}</template>
							</el-table-column>
						</el-table>
					</div>

					<hr style=" height:2px;border:none;border-top:2px dotted #f0f0f0;">

					<el-row :gutter="20" style="font-size:14px;">
						<el-col :span="6" :offset="1">快递: {{kd_cmp[details.courier]}} {{details.courier_num}}</el-col>

						<el-col :span="6" :offset="8">运费：{{details.shipping_money}}&emsp;订单总金额：¥{{details.order_money}}</el-col>
					</el-row>
				</div>

				<div class="clear"></div>
			</div>

			<div class="log">
				<div class="log-01">订单日志</div>
				<div class="log-02">
					<ul>
						<li v-for="(item,index) of log">
							[{{item.create_time}}]
							<b>{{item.type_name}}：</b>
							{{item.content}}
						</li>
					</ul>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<el-dialog width="400px" :visible.sync="form_shipping">
			<el-form :model="form" style="display: flex;flex-wrap: wrap;">
				<el-form-item label="快递公司" label-width="80px">
					<el-select v-model="form.courier" placeholder="">
						<el-option v-for="(k,v) in kd_cmp" :value="v" :label="k" :key="k"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="运单号" label-width="80px">
					<el-input v-model="form.courier_num" auto-complete="off"></el-input>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer" style="text-align: center">
				<el-button @click="form_shipping = false">取 消</el-button>
				<el-button type="primary" @click="courier_sub">确 定</el-button>
			</div>
		</el-dialog>

		<el-dialog title width="500px" :visible.sync="remark">
			<el-form :model="form">
				<el-form-item label="备注" label-width="80px" style="width: 100%">
					<el-input v-model="form.remark" auto-complete="off"></el-input>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer" style="text-align: center">
				<el-button @click="remark = false">取 消</el-button>
				<el-button type="primary" @click="remark_sub">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<script>
	export default {
		name: "Details",
		filters: {
			//字段内容过多隐藏加...
			ellipsis(value) {
				if (!value) return "";
				if (value.length > 30) {
					return value.slice(0, 30) + "...";
				}
				return value;
			}
		},
		props: ["order_id"],
		data() {
			return {
				log:[],
				details: [],
				list: [],
				mid: this.order_id,
				remark: false,
				form: {
					remark: "", //订单备注
					price: "", //调整的金额
					courier: "", //快递公司
					courier_num: "" //快递单号
				},
				form_price: false,
				form_shipping: false,
				formLabelWidth: "120px",
				kd_cmp: {
					SF: "顺丰速运",
					HTKY: "百世快递",
					ZTO: "中通快递",
					STO: "申通快递",
					YTO: "圆通速递",
					YD: "韵达速递",
					YZPY: "邮政快递包裹",
					EMS: "EMS",
					HHTT: "天天快递",
					JD: "京东快递",
					UC: "优速快递",
					DBL: "德邦快递",
					ZJS: "宅急送",
					UPS: "UPS",
					"0": "其他"
				}
			};
		},
		methods: {
			//获取订单信息
			post_details() {
				var that = this;
				this.http
					.post("order/admin/get_order_one", {
						id: this.mid
					})
					.then(res => {
						that.details = res.data.order //订单详情
						this.log = res.data.log
					});
			},
			//删除商品
			del(id, index) {
				
			},
			//更新订单的快递信息
			courier_sub() {
				var that = this;
				var arr = {
					courier: this.form.courier,
					courier_num: this.form.courier_num,
					order_id: this.order_id
				};
				this.http.post_show("order/admin/edit_courier", arr).then(res => {
					var res_code = res.status.toString(); //返回结果状态码转字符串取第一位数
					if (res_code.charAt(0) == 2) {
						that.$message({
							showClose: true,
							message: "修改成功",
							type: "success"
						});
						that.form_shipping = false;
						that.form.courier = "";
						that.form.courier_num = "";
					} else {
						that.$message({
							showClose: true,
							message: res.msg,
							type: "error"
						});
					}
				});
			}
		},
		//vue生命函数
		mounted() {
			this.post_details();
		}
	};
</script>

<style lang="less">
	#detail_page {

		.el-input__inner {
			width: 100%;
		}

		background-color: #FFFFFF;

		.fl {
			float: left;
		}

		.fr {
			float: right;
		}

		.clear {
			clear: both;
		}

		.details {
			border: 1px solid #e0e0e0;
		}

		.details-l {
			border-right: 1px solid #f0f0f0;
			width: 20%;
			box-sizing: border-box;
			padding: 30px;
		}

		.details-l-01 {
			font-weight: bold;
			width: 100%;
			font-size: 14px;
		}

		.details-l-01 span {
			font-weight: 400;
			font-size: 10px;
			text-align: right;
			color: #ffa042;
		}

		.details-l-02 {
			font-size: 10px;
			color: #000000;
			line-height: 30px;
		}

		.details-l-02-l {
			display: inline-block;
			width: 30%;
			text-align: right;
			vertical-align: top;
		}

		.details-l-02-r {
			display: inline-block;
			width: 70%;
		}

		.details-r-01 {
			padding-bottom: 20px;
		}

		.details-r-01-r-01 {
			padding-bottom: 10px;
		}

		.details-r {
			width: 80%;
			box-sizing: border-box;
			padding: 30px;
		}

		.details-r-01-l {
			display: inline-block;
			vertical-align: top;
		}

		.details-r-01-r {
			display: inline-block;
			padding-left: 10px;
			font-size: 12px;
			line-height: 30px;
			padding-bottom: 10px;
		}

		.details-r-02 {
			padding-top: 15px;
			font-size: 14px;
		}

		.details-r-03 {
			text-align: right;
			height: 60px;
			line-height: 60px;
			font-size: 14px;
		}

		.details-r-03 span {
			color: red;
		}



		.log-01 {
			height: 40px;
			background-color: #f0f0f0;
			color: #000000;
			line-height: 40px;
			font-size: 12px;
			border-top: 1px solid #e0e0e0;
			border-bottom: 1px solid #e0e0e0;
			padding-left: 10px;
		}

		.log-02 {
			text-align: left;
			color: #8e8e8e;
			line-height: 40px;
			font-size: 12px;
			border-bottom: 1px solid #e0e0e0;
			padding-left: 10px;
		}

		.log-02 ul,
		.log-02 li {
			text-decoration: none;
			list-style: none;
		}

		.details-r-02 ul {
			border-bottom: 1px solid #e0e0e0;
			line-height: 50px;
		}

		.details-r-02 ul li {
			display: inline-block;
		}

		.order-list-info li {
			line-height: 28px;
		}

		.order-list-info li img {
			width: 70px;
			height: 70px;
			padding: 10px;
			vertical-align: middle;
		}
	}
</style>
