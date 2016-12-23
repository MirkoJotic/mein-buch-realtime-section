<template>

    <div class="sidebar-body">
        <ul class="messages-list">
            <li class="message" v-for="message in getCurrentConversation.messages">
                <div class="message-info">
                    <div class="contact-name">Lola</div>
                    <div class="message-time">08:13 AM</div>
                </div>
                <div class="message-body">
                    {{ message.content }}
                </div>
            </li>
        </ul>
        <div class="messages-send">
            <active-conversation-form></active-conversation-form>
        </div>
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