<template>
	<div>
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container style="padding:15px">
				<el-main>
					<component :is="comview" :eid="eid" @emit_tangou="emit_tg" />
				</el-main>
				
			</el-container>
		</el-container>



	</div>

</template>

<script>
	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	import {
		Loading
	} from 'element-ui';
	import {
		Api_url
	} from '@/common/config'
	import Pro from '../../components/Pro.vue'

	export default {
		name: 'Good',
		props: ['eid'],
		data() {
			return {
				comview: '',

			}
		},
		components: {
			Pro,
			NavTo,
			Header
		},
		methods: {
			emit_tg() {
				this.$emit('emit_tg')
			}
		},
		mounted() {
			this.comview = 'Pro'
		}
	}
</script>

<style lang="less">

</style>
