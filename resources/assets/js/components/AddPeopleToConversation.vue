<template>
  <div class="">
      <div>
        <h4>Search for people and click on them to add them to chat</h4>
        <input  type="text"
                name="search"
                v-model="s"
                placeholder="Type First or Last name or Email..."
                @keyup="setTimeoutToUsersList()"
        />
      </div>
      <div>
        <ul class="user-list">
          <li v-for="user in users" @click="addUserToConversation(user.id)">{{ user.email }}</li>
        </ul>
      </div>
      <div>
        <button @click="closeWindow()" class="btn btn-primary">Cancel</button>
      </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("add people modal ready")
    this.updateUsersList()
  },
  data: function() {
    return {
      s: '',
      timer: 0,
      users: []
    }
  },
  methods: {
    setTimeoutToUsersList() {
      window.clearTimeout(this.timer)
      this.timer = setTimeout(this.updateUsersList, 800)
    },
    updateUsersList() {
      this.$http.post('/addtoconversationuserlist', {s: this.s}).then(
        (response) => {
          console.log(response.status)
          this.users = response.body
        },
        (response) => {
        }
      ).bind(this)
    },
    addUserToConversation(uid) {
      var data = {
        uid: uid,
        thread_id: this.$store.state.conversation_id
      }
      this.$http.post('/addusertoconversation', data).then(
        (response) => {
          console.log(response.body)
          this.$store.dispatch( 'addUserToCurrentConversation', response.body )
        },
        (response) => {
          console.log(response.body)
        }
      ).bind(this)
    },
    closeWindow() {
      this.$store.dispatch('toggleShowAddPeopleToConversation')
    }
  }
}
</script>

<style>
.user-list {
  list-style: none;
  height: 2.5rem;
}
</style>