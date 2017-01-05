<template>
    <div class="sidebar-body">
        <div class="contacts-search">
            <input type="text" class="searchinput" placeholder="Search Members">
            <i class="searchicon icon ion-ios-search"></i>
        </div>
        <ul class="contacts-list">
            <div v-for="thread in threads">
                <conversations-list-body-item :thread="thread"></conversations-list-body-item>
            </div>
        </ul>
    </div>
</template>
<script>
export default {
  mounted() {
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
