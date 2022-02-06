<template>
  <div  class="row row-cols-2">
    <div class="col form d-flex align-items-start flex-fill">
      <form class="form-control-lg">
        <h1 class="display-5">Основная информация</h1>
        <div class="mb-3">
          <label for="FIO" class="form-label">ФИО</label>
          <FormInput
              id="FIO"
              typeInput="text"
              class="form-control"
              placeholder=""
              v-model="FIO"
          />
        </div>
        <div class="mb-3">
          <label for="birthdate" class="form-label">Дата рождения</label>
          <FormInput
              id="birthdate"
              typeInput="text"
              class="form-control"
              v-model="birthdate"
          />
        </div>
        <div class="mb-3">
          <label for="city" class="form-label">Город проживания</label>
          <FormInputCity placeholder="Липецк" v-model="city" />
        </div>
        <div v-if="loaded" class="mb-3">
          <label for="education" class="form-label">Образование</label>
          <FormInputEducation
              :citySearch="city"
              id="education"
              class="form-control"
              v-model="education"
              :educationFromDB="education"
          />
        </div>
        <h1 class="display-5 mt-3">Контактная информация</h1>
        <div class="mb-3">
          <label for="email" class="form-label">Электронная почта</label>
          <FormInput
              id="email"
              typeInput="email"
              class="form-control"
              placeholder="name@example.com"
              v-model="email"
          />
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label">Телефон</label>
          <FormInputPhone
              id="phone"
              typeInput="tel"
              class="form-control"
              placeholder="89998887766"
              v-model="phone"
          />
        </div>
        <h1 class="display-5 mt-3">Информация о работе</h1>
        <div class="mb-3">
          <label for="profession" class="form-label">Профессия</label>
          <FormInput
              id="profession"
              typeInput="text"
              class="form-control"
              placeholder="Программист"
              v-model="profession"
          />
        </div>
        <div class="mb-3">
          <label for="salary" class="form-label">Желаемая зарплата в руб.</label>
          <FormInput
              id="salary"
              typeInput="number"
              class="form-control"
              placeholder="10000"
              v-model="salary"
          />
        </div>
        <div class="mb-3">
          <label for="keySkills" class="form-label">Ключеные навыки</label>
          <textarea
              id="keySkills"
              class="form-control"
              rows="3"
              v-model="keySkills"
          ></textarea>
        </div>
        <div class="mb-3">
          <label for="about" class="form-label">О себе</label>
          <textarea
              id="about"
              class="form-control"
              rows="3"
              v-model="about"
          ></textarea>
        </div>
        <div class="mb-3">
          <label for="formFile" class="form-label">Фото</label>
          <FormInput
              class="form-control"
              typeInput="text"
              id="formFile"
              v-model="url"
          />
        </div>
        <div class="mb-3">
          <label for="statusResume" class="form-label">Статус резюме</label>
          <div id="v-model-select">
            <select class="form-select" v-model="statusResume">
              <option selected>Новый</option>
              <option>Назначено собеседование</option>
              <option>Принят</option>
              <option>Отказ</option>
            </select>
          </div>
        </div>
        <button @click="sendData" class="btn btn-primary" type="submit">
          Отправить
        </button>
      </form>
    </div>
    <div class="col">
      <img id="formPhoto" src="" />
      <h1>Ваше резюме</h1>
      <p>ФИО:{{ FIO }}</p>
      <p>Дата рождения:{{ birthdate }}</p>
      <p>Город проживания:{{ city }}</p>
      <p>Образование:{{ outPutDataEducation }}</p>
      <p>Образование:{{ education }}</p>
      <p>Эл. почта:{{ email }}</p>
      <p>Номер телефона:{{ phone }}</p>
      <p>Профессия:{{ profession }}</p>
      <p>Желаемая ЗП:{{ salary }}</p>
      <p>Ключевые навыки:{{ keySkills }}</p>
      <p>О себе:{{ about }}</p>
      <p></p>
    </div>
  </div>
</template>

<script>
import FormInput from "@/components/Input.vue";
import FormInputEducation from "@/components/InputEducation.vue";
import FormInputPhone from "@/components/InputPhone.vue";
import FormInputCity from "@/components/InputCity.vue";
import axios from "axios"

export default {
  name: "FormComponent",
  data() {
    return {
      FIO: " ",
      phone: "",
      email: "",
      birthdate: "",
      education: {},
      profession: "",
      salary: "",
      keySkills: "",
      city: "",
      about: "",
      url: "",
      statusResume: "Новый",
      outPutDataEducation: " ",
      sendDataToAPI: {},
      loaded : false
    };
  },
  components: {
    FormInput,
    FormInputEducation,
    FormInputPhone,
    FormInputCity,
  },
  created() {
    console.log(this.$router)
    if (this.$router.currentRoute._value.params.id != undefined ) {
      console.log(this.$router.currentRoute._value.params.id);
      axios
          .get("http://localhost:8080/api/cv/"+this.$router.currentRoute._value.params.id)
          .then((response) => {
            console.log(response);
            this.FIO = response.data.FIO
            this.phone = response.data.phone
            this.email = response.data.email
            this.dateBirth = response.data.birthdate
            this.education = {
              selectedEducation: response.data.educationLevel,
              university: response.data.university,
              faculty: response.data.faculty,
              specialization: response.data.specialization,
              yearOfGraduation: response.data.graduationYear,
            },
                this.profession = response.data.profession
            this.city = response.data.city
            this.salary = response.data.salary
            this.keySkills = response.data.keySkills
            this.about = response.data.about
            this.url = response.data.image
            this.statusResume =  response.data.status
          })
          .catch((error) => {
            console.log(error);
            this.errored = true;
          })
          .finally(() => ((this.loaded = true)));
    }
    this.loaded = true
  },
  watch: {
    url: function (value) {
      document.getElementById("formPhoto").src = value;
    },
    education: {
      handler() {
      },
      deep:true
    }

  },
  methods: {
    sendData() {
      console.log(this.$router.currentRoute);
      console.log(this.sendDataToAPI)
        axios
            .post("http://localhost:8080/api/cv/"+this.$router.currentRoute._value.params.id+"/edit", {
              FIO: this.FIO,
              phoneNumber: this.phone,
              email: this.email,
              birthdate: this.birthdate,
              educationLevel: this.education.selectedEducation,
              university: this.education.university,
              faculty: this.education.faculty,
              specialization: this.education.specialization,
              graduationYear: this.education.yearOfGraduation,
              profession: this.profession,
              city: this.city,
              salary: this.salary,
              keySkills: this.keySkills,
              about: this.about,
              image: this.url,
              status: this.statusResume,
            })
            .then((response) => {
              console.log('ok');
              console.log(response);
            })
            .catch((error) => {
              console.log('err');
              console.log(error);
              this.errored = true;
            })

    },
  },
};
</script>

<style>
img {
  height: 8em;
  width: 6em;
}
</style>