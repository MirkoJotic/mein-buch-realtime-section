<template>
    <div :class="{ 'sidebar': sidebarOpen}" v-show="sidebarOpen">

        <transition
                name="sidebar-transition"
                enter-active-class="animated slideInRight"
                leave-active-class="animated slideOutRight"
        >
            <div v-show="sidebarOpen && ! conversationOpen">
                <conversations-list></conversations-list>
            </div>
        </transition>


        <transition
                name="sidebar-transition"
                enter-active-class="animated slideInRight"
                leave-active-class="animated slideOutRight"
        >
            <div v-if="conversationOpen" class="">
                <active-conversation></active-conversation>
            </div>
        </transition>

    </div>
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
                        console.log("Socket Msg receiv. ln 44 Sidebar.vue")
                        console.log(message)
                        if(message.taskThreadExists == false)
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
          }
        }
    }

</script>
