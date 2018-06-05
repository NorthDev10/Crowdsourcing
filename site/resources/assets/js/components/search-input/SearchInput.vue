<template>
  <div class="search-input">
    <input type="text" ref="search" class="form-control input-text user-input"
           v-model.trim="userInput"
           @keyup="onKeyup()"
           @change="changedUserInput()"
           @focus="onFocus()"
           @blur="hasFocusUserInput = false">
    <span class="fa-times-circle" 
          title="Очистить поле" 
          @click="clear()"></span>
    
    <div v-show="visibleOptions" class="custom-option">
      <span v-for="(option) in searchByOptions" 
            @click="optionSelected(option)"
            :key="option[attrValName]">
        {{option[attrTitleName]}}
      </span>
    </div>
  </div>
</template>

<script>

export default {
  name: 'search-input',
  props: {
    attrValName: {
      required: true,
      type: String,
    },
    attrTitleName: {
      required: true,
      type: String,
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
    try {
      if(typeof this.value == 'string') {
        this.currentState[this.attrValName] = -1;
        this.currentState[this.attrTitleName] = this.value;
        this.userInput = this.value;
      } else {
        this.currentState[this.attrValName] = this.value[this.attrValName];
        this.currentState[this.attrTitleName] = this.value[this.attrTitleName];
        this.userInput = this.value[this.attrTitleName];
      }
    } catch(e) {}
  },
  data() {
    return {
      visibleOptions: false,
      userInput: "",
      hasFocusUserInput: false,
      currentState: {},
    }
  },
  computed: {
    searchByOptions() {
      if(this.userInput != undefined && this.userInput.length > 0) {
        // сравниваем начало предложения
        let result = this.options.filter((option) => {
          return this.compareStr(
            option[this.attrTitleName].toLowerCase(),
            this.userInput.toLowerCase()
          );
        }).sort().slice(0, this.listSize);
        //если предыдущий поиск не дал результатов, то
        if(result.length == 0) {
          // ищим вхождения в словах
          return this.options.filter((option) => {
            return option[this.attrTitleName].toLowerCase()
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
    changedUserInput() {
      if(!this.isCoincidenceCurrentOption(this.userInput)) {
        this.currentState[this.attrValName]   = -1;
        this.currentState[this.attrTitleName] = this.userInput;
        this.$emit(
          'input', 
          Object.assign({}, this.currentState)
        );//возвращаем пользовательскую опцию
      }
    },
    //совпадает ли название текущей опции с передаваемой
    isCoincidenceCurrentOption(optionTitle) {
      return this.currentState[this.attrTitleName] == optionTitle;
    },
    optionSelected(option) {
      if(this.currentState[this.attrValName] != option[this.attrValName]) {
        this.currentState = Object.assign({}, option);
        this.$emit('input', this.currentState);
      }
      
      this.userInput = this.currentState[this.attrTitleName];
      this.visibleOptions = false;
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
      this.currentState = {};
      this.$refs.search.focus();
      this.$emit('input', this.currentState);
    },
    displayList() {
      if(this.hasFocusUserInput) {
        this.visibleOptions = true;
      } else {
        this.visibleOptions = false;
      }
    },
    onKeyup() {
      if(this.userInput.length > 3) {
        this.$emit('keyup', this.userInput);
      }
    },
    onFocus() {
      this.hasFocusUserInput = true;
      this.displayList();
      this.$emit('focus');
    },
  },
  watch: {
    searchByOptions() {
      this.displayList();
    },
    value() {
      if(this.value[this.attrTitleName] != undefined) {
        this.userInput = this.value[this.attrTitleName];
      } else {
        this.userInput = '';
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