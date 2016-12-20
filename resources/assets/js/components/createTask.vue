<template>
  <div>
    <div class="row" v-show="!visible">
      <div class="col-sm-offset-5 col-md-offset-5 col-sm-2 col-md-2">
        <button @click="visible = ! visible" class="btn btn-primary">
          Create Task
        </button>
      </div> 
    </div>
    <transition
      name="custom-task-form"
      enter-active-class="animated slideInDown"
      leave-active-class="animated slideOutUp"
    >
      <div class="row" v-show="visible">
        <div class="col-sm-offset-4 col-md-offset-4 col-sm-4 col-md-4">
          <div class="form-group">
            <label class="form-label">Task Title</label>
            <input v-model="title" type="text" class="form-control" />
          </div>
          <div class="form-group">
            <label class="form-label">Task Details</label>
            <input v-model="details" type="text" class="form-control" />
          </div>
          <button @click="submitForm()" type="submit" class="btn btn-primary">
            Submit
          </button>
          <button @click="visible = false" type="button" class="btn btn-default">
            Cancel 
          </button>

          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
    export default {
        mounted() {
//console.log("create-task-mounted");
//console.log("mounted default newTodo value");
//console.log(this.$store.getters.newTodo);
//console.log("set new newTodo value = Vue JS");
//this.$store.dispatch('testingAdd', 'Vue JS');
//console.log(this.$store.getters.newTodo); 
        },
        data() {
          return {
            title: '',
            details: '',
            validated: false,
            visible: false
          }
        },
        methods: {
          validateForm: function() {
            console.log("validate form");
          },
          submitForm: function() {
            var data = {
              _token: $("#csrf").val(),
              title: this.title,
              details: this.details
            };
            $.post("create", data).done(function(response){
              this.clearForm();
              this.visible = false;
            }.bind(this));     
          },
          clearForm: function() {
            this.title = "";
            this.details = "";
          }
        }
    }
</script>
