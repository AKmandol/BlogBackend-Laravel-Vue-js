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
            Tags
            <Button v-if="isWritePermitted" class="ivu-ml" type="default" @click="addModal = true">
              <Icon type="md-add" /> Add Tag
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Tag Name</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(tag, i) in tags" :key="i">
                <td>{{i + 1}}</td>
                <td class="_table_name">
                  {{ tag.tagName }}
                </td>
                <td>{{ tag.created_at }}</td>
                <td>
                  <Button 
                    v-if="isUpdatePermitted"
                    type="success"
                    size="small"
                    style="margin-right: 5px"
                    @click="showEditModal(tag, i)"
                  >
                    Edit
                  </Button>
                  <Button
                    v-if="isDeletePermitted"
                    type="error"
                    size="small"
                    @click="showDeletingModal(tag, i)"
                    :loading="tag.isDeleting"
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
        title="Add Tag"
        width="350px"
        :mask-closable="false"
      >
        <Input
          v-model="data.tagName"
          prefix="ios-pricetags-outline"
          placeholder="Add Tag Name"
        />

        <template #footer>
          <Button type="error" @click="addModal = false">Close</Button>
          <Button
            type="primary"
            @click="addTag"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding..." : "Add Tag" }}</Button
          >
        </template>
      </Modal>

      <!-- Edit Modal start -->

      <Modal
        v-model="editModal"
        title="Edit Tag"
        width="350px"
        :mask-closable="false"
        :closable="false"
      >
        <Input
          v-model="editData.tagName"
          prefix="ios-pricetags-outline"
          placeholder="Add Tag Name"
        />

        <template #footer>
          <Button type="error" @click="editModal = false">Close</Button>
          <Button
            type="primary"
            @click="editTag"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Updating..." : "Update Tag" }}</Button
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
        tagName: "",
      },
      addModal: false,
      editModal: false,
      showDeleteModal: false,
      isAdding: false,

      tags: [],
      editData: {
        tagName: "",
      },

      index: -1,
      isDeleting: false,
      deleteItem: {},
      deletingIndex: -1,
    };
  },

  methods: {
    async addTag() {
      if (this.data.tagName.trim() == "")
        return this.info("Tag name can not be empty");
      const res = await this.callApi("post", "app/create_tag", this.data);
      if (res.status === 201) {
        this.tags.unshift(res.data);
        this.success("Tag has been added successfully");
        this.addModal = false;
        this.data.tagName = "";
      } else {
        if (res.status == 422) {
          if (res.data.errors.tagName) {
            this.error(res.data.errors.tagName[0]);
          }
        } else {
          this.errorD();
        }
      }
    },

    async editTag() {
      if (this.editData.tagName.trim() == "")
        return this.info("Tag name can not be empty");
      const res = await this.callApi("post", "app/edit_tag", this.editData);
      if (res.status === 200) {
        this.tags[this.index].tagName = this.editData.tagName;
        this.success("Tag has been Updated successfully");
        this.editModal = false;
      } else {
        if (res.status == 422) {
          if (res.data.errors.tagName) {
            this.error(res.data.errors.tagName[0]);
          }
        } else {
          this.errorD();
        }
      }
    },
    showEditModal(tag, index) {
      let obj = {
        id: tag.id,
        tagName: tag.tagName,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },

    // async deleteTag() {
    //   this.isDeleting = true;
    //   const res = await this.callApi("post", "app/delete_tag", this.deleteItem);
    //   if (res.status == 200) {
    //     this.tags.splice(this.deletingIndex, 1);
    //     this.success("Tag has been successfully Deleted");
    //   } else {
    //     this.errorD();
    //   }
    //   this.isDeleting = false;
    //   this.showDeleteModal = false;
    // },

    showDeletingModal(tag, i) {
      this.deletingIndex = i;
      const deleteModelObj = {
        showDeleteModal: true,
        deleteUrl: "app/delete_tag",
        data: {id : tag.id},
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
    //console.log(this.isWritePermitted);
    const res = await this.callApi("get", "app/get_tags");
    if (res.status == 200) {
      this.tags = res.data;
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
        this.tags.splice(this.deletingIndex, 1);
      }
    },
  },

  // 	async created(){
  // 		const res = await this.callApi('post','/app/create_tag',{tagName:'testtag'});

  // 		if(res.status==200){
  // 			//console.log(res)
  // 		}else{
  // 			console.log(res)
  // 			console.log('running')
  // 		}
  // 	}
};
</script>

