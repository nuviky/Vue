<template>
  <b-alert show variant="secondary" class="ml-5">
    <div v-if="index !== 0" class="d-flex justify-content-end">
      <a href="" class="text-decoration-none link-education_color" @click.prevent="handleClick" @click="clickRemoveEducation">X</a>
    </div>
        <b-form-group
          :label="Education.education.label+':'"
        >
          <select
            v-model="Education.education.values"
          >
             <option>Среднее</option>
             <option>Среднее специальное</option>
             <option>Неоконченное высшее</option>
             <option>Высшее</option>
          </select>
      </b-form-group>
    <template v-if="Education.education.values !== 'Среднее' && Education.education.values !== ''" > 
       <b-form-group
              :label="Education.educational_institution.label+':'"
            >
             <b-form-input
                v-model="educ"
                type="text"
              ></b-form-input>
              <b-list-group v-if="educs">
                <b-list-group-item v-for="(ed,ind) in educs" :key="ind" button @click="clickEduc(index, ind)">
                    {{ ed.title}}
                </b-list-group-item>
              </b-list-group>
       </b-form-group>
       <b-form-group
              :label="Education.faculty.label+':'"
            >
             <b-form-input
                v-model="Education.faculty.values"
                type="text"
              ></b-form-input>
      </b-form-group>
      <b-form-group
              :label="Education.specialization.label+':'"
            >
             <b-form-input
                v-model="Education.specialization.values"
                type="text"
              ></b-form-input>
            </b-form-group>
      <b-form-group
              :label="Education.year_ending.label+':'"
            >
             <b-form-input
                v-model="Education.year_ending.values"
                type="text"
              ></b-form-input>
      </b-form-group>
    </template>
    <a v-if="education_length === index+1" href="" class="text-decoration-none link-education_color" @click.prevent="handleClick" @click="clickAddEducation">+Указать ещё одно место обучения</a>
  </b-alert>
</template>

<script>
import jsonp from "jsonp";
export default {
  name: "Education",
  props: ['Education', 'index', 'currentCityId','education_length', 'city'],
  data(){
    return {
      educ:'',
      educs: [],
    }
  },
  watch: {
    // эта функция запускается при любом изменении вопроса
    educ:  function () {
      this.educs = 'Ожидаю, когда вы закончите печатать...';
      this.debouncedGetAnswer();
    }
  },
  created: function () {
    this.debouncedGetAnswer = this.lodash.debounce(this.getEduc, 500);
  },
  methods: {
    getEduc: function () {
      this.educs  = 'Думаю...';
          jsonp('https://api.vk.com/method/database.getUniversities?city_id=' + this.currentCityId + '&country_id=1&count=10&v=5.126&access_token=' + '9bced84a7e36a2c51188a88d8e0742dcfd511f6cb1b20b10d45278570a3f5bef688bdd307fdf24900b88c' + '&q=' + this.educ + '', null, (err, data) => {
            if (err) {
              console.error(err.message);
            } else {
              this.educs = data.response.items;
            }
          });
        
    },
    clickEduc(index){
      this.educ = this.educs[index].title;
      this.educs = [];
      this.Education.educational_institution.values = this.educ;
      
    },
    handleClick(e){
      e.preventDefault();
    },
    clickAddEducation(){
      this.$emit('add-education');
    },
    clickRemoveEducation(index) {
      this.$emit('remove-education', index);
    }
  }
}
</script>

<style scoped>
/*Стили для ссылок*/
.link-education_color {
  color: gray;
}
.link-education_color:hover{
  color: azure;
}
</style>