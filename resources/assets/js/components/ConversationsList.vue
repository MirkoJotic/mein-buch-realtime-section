<template>
  <div class="conversations-list-container">
    <conversations-list-heading></conversations-list-heading>
    <conversations-list-body></conversations-list-body>
  </div>
</template>

<script>

export default {
  mounted() {
    this.$http.post('/mythreads', this.filter).then(
      (response) => {
        // If this doesn't fire of someone should be notified
        console.log("response.body")
        console.log(response)
        this.$store.dispatch('setConversations', response.body.threads_with_messages_and_task)
        this.$store.dispatch('setCurrentUser', { id: response.body.id, email: response.body.email })
      },
      (response) => {
        console.log("Rejected Promise 20 ln ConversationsList")
        console.log(response.body)
      }
    );
  },
  methods: {
  },
  computed: {
    filter: function() {
      return {};
    }
  }
}
</script>

<style>

</style>