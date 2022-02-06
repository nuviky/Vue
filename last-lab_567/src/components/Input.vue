<template>
  <input v-bind:type="this.typeInput"   v-model="inputValue"/>
</template>

<script>
export default {
    name:"FormInput",
    props:['typeInput','modelValue'],
    computed:{
        inputValue: {
            get() {
               return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        }
    }
}
</script>

<style>

</style>