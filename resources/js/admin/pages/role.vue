<template>
  <div>
    <div class="content">
      <div class="container-fluid mt-3">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div
          class="
            _1adminOverveiw_table_recent
            _box_shadow
            _border_radious
            _mar_b30
            _p20
          "
        >
          <p class="_title14">
            Role Manager
            <Button v-if="isWritePermitted" class="ivu-ml" type="default" @click="addModal = true">
              <Icon type="md-add"/> New Role
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Role Type</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(role, i) in roles" :key="i">
                <td>{{ i + 1 }}</td>
                <td class="_table_name">
                  {{ role.roleName }}
                </td>
                <td>{{ role.created_at }}</td>
                <td>
                  <Button
                    v-if="isWritePermitted"
                    type="success"
                    size="small"
                    style="margin-right: 5px"
                    @click="showEditModal(role, i)"
                  >
                    Edit
                  </Button>
                  <Button
                    v-if="isWritePermitted"
                    type="error"
                    size="small"
                    @click="showDeletingModal(role, i)"
                    :loading="role.isDeleting"
                  >
                    Delete
                  </Button>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <Page :total="100" />
      </div>

      <!-- Modal start -->

      <Modal
        v-model="addModal"
        title="Add New Role"
        width="350px"
        :mask-closable="false"
      >
        <Input
          v-model="data.roleName"
          prefix="ios-pricetags-outline"
          placeholder="Add Role Name"
        />

        <template #footer>
          <Button type="error" @click="addModal = false">Close</Button>
          <Button
            type="primary"
            @click="add"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding..." : "Add Role" }}</Button
          >
        </template>
      </Modal>

      <!-- Edit Modal start -->

      <Modal
        v-model="editModal"
        title="Edit Role"
        width="350px"
        :mask-closable="false"
        :closable="false"
      >
        <Input
          v-model="editData.roleName"
          prefix="ios-pricetags-outline"
          placeholder="Edit Role Name"
        />

        <template #footer>
          <Button type="error" @click="editModal = false">Close</Button>
          <Button
            type="primary"
            @click="edit"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Updating..." : "Update Role" }}</Button
          >
        </template>
      </Modal>

      <!-- Edit Modal End -->

      <deleteModel></deleteModel>

      <!-- Delete Modal End -->

    </div>
  </div>
</template>

<script>
import deleteModel from "../components/deleteModel.vue";
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      data: {
        roleName: "",
      },
      addModal: false,
      editModal: false,
      showDeleteModal: false,
      isAdding: false,

      roles: [],
      editData: {
        roleName: "",
      },

      index: -1,
      isDeleting: false,
      deleteItem: {},
      deletingIndex: -1,
    };
  },

  methods: {
    async add() {
      if (this.data.roleName.trim() == "")
        return this.info("Role name can not be empty");
      const res = await this.callApi("post", "app/create_role", this.data);
      if (res.status === 201) {
        this.roles.unshift(res.data);
        this.success("Role has been added successfully");
        this.addModal = false;
        this.data.roleName = "";
      } else {
        if (res.status == 422) {
          if (res.data.errors.roleName) {
            this.error(res.data.errors.roleName[0]);
          }
        } else {
          this.errorD();
        }
      }
    },

    async edit() {
      if (this.editData.roleName.trim() == "")
        return this.info("Role name can not be empty");
      const res = await this.callApi("post", "app/edit_role", this.editData);
      if (res.status === 200) {
        this.roles[this.index].roleName = this.editData.roleName;
        this.success("Role has been Updated successfully");
        this.editModal = false;
      } else {
        if (res.status == 422) {
          if (res.data.errors.roleName) {
            this.error(res.data.errors.roleName[0]);
          }
        } else {
          this.errorD();
        }
      }
    },
    showEditModal(role, index) {
      let obj = {
        id: role.id,
        roleName: role.roleName,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },

    // async deleteTag() {
    //   this.isDeleting = true;
    //   const res = await this.callApi("post", "app/delete_role", this.deleteItem);
    //   if (res.status == 200) {
    //     this.tags.splice(this.deletingIndex, 1);
    //     this.success("Tag has been successfully Deleted");
    //   } else {
    //     this.errorD();
    //   }
    //   this.isDeleting = false;
    //   this.showDeleteModal = false;
    // },

    showDeletingModal(role, i) {
      this.deletingIndex = i;
      const deleteModelObj = {
        showDeleteModal: true,
        deleteUrl: "app/delete_role",
        data: {id : role.id},
        deletingIndex: i,
        isDeleted: false,
      };

      this.$store.commit("setDeletingModalObj", deleteModelObj);
      //console.log(getDeleteModalObj);
      // this.showDeleteModal = true;
      // this.deleteItem = tag;
      // this.deletingIndex = i;
    },
  },

  async created() {
    const res = await this.callApi("get", "app/get_roles");
    if (res.status == 200) {
      this.roles = res.data;
    } else {
      this.errorD();
    }
  },



  components: {
    deleteModel,
  },

  computed: {
    ...mapGetters(["getDeleteModalObj"]),
  },

  watch: {
    getDeleteModalObj(obj) {
      if (obj.isDeleted) {
        this.roles.splice(this.deletingIndex, 1);
      }
    },
  },


  
};
</script>

