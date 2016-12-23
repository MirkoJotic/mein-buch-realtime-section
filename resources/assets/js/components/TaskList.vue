<template>
  <div v-bind:class="{
    'col-md-12': !isSidebarOpen,
    'col-md-8': isSidebarOpen
    }">
    <div>
      <h4 align="center">Tasks</h4>
      <button @click="toggleSidebar()" class="btn btn-default">Toggle Sidebar</button>
      <div class="list-group">
        <div class="list-group-item" v-for="task in tasks">

            <span>{{ task.title}}</span>
            <button @click="openConversation(task.id)" class="chat-button btn btn-primary pull-right">
              Chat
            </button>

        </div>
      </div>
    </div>
  </div>
</template>
<script>
    export default {
        mounted() {
          $.getJSON('/tasks/list', function(data) {
            this.tasks = data;
          }.bind(this));
          this.$store.dispatch('testing')
        },
        data() {
          return {
            tasks: []
          }
        },
        methods: {
          openConversation: function(taskId) {
            this.$http.post('/initiateNegotiation', {taskId: taskId}).then(
              (response) => {
                console.log(response.body)
                if(response.body['thread_exists'] == false)
                  this.$store.dispatch('addConversation', response.body['thread'] )

                this.$store.dispatch('setConversationId', response.body['thread'].id)
                if(this.$store.state.conversation_id != '')
                  this.$store.dispatch('openConversation')

              },
              (response) => {
                console.log(response.status)
                console.log(response.body)
              }
            );
            //this.$store.dispatch('openConversation', taskId)
          },
          toggleSidebar: function() {
            this.$store.dispatch('toggleSidebar')
          }

        },
        computed: {
          isSidebarOpen: function() {
            return this.$store.state.show_sidebar
          },
          isConversationOpen: function() {
            return this.$store.state.show_conversation
          }
        },
    }
</script>

<style>
.chat-button {
  height: 28px;
  padding: 10px;
  padding-top: 0px;
  padding-bottom: 0px;
}
</style>

