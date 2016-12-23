<template>
    <div class="conversation-list-body-item">
            <li @click="openConversation(thread.id)" class="contact offline">
                <div class="task-title">
                    {{ thread.task.title }}
                    </div>
                <div class="contact-avatar">
                    <img src="/images/avatar.jpg">
                </div>
                <div class="contact-info">
                    <div v-html="implodedParticipants(thread.participants)"></div>

                    <div class="contact-last-message">{{ lastMessage }}</div>
                    <div class="contact-last-chat-time">
                        <i class="icon ion-ios-clock-outline"> 21 Dec</i>
                    </div>
                </div>
            </li>
        </div>
</template>

<script>

const socket = io('http://127.0.0.1:3000');

export default {
  mounted() {
    console.log("conversations list item ready")
    socket.on("thread."+this.thread.id+":App\\Events\\NewMessage", function(message) {
      this.$store.dispatch('addMessage', message )
    }.bind(this))
  },
  props: ['thread'],
  methods: {
    implodedParticipants(participantsArray) {
    var unknown = "Group"
        if (participantsArray.length > 2)
         return  unknown

         return '<div class="contact-name">' + participantsArray.find(p=>p.email != this.$store.state.currentUser.email).email + '</div>'
      var participants = ""
      var joiner = ""
      $.each(participantsArray, function(index, participant){
        if ( index != 0 )
          joiner = ", "
        participants += joiner + participant.email
      })
      return '<div class="contact-name">' + participants + '</div>';
    },
    openConversation(id) {
      this.$store.dispatch('setConversationId', id)
      this.$store.dispatch('openConversation')
    }
  },
  computed: {
    lastMessage: function() {
      return this.thread.messages[this.thread.messages.length - 1].content
    }
  }
}
</script>
