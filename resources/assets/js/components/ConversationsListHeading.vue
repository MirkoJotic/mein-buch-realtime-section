<template>
    <div class="sidebar-header">
        <div class="header-user online">
            <img class="user-avatar pull-left" src="/images/avatar.jpg">

            <div class="user-info pull-left">
                <div class="user-name">{{ userEmail }}</div>
                <div class="user-status">online <i class="fa fa-caret-down"></i></div>
            </div>
            <div class="user-info pull-right">
                <a class="close-chat" @click="toggleSidebar()">
                    <i class="icon ion-android-close"></i>
                </a>
            </div>
        </div>
        <ul class="header-actions pull-right">
            <li id="action-add">
                <a>
                    <i class="icon ion-ios-chatbubble-outline" @click="toggleCreateNewConversation()"></i>

                </a>
            </li>
            <li id="action-my-conversation">
                <a>
                    <i style="color:#faa431;" class="icon ion-ios-person-outline"></i>
                </a>
            </li>
            <li id="action-members">
                <a>
                    <i class="icon ion-ios-people-outline"></i>
                </a>
            </li>
            <li id="action-more">
                <a>

                    <i class="icon ion-ios-gear-outline"></i>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
  mounted() {
  },
  methods: {
      toggleSidebar: function() {
        this.$store.dispatch('openSidebar')
      },
      toggleCreateNewConversation: function() {
        this.$store.dispatch('toggleNewConversationComponent')
      }
    },
  computed: {
    userEmail: function () {
      return this.$store.state.currentUser.email;
    }
  }
}



</script>
