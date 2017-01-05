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
            <div class="user-info pull-right">
                <a class="close-chat" @click="toggleSidebar()">
                    <i class="icon ion-android-close"></i>
                </a>
            </div>
        </div>

        <active-conversation-add-people v-if="showAddPersonToConversation"></active-conversation-add-people>

        <ul class="header-actions pull-right">
            <li>
                <a @click="showAddPersonToConversation = ! showAddPersonToConversation">
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
    },
    data: function() {
      return {
        showAddPersonToConversation: false
      }
    },
    methods: {
      backToConversationsList: function() {
        this.$store.dispatch('closeConversation')
      },
      toggleSidebar: function() {
        this.$store.dispatch('openSidebar')
      }
    },
    computed: {


      participants: function() {
/*          var participantsWithoutCurrentUser = this.$store.getters.currentConversation.participants.filter(function(participant){
            return participant.email != this.$store.state.currentUser.email
            }.bind(this))
          $.each(participantsWithoutCurrentUser, function(index, participant){
            participantsEmails.push(participant.email)
            })
            return participantsEmails
*/
          var participantsEmails = []
          var currentUserEmail = this.$store.state.currentUser.email
          var participants = this.$store.getters.currentConversationParticipants
          if ( participants.length == 0 )
            return participantsEmails
          var participantsWithoutCurrentUser = participants.filter(function(participant){
            return participant.email != currentUserEmail
          })
          $.each(participantsWithoutCurrentUser, function(index, participant){
            participantsEmails.push(participant.email)
          })
          return participantsEmails
      }
    }
  }


</script>



