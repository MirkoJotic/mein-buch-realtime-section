<template>
  <div>
    <div v-for="thread in threads">
      <conversations-list-body-item
          :thread="thread"
      ></conversations-list-body-item>
    </div>

  </div>
</template>

<script>
export default {
  mounted() {
    console.log('conversation list ready')
    console.log(this.threads)
  },
  methods: {
  },
  computed: {
    threads: function() {
      return this.$store.getters.conversations
    }
  }
}
</script>
