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
    export default {
        mounted() {
        },
        data() {
          return {

          }
        },
        methods: {
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
