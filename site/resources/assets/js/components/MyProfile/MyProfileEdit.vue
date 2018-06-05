<template>
  <form>
    <template v-if="loadingData">
      <div class="d-flex justify-content-center">
        <img v-if="!loadingError" src="/img/loading.gif" alt="Загрузка данных">
        <template v-else>
          <img src="/img/something_went_wrong.png" alt="что-то пошло не так =(">       
          <h4><p>что-то пошло не так =(</p></h4>
        </template>
      </div>
    </template>
    <template v-else>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Имя пользователя</label><br>
          <input type="text" 
                 class="form-control" 
                 :class="{'input-error':inputErrorElList.name}"
                 @focus="defaultBorder('name')"
                 v-model="userData.name">
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>E-mail</label><br>
          <input type="text" 
                 class="form-control" 
                 :class="{'input-error':inputErrorElList.email}"
                 @focus="defaultBorder('email')"
                 v-model="userData.email">
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Телефон</label><br>
          <input type="text" 
                 class="form-control" 
                 :class="{'input-error':inputErrorElList.phone}"
                 @focus="defaultBorder('phone')"
                 v-model="userData.phone">
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Пароль</label><br>
          <input type="password" 
                 class="form-control" 
                 :class="{'input-error':inputErrorElList.password}"
                 @focus="defaultBorder('password')"
                 v-model="userData.password">
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Подтвердить пароль</label><br>
          <input type="password" 
                 class="form-control" 
                 :class="{'input-error':inputErrorElList.password}"
                 @focus="defaultBorder('password')"
                 v-model="confirmPassword"
                 @change="checkPassword()">
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Выберите свои навыки</label><br>
            <search-input 
              v-model="chosenSkill"
              :options="skillList"
              attrValName="id"
              attrTitleName="name">
            </search-input>
            <button 
              type="button" 
              class="m-1 btn btn-info"
              @click="addSkill()">
              Добавить навык
            </button>
        </div>
        <div class="col-sm-6 project-list">
          <template v-for="(skill, index) in userData.skills">
            <span :key="index" class="p-1 m-1 badge badge-info">
              {{skill.name}}
              <span class="fa-times-circle" 
                    title="Убрать навык" 
                    @click="userData.skills.splice(index, 1)">
              </span>
            </span>
          </template>
        </div>
      </div>
      <p>
        <button 
            type="submit" 
            class="m-1 btn btn-success"
            :disabled="enabledSaveBtn"
            @click.prevent="saveProject()">
            Сохранить
        </button>
      </p>
    </template>
    <vue-snotify></vue-snotify>
  </form>
</template>

<script>

export default {
  name: 'my-profile-edit',
  props: {
    userId: {
      required: true,
    }
  },
  created() {
    axios.get('/api/v1.0/api-my-profile/'+this.userId+'/edit')
    .then((response) => {
      this.userData = response.data;
      this.loadingData = false;
    })
    .catch((error) => {
      this.loadingError = true;
      failedToLoad(error);
    });
    this.getSkillList();
  },
  data() {
    return {
      skillList: [],
      confirmPassword: '',
      loadingData: true,
      loadingError: false,
      chosenSkill: {},
      enabledSaveBtn: false,
      inputErrorElList: {
        name: false,
        email: false,
        phone: false,
        password: false,
      },
      userData: {
        name: '',
        email: '',
        phone: '',
        password: '',
        skills: [],
      }
    }
  },
  methods: {
    addSkill() {
      if(Object.keys(this.chosenSkill).length > 0) {
        if(!this.userData.skills.some((skill) => {
          return skill.name == this.chosenSkill.name;
        })) {// предотвращает повторное добавление скилов в список
          this.userData.skills.push(Object.assign({}, this.chosenSkill));
        }
        this.chosenSkill = {};
      }
    },
    getSkillList() {
      axios.get('/api/v1.0/list-of-skills')
        .then((response) => {
          this.skillList = response.data;
        })
        .catch(this.failedToLoad);
    },
    saveProject() {
      this.enabledSaveBtn = true;
      if(this.checkPassword()) {
        axios.put('/api/v1.0/api-my-profile/' + this.userId, this.userData)
        .then((response) => {
          if(response.data.status == true) {
            this.$snotify.success('Профиль обновлён!');
            setTimeout(() => {
              location.href = "/my-profile";
            }, 2000);
          } else {
            this.$snotify.error(response.data.message);
            this.enabledSaveBtn = false;
          }
        })
        .catch(this.failedToLoad);
      }
    },
    checkPassword() {
      console.log(this.userData.password);
      if(this.userData.password != undefined && 
          this.confirmPassword != this.userData.password) {
        this.$snotify.warning('Введённые пароли не совпадают!');
        this.confirmPassword = undefined;
        this.userData.password = undefined;
        return false;
      }
      return true;
    },
    failedToLoad(errorMessages) {
      console.log(errorMessages, errorMessages.response);
      this.enabledSaveBtn = false;
      let errorList = errorMessages.response.data.errors;
      if(errorList != undefined) {
        for(let errorMessage in errorList) {
          for(let message in errorList[errorMessage]) {
            let m = JSON.parse(errorList[errorMessage][message]);
            this.inputErrorElList[m['el']] = true;
            this.$snotify.warning(m['message']);
          }
        }
      } else {
        this.$snotify.warning('что-то пошло не так =(');
      }
    },
    defaultBorder(name) {
      this.inputErrorElList[name] = false;
    },
  },
}
</script>

<style lang="scss" scoped>
  @import "~fontawesome-scss/fontAwesome.scss";

  .subtask {
    margin: 30px;
  }
  
  .skill {
    margin: 5px;
  }

  .description {
    display: block;
  }

  .fa-times-circle {
    cursor: pointer;
    @include fa($fa-times-circle, black, 18px);

    &:hover {
      opacity: .7;
    }
  }

  .input-error {
    border: 1px solid red!important;
  }
</style>