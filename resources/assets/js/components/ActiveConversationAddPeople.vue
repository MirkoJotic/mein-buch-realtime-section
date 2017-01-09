<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <transition name="fade">
        <div class="add-people-to-conversation-container">
            <input class="add-people" type="text" list="people"
                   name="search"
                   v-model="s"
                   placeholder="Type First or Last name or Email..."
                   @keyup="setTimeoutToPopulatePeopleList()"
            >
            <ul class="addPeopleAutocompleteList" v-show="s.length > 0">
                <li v-for="person in people"
                    @click="handle(person.id)"
                    class="addPeopleAutocompleteListItems"
                >
                    <div class="contact-avatar">
                        <img src="/images/avatar.jpg">
                    </div>
                    <div class="user-name">
                        {{ person.email }}
                    </div>
                </li>
            </ul>
        </div>
    </transition>
</template>
<script>
  export default {
    mounted() {
      this.populatePeopleList()

    },
    data: function() {
      return {
        s: "",
        timer: 0,
        people: []
      }
    },
    methods: {
      setTimeoutToPopulatePeopleList() {
        window.clearTimeout(this.timer)
        this.timer = setTimeout(this.populatePeopleList, 800)
      },
      populatePeopleList() {
        this.$http.post('/chat/users/list', {s: this.s}).then(
          (response) => {
            this.people = response.body
          },
          (response) => {
            console.log("HANDLE populatePeopleList ERROR")
          }
        ).bind(this);
      },
      handle(uid) {
        if ( this.$store.state.show_new_conversation ) {
          this.createNewConversation( uid )
        } else {
          var formData = { uid: uid, thread_id: this.$store.state.conversation_id }
          this.addToConversation( formData )
        }
      },
      addToConversation(formData) {
        this.$http.post('/chat/users/add', formData).then(
          (response) => {
            this.$store.dispatch('addUserToCurrentConversation', response.body)
            this.s = ""
          },
          (response) => {
            console.log( "HANDLE addToConversation ERROR" )
          }
        ).bind(this)
      },
      createNewConversation(uid) {
        this.$http.post('/chat/initiate/private', {user_id: uid}).then(
          (response) => {
            this.$store.dispatch('addConversationAfterPrivateConversationStarted', response.body)
          },
          (response) => {
            console.log("HANDLE populatePeopleList ERROR")
          }
        ).bind(this);
      }

    }
  }

</script>

