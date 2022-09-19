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
            Category
            <Button v-if="isWritePermitted" class="ivu-ml" type="default" @click="addModal = true">
              <Icon type="md-add" /> Add Category
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Icon Image</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(category, i) in categoryLists" :key="i">
                <td>{{ i + 1 }}</td>
                <td class="_table_name">
                  {{ category.categoryName }}
                </td>
                <td class="table_image">
                  <img :src="category.iconImage" />
                </td>
                <td>{{ category.created_at }}</td>
                <td>
                  <Button
                    v-if="isUpdatePermitted"
                    type="success"
                    size="small"
                    style="margin-right: 5px"
                    @click="showEditModal(category, i)"
                  >
                    Edit
                  </Button>
                  <Button
                    v-if="isDeletePermitted"
                    type="error"
                    size="small"
                    @click="showDeletingModal(category, i)"
                    :loading="category.isDeleting"
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
        title="Add Category"
        width="400px"
        :mask-closable="false"
      >
        <Input
          class="ivu-mb"
          v-model="data.categoryName"
          prefix="ios-aperture-outline"
          placeholder="Add Category Name"
        />

        <Upload
          :headers="{
            'x-csrf-token': token,
            'X-Requested-With': 'XMLHttpRequest',
          }"
          type="drag"
          action="/app/uploads"
          ref="uploads"
          :on-error="handleError"
          :on-success="handleSuccess"
          :format="['jpg', 'jpeg', 'png']"
          :max-size="2048"
          :on-format-error="handleFormatError"
          :on-exceeded-size="handleMaxSize"
        >
          <div style="padding: 20px 0">
            <Icon
              type="ios-cloud-upload"
              size="52"
              style="color: #3399ff"
            ></Icon>
            <p>Click or drag Icon-Image here to upload</p>
          </div>
        </Upload>
        <!-- <div class="demo-upload-list" v-if="data.iconImage">
            <img :src="`/uploads/${data.iconImage}`" />
            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage" />
            </div>
        </div> -->
        <div class="image_thumb" v-if="data.iconImage">
          <img class="img_thumb" :src="`${data.iconImage}`" />
          <span class="upload-list-cover">
            <Icon type="ios-trash-outline" @click="deleteImage" />
          </span>
        </div>

        <template #footer>
          <Button type="error" @click="addModal = false">Close</Button>
          <Button
            type="primary"
            @click="addCategory"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding..." : "Add Category" }}</Button
          >
        </template>
      </Modal>

      <!-- Edit Modal start -->

      <Modal
        v-model="editModal"
        title="Edit Category"
        width="450px"
        :mask-closable="false"
      >
        <Input
          class="ivu-mb"
          v-model="editData.categoryName"
          prefix="ios-aperture-outline"
          placeholder="Add Category Name"
        />

        <Upload
          v-show="isIconImageNew"
          :headers="{
            'x-csrf-token': token,
            'X-Requested-With': 'XMLHttpRequest',
          }"
          type="drag"
          action="/app/uploads"
          ref="editDatauploads"
          :on-error="handleError"
          :on-success="handleSuccess"
          :format="['jpg', 'jpeg', 'png']"
          :max-size="2048"
          :on-format-error="handleFormatError"
          :on-exceeded-size="handleMaxSize"
        >
          <div style="padding: 20px 0">
            <Icon
              type="ios-cloud-upload"
              size="52"
              style="color: #3399ff"
            ></Icon>
            <p>Click or drag Icon-Image here to upload</p>
          </div>
        </Upload>

        <div class="image_thumb" v-if="editData.iconImage">
          <img class="img_thumb" :src="`${editData.iconImage}`" />
          <span class="upload-list-cover">
            <Icon type="ios-trash-outline" @click="deleteImage(false)" />
          </span>
        </div>

        <template #footer>
          <Button type="error" @click="closeEditModal">Close</Button>
          <Button
            type="primary"
            @click="editCategory"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Updating..." : "Update Category" }}</Button
          >
        </template>
      </Modal>

      <!-- Edit Modal End -->

      <!-- Delete Modal start -->

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
        categoryName: "",
        iconImage: "",
      },
      addModal: false,
      editModal: false,
      showDeleteModal: false,
      isAdding: false,

      categoryLists: [],

      editData: {
        categoryName: "",
        iconImage: "",
      },

      index: -1,
      isDeleting: false,
      deleteItem: {},
      deletingIndex: -1,
      token: "",

      isIconImageNew: false,
      isEditingItem: false,
    };
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
        this.categoryLists.splice(this.deletingIndex, 1);
      }
    },
  },

  methods: {

     handleSuccess(res, file) {
        res = `/uploads/${res}`;
        if (this.isEditingItem) {
          return (this.editData.iconImage = res);
        }
        this.data.iconImage = res;
      },
      
    async addCategory() {
      if (this.data.categoryName.trim() == "")
        return this.info("Category name can not be empty");
      if (this.data.iconImage.trim() == "")
        return this.info("Icon Image can not be empty");

      this.data.iconImage = `${this.data.iconImage}`;

      const res = await this.callApi("post", "app/create_category", this.data);
      if (res.status === 201) {
        this.categoryLists.unshift(res.data);
        this.success("Category has been added successfully");
        this.addModal = false;
        this.data.categoryName = "";
        this.data.iconImage = "";
        this.$refs.uploads.clearFiles();
      } else {
        if (res.status === 422) {
          if (res.data.errors.categoryName) {
            this.error(res.data.errors.categoryName[0]);
          }
          if (res.data.errors.iconImage) {
            this.error(res.data.errors.iconImage[0]);
          }
        } else {
          this.errorD();
        }
      }
    },

    async editCategory() {
      if (this.editData.categoryName.trim() == "")
        return this.info("Category name can not be empty");
      if (this.editData.iconImage.trim() == "")
        return this.info("Icon Image can not be empty");
      const res = await this.callApi(
        "post",
        "app/edit_category",
        this.editData
      );
      if (res.status === 200) {
        this.categoryLists[this.index].categoryName = this.editData.categoryName;
        this.categoryLists[this.index].iconImage = this.editData.iconImage;
        this.success("Category been Updated successfully");
        this.editModal = false;
      } else {
        if (res.status == 422) {
          if (res.data.errors.categoryName) {
            this.error(res.data.errors.categoryName[0]);
          }
          if (res.data.errors.iconImage) {
            this.error(res.data.errors.iconImage[0]);
          }
        } else {
          this.errorD();
        }
      }
    },

    showEditModal(category, i) {
      this.deletingIndex = i;
      let obj = {
        id: category.id,
        categoryName: category.categoryName,
        iconImage: category.iconImage,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = i;
      this.isEditingItem = true;
    },
    closeEditModal() {
      this.isEditingItem = false;
      this.editModal = false;
    },

    showDeletingModal(category, i) {
      this.deletingIndex = i;
      const deleteModelObj = {
        showDeleteModal: true,
        deleteUrl: "app/delete_category",
        data: {id : category.id},
        deletingIndex: i,
        isDeleted: false,
      };

      this.$store.commit("setDeletingModalObj", deleteModelObj);
      // this.showDeleteModal = true;
      // this.deleteItem = tag;
      // this.deletingIndex = i;
    },

    async deleteImage(isAdd = true) {
      let image;
      if (!isAdd) {
        this.isIconImageNew = true;
        image = this.editData.iconImage;
        this.editData.iconImage = "";
        this.$refs.editDatauploads.clearFiles();
      } else {
        image = this.data.iconImage;
        this.data.iconImage = "";
        this.$refs.uploads.clearFiles();
      }

      const res = await this.callApi("post", "app/delete_image", {
        imageName: image,
      });
      if (res.status != 200) {
        this.data.iconImage = image;
        this.errorD();
      }
    },

    
  },

  async created() {
    this.token = window.Laravel.csrfToken;
    const res = await this.callApi("get", "app/get_category");
    if (res.status == 200) {
      this.categoryLists = res.data;
    } else {
      this.errorD();
    }
  },
};
</script>

