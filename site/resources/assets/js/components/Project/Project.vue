<template>
  <div>
    <search-input 
      v-model="indexProjectType"
      v-bind:return-val="false"
      v-bind:options="projectTypeNameList">
    </search-input>
    <search-input 
      v-model="indexCategory"
      v-bind:return-val="false"
      v-bind:options="categoriesNameList">
    </search-input>
    {{projectTypeId}} / {{categoryId}}
  </div>
</template>

<script>

export default {
  name: 'project',
  created() {
    this.getProjectType();
    this.getCategories();
  },
  data() {
    return {
      indexProjectType: 1,
      indexCategory: 1,
      projectTypeList: [],
      categoryList: [],
    }
  },
  methods: {
    getProjectType() {
      axios.get('/api/v1.0/categories/projects-type')
        .then((response) => {
          this.projectTypeList = response.data;
        })
        .catch((error) => {
          //this.$snotify.error(this.translate('info.something_went_wrong'));
          console.log(error);
        });
    },
    getCategories() {
      axios.get('/api/v1.0/categories/parent-id/' + this.projectTypeId)
        .then((response) => {
          this.categoryList = response.data;
        })
        .catch((error) => {
          //this.$snotify.error(this.translate('info.something_went_wrong'));
          console.log(error);
        });
    },
  },
  computed: {
    projectTypeNameList() {
      return this.projectTypeList.map((currentValue, index, array) => {
        return currentValue.title;
      });
    },
    projectTypeId() {
      if(this.projectTypeList == undefined) {
        return 1;
      } else {
        return this.projectTypeList[this.indexProjectType].id;
      }
    },
    categoriesNameList() {
      return this.categoryList.map((currentValue, index, array) => {
        return currentValue.title;
      });
    },
    categoryId() {
      if(this.projectTypeList == undefined) {
        return 1;
      } else {
        return this.categoryList[this.indexCategory].id;
      }
    },
  },
}
</script>

<style lang="scss" scoped>

</style>