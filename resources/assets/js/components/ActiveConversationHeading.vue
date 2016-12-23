<template>
    <div class="sidebar-header">
        <div class="header-user online">
            <img class="user-avatar pull-left" src="/images/avatar.jpg">
            <div class="user-info pull-left">
                <div class="user-name" v-for="participant in participants">
                    {{ participant }}
                </div>
                <div class="user-status">online</div>
            </div>
        </div>
        <ul class="header-actions pull-left">
            <li>
                <a>
                    <i class="icon ion-ios-arrow-right"></i>
                </a>
            </li>
        </ul>
        <ul class="header-actions pull-right">
            <li>
                <a @click="backToConversationsList()">
                    <i class="icon ion-ios-personadd-outline"></i>
                </a>
            </li>
            <li>
                <a @click="backToConversationsList()">
                    <i class="icon ion-ios-home-outline"></i>
                </a>
            </li>
        </ul>
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
          var participantsEmails = []
          var participantsWithoutCurrentUser = this.$store.getters.currentConversation.participants.filter(function(participant){
            return participant.email != this.$store.state.currentUser.email
            }.bind(this))
          $.each(participantsWithoutCurrentUser, function(index, participant){
            participantsEmails.push(participant.email)
            })
            return participantsEmails
      }
    }
  }

</script>



