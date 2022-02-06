<template>
  <input id="city" class="form-control" type="text" v-model="inputValue"/>
  <p v-show="showHint" >Возможно вы имели ввиду...</p>
  <div class="list-group" v-show="showHint">
        <button @click="insertSearch" type="button" class="list-group-item list-group-item-action"  v-for="hint in hints" v-bind:key="hint">
          {{hint}}
        </button>
  </div>
</template>

<script>
import  jsonp  from 'jsonp'
//const axios = require('axios').default;
export default {
    name:"FormInputCity",
    data() {
        return {
            showHint: false,
            hints: [],
        }
    },
    props:['modelValue'],
    watch: {
        inputValue: function () {
            jsonp(`http://api.vk.com/method/database.getCities?country_id=1&q=${this.inputValue}&count=3&v=5.131&access_token=${process.env.VUE_APP_ACCESS_TOKEN}`, null, (err, data) => {
                if (err) 
                {
                    console.log(err)
                }
                else
                {
                    console.log(data)
                    this.showHint = true
                    this.hints = []
                    data.response.items.forEach(element => {
                        //console.log(element.title)
                        this.hints.push(element.title)
                    });
                }
            })
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
    },
    methods:{
        insertSearch(event)
        {
            this.$emit('update:modelValue', event.target.innerHTML)
        }
    }
}
</script>

<style>

</style>