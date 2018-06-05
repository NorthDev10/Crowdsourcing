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
          <label>Что вам необходимо разработать?</label><br>
          <search-input 
            v-model="projectData.type_project"
            :options="projectTypeList"
            attrValName="id"
            :class="{'input-error-nested-el':inputErrorElList.typeProject}"
            @focus="defaultBorder('typeProject')"
            attrTitleName="title">
          </search-input>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Название проекта
            <input type="text" 
                   class="form-control" 
                   :class="{'input-error':inputErrorElList.projectName}"
                   @focus="defaultBorder('projectName')"
                   v-model="projectData.project_name">
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Название компании, бренда или домена
            <input type="text" 
                   class="form-control" 
                   :class="{'input-error':inputErrorElList.brand}"
                   @focus="defaultBorder('brand')"
                   v-model="projectData.brand">
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Расскажите нам больше о своем проекте
            <textarea 
              class="form-control" 
              :class="{'input-error':inputErrorElList.projectDescription}"
              @focus="defaultBorder('projectDescription')"
              v-model="projectData.project_description" 
              rows="3"></textarea>
          </label>
        </div>
        <div class="col-xs-12 col-sm-6">
          <p>Качественные описания проектов включают несколько слов о вас, подробное описание того, чего вы пытаетесь достичь, а также информацию о любых решениях, которые вы уже приняли относительно вашего проекта.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Выберите вашу сферу</label><br>
            <search-input 
              v-model="projectData.business_activity"
              :options="businessActivityList"
              :class="{'input-error-nested-el':inputErrorElList.businessActivity}"
              @focus="defaultBorder('businessActivity')"
              attrValName="id"
              attrTitleName="title">
            </search-input>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Как долго будет выполнятся поиск исполнителей?
            <select 
                class="form-control" 
                :class="{'input-error':inputErrorElList.tenderClosing}"
                @focus="defaultBorder('tenderClosing')"
                v-model="projectData.tender_closing">
              <option v-for="(days, index) in searchDurationExecutors" 
                :key="index" :value="index">
                {{days}}
              </option>
            </select>
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>До какого срока необходимо выполнить весь проект?
            <input class="form-control" 
                   type="date" 
                   :class="{'input-error':inputErrorElList.deadline}"
                   @focus="defaultBorder('deadline')"
                   v-model="deadline">
          </label>
        </div>
      </div>
      <p>Создание подзадач поможет выполнить проект в кратчайшие строки, так как над проектом могут быть задействованы сразу несколько исполнителей</p>
      <template v-for="(task, index) in projectData.subtasks">
        <subtask 
          v-model="projectData.subtasks[index]" 
          @removeTask="removeTask(index)"
          :key="index"
          :index="index"
          :categoryList="categoryList"
          :inputErrorElList="inputErrorElList"
          :skillList="skillList">
        </subtask>
      </template>
      <p class="d-flex justify-content-center">
        <button 
            type="button" 
            class="btn btn-info"
            @click="addTask()">
          Добавить подзадачу
        </button>
      </p>
      <p>
        <button 
            type="submit" 
            class="btn btn-success"
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
import subtask from './components/Subtask.vue'

export default {
  name: 'project',
  components: {
    subtask,
  },
  props: {
    slug: {
      type: String,
    },
    isEditingPage: {
      default: false
    },
  },
  created() {
    if(this.slug != undefined) {
      axios.get('/api/v1.0/api-my-projects/'+this.slug+'/edit')
      .then((response) => {
        this.projectData = response.data;
        this.loadingData = false;
      })
      .catch((error) => {
        this.loadingError = true;
        failedToLoad(error);
      });
    } else {
      this.loadingData = false;
    }
    this.getProjectType();
    this.getCategories();
    this.getBusinessActivity();
    this.getSkillList();
  },
  data() {
    return {
      projectTypeList: [],
      categoryList: [],
      businessActivityList: [],
      skillList: [],
      enabledSaveBtn: false,
      searchDurationExecutors: [
        '1 день',
        '2 дня',
        '3 дня',
        '4 дня',
        '5 дней',
        '6 дней',
        '7 дней',
      ],
      loadingData: true,
      loadingError: false,
      inputErrorElList: {
        typeProject: false,
        projectName: false,
        brand: false,
        projectDescription: false,
        businessActivity: false,
        deadline: false,
        tenderClosing: false,
        subtasks: [],
      },
      projectData: {
        brand: '',
        project_name: '',
        project_description: '',
        type_project: {},
        business_activity: {},
        tender_closing: 6,
        subtasks: [],
        deadline: '',
      }
    }
  },
  computed: {
    deadline: {
      get: function() {
        return this.projectData.deadline.split(' ')[0];
      },
      set: function(date) {
        this.projectData.deadline = date;
      }
    },
  },
  methods: {
    getProjectType() {
      axios.get('/api/v1.0/categories/projects-type')
        .then((response) => {
          this.projectTypeList = response.data;
        })
        .catch(this.failedToLoad);
    },
    getCategories() {
      if(this.projectData.type_project.id != -1) {
        axios.get('/api/v1.0/categories/parent-id/' + this.projectData.type_project.id)
          .then((response) => {
            this.categoryList = response.data;
          })
          .catch(this.failedToLoad);
      } else {
        this.categoryList = [];
      }
    },
    getBusinessActivity() {
      axios.get('/api/v1.0/business-activities')
        .then((response) => {
          this.businessActivityList = response.data;
        })
        .catch(this.failedToLoad);
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
      if(this.isEditingPage == 'edit') {
        axios.put('/api/v1.0/api-my-projects/' + this.slug, this.projectData)
          .then(this.projectSaved)
          .catch(this.failedToLoad);
      } else {
        axios.post('/api/v1.0/api-my-projects', this.projectData)
          .then(this.projectSaved)
          .catch(this.failedToLoad);
      }
    },
    failedToLoad(errorMessages) {
      console.log(errorMessages, errorMessages.response);
      this.enabledSaveBtn = false;
      let errorList = errorMessages.response.data.errors;
      if(errorList != undefined) {
        for(let errorMessage in errorList) {
          for(let message in errorList[errorMessage]) {
            let m = JSON.parse(errorList[errorMessage][message]);
            let index = +errorMessage.split('.')[1];// получаем строку или индекс
            if(!isNaN(index)) {//Проверяем, находится ли элемент в подзадачах
              this.inputErrorElList.subtasks[index][m['el']] = true;
            } else {
              this.inputErrorElList[m['el']] = true;
            }
            this.$snotify.warning(m['message']);
          }
        }
      } else {
        this.$snotify.warning('что-то пошло не так =(');
      }
    },
    projectSaved(response) {
      if(response.data.status == true) {
        this.$snotify.success('Проект сохранён!');
        setTimeout(() => {
          location.href = "/my-projects";
        }, 2000);
      } else {
        this.$snotify.error(response.data.message);
        this.enabledSaveBtn = false;
      }
    },
    defaultBorder(name) {
      this.inputErrorElList[name] = false;
    },
    addTask() {
      this.projectData.subtasks.push({
        id: -1,
        category: {
          id: -1,
          title: '',
        },
        description: '',
        necessary_skills: [],
        number_executors: 1,
      });
      this.inputErrorElList.subtasks.push({
        subtasksCategory: false,
        subtasksDescription: false,
        subtasksNecessarySkills: false,
        subtasksNumberExecutors: false,
        subtasksTaskName: false,
      });
    },
    removeTask(index) {
      this.inputErrorElList.subtasks.splice(index, 1);
      this.projectData.subtasks.splice(index, 1);
    },
  },
  watch: {
    'projectData.type_project': {
      handler: function (val, oldVal) {
        this.getCategories();
      },
      deep: true
    },
    'projectData.subtasks': {
      handler: function (val, oldVal) {
        if(this.projectData.subtasks.length > this.inputErrorElList.subtasks.length) {
          let quantityInputErrorEl = this.projectData.subtasks.length - this.inputErrorElList.subtasks.length;
          for(let i = 0; i < quantityInputErrorEl; ++i) {
            this.inputErrorElList.subtasks.push({
              subtasksCategory: false,
              subtasksDescription: false,
              subtasksNecessarySkills: false,
              subtasksNumberExecutors: false,
              subtasksTaskName: false,
            });
          }
        }
      },
    },
  }
}
</script>

<style lang="scss">
  .input-error, .input-error-nested-el input {
    border: 1px solid red!important;
  }
</style>
