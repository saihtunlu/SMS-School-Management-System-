<template>
  <vs-prompt
    color="danger"
    title="Delete"
    @cancel="cancel()"
    @accept="setDelete"
    @close="cancel()"
    :active.sync="active_delete"
    accept-text="Yes, I'm sure"
    cancel-text="No, thanks"
  >
    <p class="text-md text-center mt-3">Are you sure to move to delete this {{type}}?</p>
  </vs-prompt>
</template>

<script>
export default {
  props: {
    type: "",
    active: false,
    delete_id: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      active_delete: false
    };
  },
  watch: {
    active(newVal, oldVal) {
      this.active_delete = newVal;
    },
    delete_id(newVal, oldVal) {
      this.delete_id = newVal;
    }
  },
  methods: {
    cancel() {
      this.$emit("cancel");
    },
    setDelete() {
      var id = this.delete_id;
      this.active = false;

      axios
        .post(`/web/delete/`, { id: id, type: this.type })
        .then(response => {
          this.$message({
            message: `Successfully deleted this ${this.type}!`,
            type: "success"
          });
          this.$emit("success");
        })
        .catch(error => {
          console.log("error: ", error);
          this.$message({
            message: "Oops! There is an error while deleting!",
            type: "success"
          });

          this.$emit("error");
        });
    }
  }
};
</script>
