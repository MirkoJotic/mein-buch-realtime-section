<template>
  <div>
    <ul>
      <li v-for="message in getCurrentConversation.messages">{{ message.content }}</li>
    </ul>
  </div>
</template>

<script>
  export default {
    mounted() {
      console.log('active conversation body ready...')
    },
    methods: {
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