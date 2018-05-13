<template>
  <div class="search-input">
    <input type="text" ref="search" class="form-control input-text user-input"
           v-model.trim="userInput"
           v-on:focus="visibleOptions = true">
    <span class="fa-times-circle" 
          title="Очистить поле" 
          v-on:click="clear()"></span>
    
    <div v-show="visibleOptions" class="custom-option">
      <span v-for="(option) in searchByOptions" 
            v-on:click="optionSelected(option)">
        {{option}}
      </span>
    </div>
  </div>
</template>

<script>

export default {
  name: 'search-input',
  props: {
    returnVal: {
      default: true,
      type: Boolean
    },
    options: {
      required: true,
      type: Array,
    },
    value: {//сюда попадают значение из директивы v-model
      required: true,
    },
    listSize: {
      default: 7,
      type: Number,
    },
  },
  created() {
    if(this.returnVal) {
      this.currentState.val = this.value;
      this.currentState.key = getIndexSelectedOption(this.value);
      this.userInput = this.value;
    } else {
      this.currentState.val = this.options[this.value];
      this.currentState.key = this.value;
      this.userInput = this.options[this.value];
    }
  },
  data() {
    return {
      visibleOptions: false,
      userInput: "",
      currentState: {
        key: 0,
        val: 0,
      },
    }
  },
  computed: {
    searchByOptions() {
      if(this.userInput.length > 0) {
        let result = this.options.filter((option) => {
          return this.compareStr(
            option.toLowerCase(),
            this.userInput.toLowerCase()
          );
        }).sort().slice(0, this.listSize);
        //если предыдущий поиск не дал результатов, то
        if(result.length == 0) {
          // ищим вхождения в словах
          return this.options.filter((option) => {
            return option.toLowerCase()
              .indexOf(this.userInput.toLowerCase()) >= 0;
          }).sort().slice(0, this.listSize);
        } else {
          return result;
        }
      } else {
        return this.options.slice(0, this.listSize).sort();
      }
    },
  },
  methods: {
    compareStr(word, searchWord) {
      return word.substring(0, searchWord.length) == searchWord;
    },
    optionSelected(option) {
      let indexOption = this.getIndexSelectedOption(option);

      if(this.currentState.key != indexOption) {
        this.currentState.key = indexOption;
        this.currentState.val = option;
        
        this.emitData();
      }
      
      this.userInput = option;
      this.visibleOptions = false;
    },//из полного массива опций, находим индекс опции
    getIndexSelectedOption(Option) {
      for(let key in this.options) {
        if(this.options[key] == Option) {
          return key;
        }
      }
    },
    hideList(e) {
      try {
        if(!(e.target.classList.contains('user-input') || 
          e.target.classList.contains('fa-times-circle') || 
          e.target.parentNode.classList.contains('selected-option') || 
          e.target.classList.contains('selected-option'))) {
            this.visibleOptions = false;
        }
      } catch(e) {//при клике на svg возникает ошибка
        this.visibleOptions = false;
      }
    },
    clear() {
      this.userInput = '';
      this.currentState.key = '';
      this.currentState.val = '';
      this.$refs.search.focus();
      this.emitData();
    },
    emitData() {
      if(this.returnVal) {
        this.$emit('input', this.currentState.val);
      } else {
        this.$emit('input', this.currentState.key);
      }
    }
  },
  watch: { //чтоб при смене языка обновлялось выбранное значение в select-е
    options() {
      this.currentState.val = this.options[this.currentState.key];
      this.userInput = this.options[this.currentState.key];
    },
    userInput() {
      if(this.userInput.length == 0) {
        this.clear();
      }
    }
  },
  mounted() {
    document.body.addEventListener("click", this.hideList);
  },
  beforeDestroy() {
    document.body.removeEventListener("click", this.hideList);
  },
}
</script>

<style lang="scss" scoped>
  @import "~fontawesome-scss/fontAwesome.scss";

  .search-input {
    user-select: none;
    position: relative;
    display: inline;

    .user-input{
      display: inline!important;
      width: auto!important;
      padding-right: 25px;
    }

    .custom-option {
      display: flex;
      flex-direction: column;
      background: white;
      position: absolute;
      left: 0;
      min-width: 160px;
      text-align: left;
      cursor: pointer;
      z-index: 1;
      border: 1px solid #e9ecef;

      span {
        font-size: 1.1em;
        padding: 5px;

        &:hover {
          background: #e9ecef;
        }
      }
    }

    .fa-times-circle {
      position: absolute;
      top: 0;
      right: 10px;
      cursor: pointer;
      @include fa($fa-times-circle, black, 18px);

      &:hover {
        opacity: .7;
      }
    }
  }
</style>