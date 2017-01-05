<template>
  <div>
    <div class="sidebar-header">
      CREATE NEW CONVERSATION
      <div class="sidebar-body">
        <input type="text" v-model="s" @keyup="setTimeoutToPopulatePeopleList()" />
        <ul class="lst" v-show="s.length > 0">
          <li class="lst-itm"
              v-for="person in people"
              @click="addPersonToConversation(person.id)"
          >{{ person.email }}</li>
        </ul>
      </div>
    </div>
  </div>
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
      addPersonToConversation(uid) {
        console.log(uid)
        this.$http.post('/chat/initiate/private', {user_id: uid}).then(
          (response) => {
            //console.log(response)
            //this.$store.dispatch('addConversation', response.body)
          },
          (response) => {
            console.log("HANDLE populatePeopleList ERROR")
          }
        ).bind(this);
      }

    }
  }
</script>
<style>
  .lst {
    list-style: none;
    background-color: white;
  }
  .lst-itm {
    max-width: 150px;
    border-bottom: 1px solid grey;
  }
</style>