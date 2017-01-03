<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <div class="conversation-list-body-item">
            <li @click="openConversation(thread.id)" class="contact offline">
                <div class="task-title">
                    {{ thread.type }}
                    <!--{{ thread.task ? thread.task.title : thread.type }}-->
                    </div>
                <div class="contact-avatar">
                    <img src="/images/avatar.jpg">
                </div>
                <div class="contact-info">
                    <div v-html="participants(thread.participants)"></div>

                    <div class="contact-last-message">{{ lastMessage.content }}</div>
                    <div class="contact-last-chat-time">
                        <i class="icon ion-ios-clock-outline">{{ lastMessage.created_at }}</i>
                    </div>
                </div>
                <div v-bind:class="{ active: addCls }" class="pull-right">

                        {{ unreadMessages !== 0 ? unreadMessages : ''}}

                </div>
            </li>
        </div>
</template>

<script>

const socket = io('http://127.0.0.1:3000');

export default {
    mounted() {
        socket.on("thread."+this.thread.id+":App\\Events\\NewMessage", function(message) {
            this.$store.dispatch('addMessage', message )
            this.$store.dispatch('addUnseenMessages', message.thread)
            if( this.$store.state.show_conversation && this.$store.state.conversation_id !== null )
                this.markMessageAsSeen()
        }.bind(this))
    },
    props: ['thread'],
    data: function() {
        return {
            addCls:true
        }
    },
    methods: {
        markMessageAsSeen()
        {
            this.$http.post('/chat/messages/read', { thread_id: this.thread.id }).then(
                (response) => {  },
                (response) => { console.log(response.body) }
            )
            this.$store.dispatch('markAllMessagesAsSeen')
        },
        participants(participants)
        {
            if (participants.length > 2)
                return "Group"

            var participantsHTML = "";
            $.each(participants, function(index, participant){
                if(participant.email != this.$store.state.currentUser.email)
                    participantsHTML += '<div class="contact-name">'+participant.email+'</div>';
            }.bind(this))
            return participantsHTML
        },
        openConversation(id)
        {
            this.$store.dispatch('setConversationId', id)
            this.$store.dispatch('openConversation')
            this.markMessageAsSeen()
        }
    },
    computed: {
        lastMessage: function() {
            return this.thread.messages[this.thread.messages.length - 1]
        },
        unreadMessages: function() {
            var unreadMessagesCount = 0;
            for (var i = this.thread.unseen_messages.length - 1; i >= 0; i--) {
                if( this.thread.unseen_messages[i].user_id === this.$store.getters.currentUserId )
                    unreadMessagesCount++
            }
            return unreadMessagesCount
        },
        addCls: function() {
            if( this.unreadMessages > 0 )
                return 'unread-messages-container'
            return ''
        }
    }
}
</script>
