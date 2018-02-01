<template>
  <div class="panel-body">
    <form>
      <vue-form-generator :schema="schema" :model="model" :options="formOptions">
      </vue-form-generator>
    </form>
  </div>
</template>
<script>

import Vue from "vue";
import VueFormGenerator from "vue-form-generator";
import axios from 'axios';

Vue.use(VueFormGenerator);

export default {
  data () {
    return {
      model: {},
      schema: {},
      formOptions: {}
    }
  },

  created() {
    axios.get('http://CHANGEME/webform_vue/contact/elements?_format=json')
    .then(response => {
      this.model = response.data.model;
      this.schema = response.data.schema;
      this.formOptions = response.data.formOptions;
    });
  }
}
</script>


<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
