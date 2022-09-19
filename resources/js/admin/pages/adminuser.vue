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
            Admin Users
            <Button v-if="isWritePermitted" class="ivu-ml" type="default" @click="addModal = true">
              <Icon type="md-add" /> Add Admin
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>Role</th>
                <th>Role Id</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->

              <tr v-for="(user, i) in users" :key="i">
                <td>{{ i + 1 }}</td>
                <td>
                  {{ user.fullName }}
                </td>
                <td>
                  {{ user.email }}
                </td>
               <!-- <template v-for="(r,y) in roles" :key="y">                 -->
                <td v-show="user.role_id == 1">
                  Admin
                </td>
                <td v-show="user.role_id == 3">
                  Editor
                </td>
                <td v-show="user.role_id == 2">
                  Moderator
                </td>
                <td v-show="user.role_id == 4">
                 User
                </td>
                <td v-show="user.role_id >= 5 ">
                 User
                </td>
                <!-- </template> -->
                <td>
                  {{ user.role_id }}
                </td>
                <td>{{ user.created_at }}</td>
                <td>
                  <Button
                    v-if="isWritePermitted"
                    type="success"
                    size="small"
                    style="margin-right: 5px"
                    @click="showEditModal(user, i)"
                  >
                    Edit
                  </Button>
                  <Button
                    v-if="isDeletePermitted"
                    type="error"
                    size="small"
                    @click="showDeletingModal(user, i)"
                    :loading="user.isDeleting"
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
        title="Add Admin User"
        width="400px"
        :mask-closable="false"
      >
        <Input
          v-model="data.fullName"
          type="text"
          prefix="md-person"
          placeholder="Full Name"
        />
        <Input
          v-model="data.email"
          type="email"
          prefix="md-mail"
          class="ivu-mb ivu-mt"
          placeholder="Email"
        />
        <Input
          v-model="data.password"
          class="ivu-mb"
          type="text"
          prefix="md-finger-print"
          placeholder="Password"
        />
       
        <Select class="custom" v-model="data.role_id" prefix="md-checkmark-circle" placeholder="Select Admin Type" >
            <Option :value="r.id" v-for="(r,i) in roles" :key="i" >{{r.roleName}}</Option>
            <!-- <Option value="Editor">Editor</Option>
            <Option value="User">User</Option> -->
        </Select>
      
        <template #footer>
          <Button type="error" @click="addModal = false">Close</Button>
          <Button
            type="primary"
            @click="addAdmin"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding..." : "Add Admin User" }}</Button
          >
        </template>
      </Modal>

      <!-- Edit Modal start -->

      <Modal
        v-model="editModal"
        title="Edit Admin User"
        width="400px"
        :mask-closable="false"
        :closable="false"
      >
        <Input
          v-model="editData.fullName"
          type="text"
          prefix="md-person"
          placeholder="Full Name"
        />
        <Input
          v-model="editData.email"
          type="email"
          prefix="md-mail"
          class="ivu-mb ivu-mt"
          placeholder="Email"
        />
        <Input
          v-model="editData.oldPassword"
          class="ivu-mb"
          type="text"
          prefix="md-finger-print"
          placeholder="Old Password"
        />
        <Input
          v-model="editData.password"
          class="ivu-mb"
          type="text"
          prefix="md-finger-print"
          placeholder="New Password"
        />
       
        <Select class="custom" v-model="editData.role_id" prefix="md-checkmark-circle" placeholder="Select Admin Type" >
            <Option :value="r.id" v-for="(r,i) in roles" :key="i" >{{r.roleName}}</Option>
            <!-- <Option value="Editor">Editor</Option>
            <Option value="User">User</Option> -->
        </Select>

        <template #footer>
          <Button type="error" @click="editModal = false">Close</Button>
          <Button
            type="primary"
            @click="editAdmin"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Updating..." : "Update User" }}</Button
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
        fullName : "",
        email : "",
        password : "",
        role_id : null,
      },
      addModal: false,
      editModal: false,
      showDeleteModal: false,
      isAdding: false,

      users: [],
      roles: [],
      role: "",
      editData: {
        fullName : "",
        email : "",
        password : "",
        oldPassword : "",
        role_id : null,
      },

      //checkRole: this.roles.data.id,
      

      index: -1,
      isDeleting: false,
      deleteItem: {},
      deletingIndex: -1,
    };
  },

  methods: {
    async addAdmin() {
      if (this.data.fullName.trim() == "")
        return this.info("Name can not be empty");
      if (this.data.email.trim() == "")
        return this.info("Email can not be empty");
      if (this.data.password.trim() == "")
        return this.info("Password can not be empty");
      if (!this.data.role_id)
        return this.info("Role can not be empty");

      const res = await this.callApi("post", "app/create_user", this.data);
      if (res.status === 201) {
        this.users.unshift(res.data);
        this.success("User has been added successfully");
        this.addModal = false;
        this.data.fullName = "";
        this.data.email = "";
        this.data.password = "";
        this.data.role_id = "";
      } else {
        if (res.status == 422) {
            //console.log(res.data.errors);
            for(let i in res.data.errors){
                this.error(res.data.errors[i][0])
            }
        } 
        // if(res.data.userType == this.data.userType) {
        //   this.error("Email Name already Exist");
        // } 
        else {
            this.errorD();
        }
      }
    },


    showEditModal(user, i) {
      let obj = {
        id: user.id,
        fullName: user.fullName,
        email: user.email,
        role_id: user.role_id,
        password: user.password,
        oldPassword : user.oldPassword,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = i;
    },

    async editAdmin() {
      if (this.editData.fullName.trim() == "")
        return this.info("Name can not be empty");
      if (this.editData.email.trim() == "")
        return this.info("Email can not be empty");
      if (this.editData.oldPassword == "")
        return this.info("Old Password can not be empty");
      if (!this.editData.role_id)
        return this.info("Role can not be empty");

      const res = await this.callApi("post", "app/edit_admin", this.editData);
      if (res.status === 200) {
        //this.users[this.index] = this.editData;
        this.users[this.index].fullName = this.editData.fullName;
        this.users[this.index].role_id = this.editData.role_id;
        this.users[this.index].email = this.editData.email;
        // this.users[this.index].password = this.editData.password;
        // this.users[this.index].oldPassword = this.editData.oldPassword;
        this.success("Admin User has been Updated successfully");
        this.editModal = false;
      } else {
        if (res.status == 422) {
          for(let i in res.data.errors){
                this.error(res.data.errors[i][0])
            }
        } else {
          this.errorD();
        }
      }
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

    showDeletingModal(user, i) {
       this.deletingIndex = i;
      const deleteModelObj = {
        showDeleteModal: true,
        deleteUrl: "app/delete_user",
        data: {id : user.id},
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
    const [res, resRole] = await Promise.all([
        this.callApi("get", "app/get_admins"),
        this.callApi("get", "app/get_roles")
    ]);
    // const res = await this.callApi("get", "app/get_admins");
    // const resRole = await this.callApi("get", "app/get_roles");
    if (res.status == 200) {
      this.users = res.data;
    } else {
      this.errorD();
    }
    if (resRole.status == 200) {
      this.roles = resRole.data;
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
        this.users.splice(this.deletingIndex, 1);
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

<style lang="css" >
.custom .ivu-select-prefix i {
  margin-right: 8px;
  color:#808695;
}
</style>