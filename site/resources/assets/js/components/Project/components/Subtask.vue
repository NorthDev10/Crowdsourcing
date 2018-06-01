<template>
  <div class="card subtask">
    <div class="d-flex card-header justify-content-end">
      <button 
        type="button" 
        title="Отменить задачу" 
        class="btn btn-outline-danger"
        @click="removeTask">X
      </button>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Выберите подходящую категорию</label><br>
          <search-input 
            v-model="value.category"
            :options="categoryList"
            attrValName="id"
            attrTitleName="title">
          </search-input>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Название задания</label><br>
          <search-input 
            v-model="value.task_name"
            :options="taskNameList"
            @keyup="getSkillList"
            attrValName="id"
            attrTitleName="task_name">
          </search-input>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label class="description">Опишите, что необходимо сделать
            <textarea v-model="value.description" 
                      class="form-control" 
                      id="projectDescribe" 
                      rows="3">
            </textarea>
          </label>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Какие навыки необходимы?</label><br>
            <search-input 
              v-model="chosenSkill"
              :options="skillList"
              attrValName="id"
              attrTitleName="name">
            </search-input>
            <button 
              type="button" 
              class="btn btn-info"
              @click="addSkill()">
              Добавить навык
            </button>
        </div>
        <div class="col-sm-6">
          <template v-for="(skill, index) in value.necessary_skills">
            <span :key="index" class="skill badge badge-info">
              {{skill.name}}
              <span class="fa-times-circle" 
                    title="Убрать навык" 
                    @click="value.necessary_skills.splice(index, 1)">
              </span>
            </span>
          </template>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <label>Количество исполнителей
            <input type="number" v-model="value.number_executors" min="1" class="form-control">
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'subtask',
  props: {
    value: {
      required: true,
    },
    categoryList: {
      required: true,
    },
    skillList: {
      required: true,
    }
  },
  data() {
    return {
      chosenSkill: {},
      taskNameList: [],
    }
  },
  methods: {
    removeTask() {
      this.$emit('removeTask');
    },
    addSkill() {
      if(Object.keys(this.chosenSkill).length > 0) {
        if(!this.value.necessary_skills.some((skill) => {
          return skill.name == this.chosenSkill.name;
        })) {// предотвращает повторное добавление скилов в список
          this.value.necessary_skills.push(Object.assign({}, this.chosenSkill));
        }
        this.chosenSkill = {};
      }
    },
    getSkillList(taskName) {
      axios.post('/api/v1.0/list-of-task-name', {
        params: {
          taskName: taskName
        }
      })
        .then((response) => {
          this.taskNameList = response.data;
        })
        .catch((error) => {
          //this.$snotify.error(this.translate('info.something_went_wrong'));
          console.log(error);
        });
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
</style>