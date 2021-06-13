<template>
	<div>    
			<component :is="comview" :eid="eid" @emit_tangou="emit_tg" @back="back" />
	</div>

</template>

<script>
	import {Loading} from 'element-ui'; 
	import {Api_url} from '@/common/config'  
	import Pro from './Pro.vue' 
	
	export default {
		name: 'Good', 
		props: ['eid'],
		data() {
			return { 
				comview:''
			}
		}, 
		components: { 
			Pro
		},
		methods: { 
			back(){
				this.$emit('back')
			},
			emit_tg(){
				this.$emit('emit_tg')
			}
		},
		mounted() {
			this.comview='Pro'		
		}
	}
</script>

<style lang="less">

</style>
