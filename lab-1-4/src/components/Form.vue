<template>
  <b-container class="form">
    <div class="d-flex">
      <b-col class="form_col form-input">
        <b-form @submit="onSubmit">
        <div>
            <b-form-group :label="form.profession.label+':'">
             <b-form-input v-model="form.profession.values" type="text"></b-form-input>
            </b-form-group>
        </div>
        <div>
            <b-form-group :label="form.city.label+':'">
            <b-form-input v-model="city" type="text"></b-form-input>
                <b-list-group v-if="cities">
                  <b-list-group-item v-for="(city,index) in cities" :key="index" button @click="clickCity(index)">
                    {{ city.title }}
                  </b-list-group-item>
                </b-list-group>
            </b-form-group>
        </div>
        <div>
            <b-form-group :label="form.foto.label+':'">
             <b-form-input v-model="form.foto.values" type="text"></b-form-input>
            </b-form-group>
        </div>
        <div>
            <b-form-group :label="form.full_name.label+':'">
             <b-form-input v-model="form.full_name.values" type="text"></b-form-input>
            </b-form-group>
        </div>
        <div>
            <b-form-group :label="form.phone.label+':'">
             <b-form-input v-model="form.phone.values" type="text"></b-form-input>
            </b-form-group>
        </div>
        <div>
            <b-form-group :label="form.email.label+':'">
             <b-form-input v-model="form.email.values" type="text"></b-form-input>
            </b-form-group>
        </div>
        <div>
            <b-form-group :label="form.date.label+':'">
            <b-form-datepicker v-model="form.date.values" type="text"></b-form-datepicker>
            </b-form-group>
        </div>
        {{city.title}}
         <Education
                v-for="(education,index) in form.education_arry"
                :Education="education"
                :education_length="form.education_arry.length"
                :index="index"
                :currentCityId ="city_id"
                :city="form.city.values"
                @add-education="addEducation"
                @remove-education="clickRemoveEducation"
                :key="index">
            </Education>
        <div>
            <b-form-group :label="form.desired_salary.label+':'">
             <b-form-input v-model="form.desired_salary.values" type="text"></b-form-input>
            </b-form-group>
        </div>
        <div>
            <b-form-group :label="form.key_skills.label+':'">
            <b-form-textarea v-model="form.key_skills.values" rows="3" max-rows="6"></b-form-textarea>
            </b-form-group>
        </div>
        <div>
            <b-form-group :label="form.information_about.label+':'">
            <b-form-textarea v-model="form.information_about.values" rows="3" max-rows="6"></b-form-textarea>
            </b-form-group>
        </div>
        <div>
          <b-form-group :label="form.status.label+':'">
          <select v-model="form.status.values">
            <option v-for="(stat,index) in status" :key="index">{{stat.values}}</option>
          </select>
          </b-form-group>
        </div>
        <b-alert v-if="errors.length" show variant="danger">
          <b>Телефон введён неверно</b>
        </b-alert>
            <div class="d-flex justify-content-around">
              <b-button type="submit" variant="primary">Применить</b-button>
            </div>
          </b-form>
      </b-col>
      <b-col class="form_col form-output">
        <h2 class="text-center">Резюме</h2>
        <div class="">
          <div class="d-flex justify-content-center">
            <img v-if="showResume" :src="form.foto.values" alt="" class="img_size">
          </div>
          <p class="mb-2" v-for="(f,index) in form" :key="index">
            <template v-if="f.label !== form.foto.label">
              <template v-if="f !== form['education_arry']">
                {{f.label}}:
              </template>
              <template v-if="showResume & f !== form['education_arry']">
                {{f.values}}
              </template>
            </template>
          </p>
          <b-alert show variant="secondary" v-for="(educ,index) in form.education_arry" :key="index">
            <template v-if="educ.education.values === 'Среднее'">
              <p>{{educ.education.label}}: {{educ.education.values}}</p>
            </template>
            <template v-else-if="educ.education.values === null">
              <p>{{educ.education.label}}:Отсутствует</p>
            </template>
            <template v-else>
              <p v-for="(ed,ind) in educ" :key="ind">
                {{ed.label}}:
                <template v-if="showResume">
                  {{ed.values}}
                </template>
              </p>
            </template>
          </b-alert>
        </div>
      </b-col>
    </div>
  </b-container>
</template>

<script>
import jsonp from 'jsonp';
import Education from "@/components/Education";
export default {
  name: "Form",
  components:{
    Education
  },
  data() {
    return {
      cities: [],
      city: '',
      citi_id: '',
      errors:[],
      form: {
        foto: {label:'Ссылка на фото', values: 'https://avatars.mds.yandex.net/get-zen_doc/5234501/pub_60eee28b5f08a3516318dbeb_60eee558e92187004c0f86cd/scale_1200'},
        profession: {label:'Профессия', values: ''},
        city: {label:'Город', values: ''},
        full_name: {label:'ФИО', values: ''},
        phone: {label:'Номер телефона', values: ''},
        email: {label:'Email', values: ''},
        date: {label:'Дата рождения', values: ''},
        education_arry: [
          {
            education: {label:'Образование', values: ''},
            educational_institution: {label:'Учебное заведение', values: ''},
            faculty: {label:'Факультет', values: ''},
            specialization: {label:'Специализация', values: ''},
            year_ending: {label:'Год окончания', values: ''},
          }
        ],
        desired_salary: {label:'Желаемая зарплата', values: ''},
        key_skills: {label:'Ключевые навыки', values: ''},
        information_about: {label:'О себе', values: ''},
        status: {label:'Статус', values: 'Новый'},
      },
      status:[
        {values:'Новый'},
        {values:'Назначено собеседование'},     
        {values:'Принят'},
        {values:'Отказ'}
      ],
      showResume: false
    }
  },
  watch: {
    // эта функция запускается при любом изменении
    city:  function () {
    this.debouncedGetAnswer();
    },
  },
  created: function () {
    this.debouncedGetAnswer = this.lodash.debounce(this.getCity, 500);
  },
  methods: {
    getCity: function () {
      this.cities  = 'Думаю...';
      let vm = this;
      jsonp('https://api.vk.com/method/database.getCities?country_id=1&need_all=1&count=10&v=5.126&access_token=' + '' + '&lang=ru&q=' + vm.city +'', null, (err, data) => {
      if (err) {
        vm.cities = err.message;
      } else {
        vm.cities = data.response.items;
        this.city_id= data.response.items[0].id;
        }
      });
    },
    
    clickCity(index){
      this.city = this.cities[index].title;
      this.form.city.values = this.cities[index].title;
       this.cities = [];
    },
   
    onSubmit(event) {
      if(!this.clickCityCheck)
        this.form.city.values = this.city;
      this.errors =[];
      //let regTel = new RegExp('\\d{'+this.form.phone.values.length +'}', 'gim');
      if(this.form.phone.values.length < 6 || this.form.phone.values.length > 10) {
        this.errors.push('Не корректно заполнен номер телефона, он должен состоять от 6 до 10 символов, и содержать только цифры');
      }
      if(!this.errors.length)
        this.showResume = true;
      else
        this.showResume = false;
       event.preventDefault();
    },
    clickRemoveEducation(index) {
    this.form.education_arry.splice(index,1);
    },
    addEducation() {
        this.form.education_arry.push(
            {
               education: {label:'Образование', values: ''},
               educational_institution: {label:'Учебное заведение', values: ''},
               faculty: {label:'Факультет', values: ''},
               specialization: {label:'Специализация', values: ''},
               year_ending: {label:'Год окончания', values: ''},
            }
        );
      },
    handleClick(e){
      e.preventDefault();
    },
  },
}
</script>

<style scoped>
.form_col {
  padding: 20px 100px 20px 100px;
}
.img_size {
  max-width: 250px;
  max-height: 250px;
}
</style>