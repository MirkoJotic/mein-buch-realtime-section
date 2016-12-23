<template>
  <div>
    <div>
      <span>Participants: </span>
      <span>{{ participants }}</span>
    </div>
    <button @click="backToConversationsList()">Back</button>
    <button @click="openListOfPeople()">Add Person to chat</button>


  </div>
</template>

<script>
  export default {
    mounted() {
      console.log("active conversation heading")
    },
    data: function() {
      return {
      }
    },
    methods: {
      backToConversationsList: function() {
        console.log(this.$store.state.show_conversation ? 'On' : 'Off')
        this.$store.dispatch('closeConversation')
        console.log(this.$store.state.show_conversation ? 'On' : 'Off')
      },
      openListOfPeople() {
        /*this.$http.post('/allusers').then(
          (response) => {
            console.log(response.body)
            this.$store.dispatch('toggleShowAddPeopleToConversation')
          },
          (response) => {
            console.log(response.body)
          }
        )*/
        console.log(this.$store.state.show_add_people_to_conversation)
        this.$store.dispatch('toggleShowAddPeopleToConversation')
        console.log(this.$store.state.show_add_people_to_conversation)
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
      },
      show_add_people_to_conversation: function() {
        return this.$store.state.show_add_people_to_conversation
      }
    }
  }
</script>