<template>
  <div>
    <Modal
      v-model="getDeleteModalObj.showDeleteModal"
      :mask-closable="false"
      width="360"
    >
      <template #header>
        <p class="d-flex align-items-center justify-content-center " style="color: red; text-align: center; min-height:40px">
          <Icon type="md-alert" size="32" />
          <span class="ml-2">Delete confirmation</span>
        </p>
      </template>
      <div style="text-align: center; color: black">
        <p>Are you sure you want to delete this ?</p>
      </div>
      <template #footer>
        <Button type="default" @click="closeModal">Close</Button>
        <Button
          type="error"
          :loading="isDeleting"
          :disabled="isDeleting"
          @click="deleteTag"
          >Delete</Button
        >
      </template>
    </Modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      isDeleting: false,
    };
  },

  methods: {
    async deleteTag() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        this.getDeleteModalObj.deleteUrl,
        this.getDeleteModalObj.data
      );
      if (res.status == 200) {
        //this.tags.splice(this.deletingIndex, 1);
        this.success("Data has been successfully Deleted");
        this.$store.commit("setDeleteModal", true);
        //this.$store.commit("setDeleteModal", false);
      } else {
        this.errorD();
        this.$store.commit("setDeleteModal", false);
      }
      this.isDeleting = false;
    },
    closeModal() {
      this.$store.commit("setDeleteModal", false);
    },
  },

 computed: {
    ...mapGetters(["getDeleteModalObj"]),
 },

};
</script>

