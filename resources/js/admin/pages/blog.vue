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
            Blogs
            <Button v-if="isWritePermitted" class="ivu-ml" type="default" @click="$router.push('/createBlog')">
              <Icon type="md-add" /> Create Blog
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Title Image</th>
                <th>Categories</th>
                <th>Tags</th>
                <th>Views</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(blog, i) in blogs" :key="i">
                <td>{{i + 1}}</td>
                <td>
                  {{ blog.title }}
                </td>
                <td class="table_image">
                  <img :src="blog.featuredImage" />
                </td>
                <td>
                  <span v-for="(c,j) in blog.cat" :key="j" > <Tag color="blue">{{c.categoryName}}</Tag> </span>
                </td>
                <td>
                  <span v-for="(t,k) in blog.tag" :key="k" > <Tag color="geekblue">{{t.tagName}}</Tag> </span>
                </td>
                <td>
                  {{ blog.views }}
                </td>
                <td>{{ blog.created_at }}</td>
                <td style="min-width:150px">
                  <Button 
                    v-if="isUpdatePermitted"
                    type="success"
                    size="small"
                    style="margin-right: 5px"
                    @click="$router.push(`/editblog/${blog.id}`)"
                  >
                    Edit
                  </Button>
                  <Button
                    v-if="isDeletePermitted"
                    type="error"
                    size="small"
                    @click="showDeletingModal(blog, i)"
                    :loading="blog.isDeleting"
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
        isAdding: false,
        showDeleteModal: false,
        blogs: [],
        index: -1,
        isDeleting: false,
        deleteItem: {},
        deletingIndex: -1,
    }
  },

  methods: {

    showDeletingModal(blog, i) {
      //console.log('delete index', i);
      this.deletingIndex = i;
      const deleteModelObj = {
        showDeleteModal: true,
        deleteUrl: "app/delete_blog",
        data: {id : blog.id},
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
    //console.log(this.permission);
    //this.$store.commit('setUserPERMISSION',this.permission); 
    const res = await this.callApi("get", "app/blog-data");
    if (res.status == 200) {
      this.blogs = res.data;
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
        this.blogs.splice(this.deletingIndex, 1);
      }
    },
  },

};
</script>

