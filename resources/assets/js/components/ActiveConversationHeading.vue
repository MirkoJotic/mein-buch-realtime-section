<template>
  <div>
    <div>
      <span>Participants: </span>
      <span>{{ participants }}</span>
    </div>
    <button @click="backToConversationsList()">Back</button>
  </div>
</template>

<script>
  export default {
    mounted() {
      console.log("active conversation heading")
    },
    methods: {
      backToConversationsList: function() {
        console.log(this.$store.state.show_conversation ? 'On' : 'Off')
        this.$store.dispatch('closeConversation')
        console.log(this.$store.state.show_conversation ? 'On' : 'Off')
      }
    },
    computed: {
      participants: function() {
        var join = ""
        var participantsString = ""
        $.each(this.$store.getters.currentConversation.participants, function(index, participant){
          if(index != 0) join = ", "
          participantsString += join + participant.email
        })
        return participantsString
      }
    }
  }
</script>