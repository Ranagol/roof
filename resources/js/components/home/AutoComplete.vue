<template>
	<el-autocomplete
		v-model="state"
		:fetch-suggestions="querySearch"
		:placeholder="placeholder"
		:trigger-on-focus="false"
		:value-key="name"
		@select="handleSelect"
		@change="handleManualTyping"
	/>
</template>

<script>
import Ad from '../../models/Ad.js';

export default {
	name: 'AutoComplete',
	props: {
		value: {
			type: String,
			required: false,
			default () {
				return '';
			}
		},
		name: {
			type: String,
			required: false,
			default () {
				return '';
			}
		},
		placeholder: {
			type: String,
			required: false,
			default () {
				return '';
			}
		}
	},
	data () {
		return {
			state: this.value
		};
	},
	methods: {
		async querySearch (queryString, callbackFunction) {
			const {data} = await Ad.getCityOrLocationForAutocomplete(this.name, queryString);
			console.log('data', data);//itt altam meg es a problema ott van, hogy az autocomplete kereses nem mukodik**********
			let dataCapitalized = this.capitalizeSuggestions(data);
			console.log('dataCapitalized', dataCapitalized);
			callbackFunction(dataCapitalized);
		},

		handleSelect (item) {
			this.$emit('input', item[this.name]);
		},

		handleManualTyping (item) {
			this.$emit('input', item);
		},

		capitalizeSuggestions (data) {
			let dataCapitalized = [];
			_.forEach(data, function(object){
				if(!_.isEmpty(object.city)){
					object.city = _.startCase(object.city);
				}
				if(!_.isEmpty(object.location_in_city)){
					object.location_in_city = _.startCase(object.location_in_city);
				}
				dataCapitalized.push(object);
			});
			return dataCapitalized;
		}
	}
};
</script>

<style scoped>
.ad-list-grey-text {
	font-size: 16px;
	color: #808996;
	font-weight: 500;
	line-height: 26px;
}
</style>
