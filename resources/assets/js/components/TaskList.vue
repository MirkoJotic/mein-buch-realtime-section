<template>
  <div id="tasks" class="col-md-12">
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
          this.$http.get('/tasks/list').then(
            (response)=>{ this.tasks = response.body },
            (response)=>{/*ERROR*/}
          ).bind(this);
        },
        data() {
          return {
            tasks: []
          }
        },
        methods: {
          openConversation: function(taskId) {
            this.$http.post('/chat/initiate/task', {task_id: taskId}).then(
              (response) => {
                this.handleNewConversation(response.body)
              },
              (response) => {
                console.log(response.status)
                console.log(response.body)
              }
            );
            //this.$store.dispatch('openConversation', taskId)
          },
          handleNewConversation: function(data) {
            if ( data.thread_exists )
              this.$store.dispatch('addConversation', data.thread )

            this.$store.dispatch('setConversationId', data.thread.id )
            console.log("after setConversationId")
            console.log(this.$store.getters.currentConversation)
            console.log("should ^ be ^ conversation object")

            this.$store.dispatch('openConversation')
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

