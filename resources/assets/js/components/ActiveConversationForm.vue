<template xmlns:v-on="http://www.w3.org/1999/xhtml">


    <div class="append-icon">
      <input v-on:keyup.enter="submitForm()" v-model="content" type="text" name="content" class="form-control">
      <i class="fa fa-paperclip text-primary" aria-hidden="true"></i>
    </div>

</template>

<script>
  export default {
    mounted() {
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
        var content = this.content
        this.content = ""
        var data = {
          content: content,
          thread_id: this.getCurrentConversation.id
        }
        this.$http.post('/chat/send/message', data).then(
          (response) => {
            this.$store.dispatch('addMessage', response.body )
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