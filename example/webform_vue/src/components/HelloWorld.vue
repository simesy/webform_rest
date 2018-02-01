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
Vue.use(VueFormGenerator);

export default {
  data () {
    return {
      model: {
        id: 1,
        name: "John Doe",
        password: "J0hnD03!x4",
        skills: ["Javascript", "VueJS"],
        email: "john.doe@gmail.com",
        status: true
      },

      schema: {
        fields: [
          {
            type: "input",
            inputType: "text",
            label: "ID (disabled text field)",
            model: "id",
            readonly: true,
            disabled: true
          },
          {
            type: "input",
            inputType: "text",
            label: "Name",
            model: "name",
            id: "user_name",
            placeholder: "Your name",
            featured: true,
            required: true
          },
          {
            type: "input",
            inputType: "email",
            label: "E-mail",
            model: "email",
            placeholder: "User's e-mail address"
          },
          {
            type: "input",
            inputType: "password",
            label: "Password",
            model: "password",
            min: 6,
            required: true,
            hint: "Minimum 6 characters",
          }
        ]
      },
      formOptions: {
        validateAfterLoad: true,
        validateAfterChanged: true,
        fieldIdPrefix: 'user-'
      }
    }
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
