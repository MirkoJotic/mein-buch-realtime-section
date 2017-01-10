<template>
    <transition name="slide-fade">
        <div :class="{ 'sidebar': sidebarOpen}" v-show="sidebarOpen">
            <div v-show="sidebarOpen && ! conversationOpen && ! newConversationOpen">
                <conversations-list></conversations-list>
            </div>
            <div v-if="conversationOpen && ! newConversationOpen">
                <active-conversation></active-conversation>
            </div>
            <div v-if="newConversationOpen">
                <create-new-conversation></create-new-conversation>
            </div>
        </div>
    </transition>
</template>

<script>
const socket = io('http://127.0.0.1:3000');

export default {
        mounted() {
            setTimeout(this.openNewThreadSocket, 1000)
        },
        data() {
          return {

          }
        },
        methods: {
            openNewThreadSocket: function() {
                console.log("Socket OPENED ln 43 Sidebar.vue")
                socket.on("newthread."+this.$store.state.currentUser.id+":App\\Events\\NewThread", function(message) {
                   console.log("Socket received NEW THREAD")
                   console.log(message)
                   this.$store.dispatch('addConversation', message.thread )
                }.bind(this))
            }
        },
        computed: {
          sidebarOpen: function() {
            return this.$store.state.show_sidebar
          },
          conversationOpen: function() {
            return this.$store.state.show_conversation
          },
          conversations: function() {
            return this.$store.state.conversations
          },
          newConversationOpen: function() {
            return this.$store.state.show_new_conversation
          }
        }
    }



</script>

<style scoped>
    .slide-fade-enter-active {
  transition: all .8s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-active {
  transform: translateX(280px);
 /* transform:translate3d(0%, 0px, 0px);*/
  opacity: 0;
}
</style>


