<template>
  <div>
    <ul class="message-list">
      <li v-for="message in getCurrentConversation.messages">
        <b>{{ messageBelongsTo(message.user_id) }}:</b> {{ message.content }}
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    mounted() {
      console.log('active conversation body ready...')
    },
    methods: {
      messageBelongsTo(uid) {
        return this.$store.getters.currentConversation.participants.find( participant => participant.id === uid ).email
      }
    },
    computed: {
      activeConversationId: function() {
        return this.$store.state.conversation_id
      },
      getCurrentConversation: function() {
        return this.$store.getters.currentConversation
      }
    }
  }
</script>
<style>
 .message-list {
  list-style: none;

 }

</style>