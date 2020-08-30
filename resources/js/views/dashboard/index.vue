<template>
  <div>
    <admin @calendarEvent="event" />
  </div>
</template>

<script>
import admin from "./admin.vue";
export default {
  components: {
    admin
  },
  created() {
    var index = this.$route.meta.index;
    this.$emit("index", index);
  },
  methods: {
    event(data) {
      console.log("data: ", data);
      this.$emit("event", data);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>