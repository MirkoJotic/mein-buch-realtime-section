<template>
  <div class="conversation-list-body-item">
    <div @click="openConversation(thread.id)">
      <div>{{ implodedParticipants(thread.participants) }}</div>
      <div>{{ thread.task.title }}</div>
      <div>{{ lastMessage }}</div>
    </div>
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
      var participants = ""
      var joiner = ""
      $.each(participantsArray, function(index, participant){
        if ( index != 0 )
          joiner = ", "
        participants += joiner + participant.email
      })
      return participants
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

<style>

.conversation-list-body-item {
  min-height: 2.5rem;
  background-color: grey;
  margin: 0.5rem;
  padding: 0.5rem;
}

.conversation-list-body-item:hover {
  background-color: white;
  cursor: pointer;
}
</style>