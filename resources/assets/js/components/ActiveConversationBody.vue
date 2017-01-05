<template>

    <div  class="sidebar-body">
        <ul id="conversation-area" class="messages-list">
            <li v-for="message in messages"
                :class="{ message: true, reply: isOtherThanCurrentUser(message) }"
            >
                <div class="message-info">
                    <div class="contact-name">{{ sentBy(message) }}</div>
                    <div class="message-time">{{ message.created_at }}</div>
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
      this.currentUserId = this.$store.state.currentUser.id
      this.$store.dispatch('markAllMessagesAsSeen')
    },
    data: function () {
      return {
        currentUserId: null
      }
    },
    methods: {

      isOtherThanCurrentUser: function(message) {
        return this.currentUserId != message.user_id
      },
      sentBy(message) {
        if(message.user_id == this.currentUserId)
            return 'Me'

        return this.$store.getters.currentConversationParticipants.find(p => p.id == message.user_id).email
      }
    },
    computed: {
      activeConversationId: function() {
        return this.$store.state.conversation_id
      },
      messages: function() {
        return this.$store.getters.currentConversationMessages
      }
    }
  }
</script>