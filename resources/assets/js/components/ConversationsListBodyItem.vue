<template>
    <div class="conversation-list-body-item">
            <li @click="openConversation(thread.id)" class="contact offline">
                <div class="task-title">
                    {{ thread.task ? thread.task.title : thread.type }}
                    </div>
                <div class="contact-avatar">
                    <img src="/images/avatar.jpg">
                </div>
                <div class="contact-info">
                    <div v-html="participants(thread.participants)"></div>

                    <div class="contact-last-message">{{ lastMessage.content }}</div>
                    <div class="contact-last-chat-time">
                        <i class="icon ion-ios-clock-outline">{{ lastMessage.created_at }}</i>
                    </div>
                </div>
            </li>
        </div>
</template>

<script>


const socket = io('http://127.0.0.1:3000');

export default {
  mounted() {
    socket.on("thread."+this.thread.id+":App\\Events\\NewMessage", function(message) {
      this.$store.dispatch('addMessage', message )
    }.bind(this))
  },
  props: ['thread'],
  methods: {
    participants(participants)
    {
        if (participants.length > 2)
            return "Group"

        var participantsHTML = "";
        $.each(participants, function(index, participant){
            if(participant.email != this.$store.state.currentUser.email)
                participantsHTML += '<div class="contact-name">'+participant.email+'</div>';
        }.bind(this))
        return participantsHTML
    },
    openConversation(id)
    {
        this.$store.dispatch('setConversationId', id)
        this.$store.dispatch('openConversation')
    }
  },
  computed: {
      lastMessage: function() {
        return this.thread.messages[this.thread.messages.length - 1]
    }
  }
}
</script>
