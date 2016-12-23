<template>
  <div>
    Active conversation
    <active-conversation-heading></active-conversation-heading>
    <!--<active-conversation-message-feed v-show="!show_add_people_to_conversation"></active-conversation-message-feed>-->
    <div v-show="!show_add_people_to_conversation">
      <active-conversation-body></active-conversation-body>
      <active-conversation-form></active-conversation-form>
    </div>
    <add-people-to-conversation v-if="show_add_people_to_conversation"></add-people-to-conversation>
  </div>
</template>
<script>
  export default {
    mounted() {

    },
    computed: {
      show_add_people_to_conversation: function() {
        return this.$store.state.show_add_people_to_conversation
      }
    }
  }

</script>