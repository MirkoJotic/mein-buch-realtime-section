<template>
  <div>
    <input v-on:keyup.enter="submitForm()" v-model="content" type="text" name="content" class="">
  </div>
</template>

<script>
  export default {
    mounted() {
      console.log('message form ready...')
    },
    data: function() {
      return {
        content: ''
      }
    },
    methods: {
      submitForm() {
        if(this.validateForm())
          this.sendMessage()
      },
      validateForm() {
        if(this.content !== "") {
          return true
        }
        return false
      },
      sendMessage() {
        var data = {
          content: this.content,
          thread_id: this.getCurrentConversation.id
        }
        this.$http.post('/sendmessage', data).then(
          (response) => {
            this.content = ""
            // success callback do nothing as updating message feed is done through socket
          },
          (response) => {
            // error callback I guess we should log it somewhere
          });
      }
    },
    computed: {
      getCurrentConversation: function() {
        return this.$store.getters.currentConversation
      }
    }
  }
</script>