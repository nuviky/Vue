<template>
  <input type="number"   v-model="inputValue"/>
</template>

<script>
export default {
    name:"FormInputPhone",
    props:['modelValue'],
    watch:{
        inputValue: function (value){
            if (value.length == 11) {
                document.getElementById('phone').classList.remove("is-valid")
            }
            else
            {
                document.getElementById('phone').classList.add("is-invalid")
            }
        }
    },
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