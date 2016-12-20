<template>
  <div>
    <span>{{ userEmail }}</span>
  </div>
</template>

<script>
export default {
  mounted() {

  },
  computed: {
    userEmail: function () {
      return this.$store.state.currentUser.email;
    }
  }
}
</script>