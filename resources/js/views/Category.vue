<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">
            Categories
            <Button @click="addModal=true">
              <Icon type="md-add" />Add Category
            </Button>
          </p>

          <div class="_overflow _table_div">
            <table class="_table">
              <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Icon Image</th>
                <th>Category Name</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              <!-- TABLE TITLE -->

              <!-- ITEMS -->
              <tr v-for="(category,i) in categories" :key="i">
                <td>{{ tagIndex(i) }}</td>
                <td class="table_image">
                  <img :src="category.iconImage" />
                </td>
                <td class="_table_name">{{ category.categoryName }}</td>
                <td>{{ category.created_at }}</td>
                <td>
                  <button
                    class="_btn _action_btn edit_btn1"
                    type="button"
                    @click="showEditModal(category, i)"
                  >Edit</button>
                  <button
                    class="_btn _action_btn make_btn1"
                    type="button"
                    @click="showDeleteModal(category, i)"
                  >Delete</button>
                </td>
              </tr>
              <!-- ITEMS -->
            </table>
          </div>
        </div>
        <!-- Tag adding Moadl -->
        <Modal v-model="addModal" title="Add Category" :mask-closable="false">
          <Input v-model="data.categoryName" placeholder="Enter Category Name" />
          <div class="space"></div>
          <Upload
            type="drag"
            ref="uploads"
            :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
            :on-success="handleSuccess"
            :on-error="handleError"
            :format="['jpg','jpeg','png']"
            :max-size="2048"
            :on-format-error="handleFormatError"
            :on-exceeded-size="handleMaxSize"
            action="/app/upload"
          >
            <div style="padding: 20px 0">
              <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
              <p>Click or drag image here to upload</p>
            </div>
          </Upload>

          <div class="demo-upload-list" v-if="this.data.iconImage">
            <img :src="this.data.iconImage" />
            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="addModal=false">Close</Button>
            <Button
              type="primary"
              @click="addCategory"
              :disabled="isAdding"
              :loading="isAdding"
            >{{ isAdding ? 'Adding..' : 'Add Catrgory' }}</Button>
          </div>
        </Modal>

        <!-- Editing Tag Moadl -->
        <Modal v-model="editModal" title="Edit Category" :mask-closable="false">
          <Input v-model="editData.categoryName" placeholder="Enter Category Name" />
          <div class="space"></div>
          <Upload
            v-show="this.editData.iconImage==''"
            type="drag"
            ref="uploads"
            :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
            :on-success="handleSuccess"
            :on-error="handleError"
            :format="['jpg','jpeg','png']"
            :max-size="2048"
            :on-format-error="handleFormatError"
            :on-exceeded-size="handleMaxSize"
            action="/app/upload"
          >
            <div style="padding: 20px 0">
              <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
              <p>Click or drag image here to upload</p>
            </div>
          </Upload>

          <div class="demo-upload-list" v-if="editData.iconImage">
            <img :src="editData.iconImage" />
            <div class="demo-upload-list-cover">
              <Icon type="ios-trash-outline" @click="deleteUpdateImage"></Icon>
            </div>
          </div>

          <div slot="footer">
            <Button type="default" @click="addModal=false">Close</Button>
            <Button
              type="primary"
              @click="addCategory"
              :disabled="isAdding"
              :loading="isAdding"
            >{{ isAdding ? 'Adding..' : 'Add Catrgory' }}</Button>
          </div>
        </Modal>

        <!-- Delete Tag Modal -->
        <Modal v-model="deleteModal" width="360">
          <p slot="header" style="color:#f60;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>Delete confirmation</span>
          </p>
          <div style="text-align:center">
            <p>Are you sure? Once delete, this will be permanently deleted.</p>
          </div>
          <div slot="footer">
            <Button
              type="error"
              size="large"
              long
              :loading="isDeleting"
              :disabled="isDeleting"
              @click="deleteCategory"
            >Delete</Button>
          </div>
        </Modal>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      data: {
        categoryName: "",
        iconImage: "",
      },
      addModal: false,
      isAdding: false,
      categories: [],

      editModal: false,
      editData: {},
      index: -1,
      deleteModal: false,
      deleteData: {},
      isDeleting: false,
      token: "",
    };
  },
  methods: {
    tagIndex(i) {
      return i + 1;
    },
    addCategory() {
      if (this.data.categoryName.trim() == "")
        return this.e("Category name is required.");
      if (this.data.iconImage.trim() == "")
        return this.e("Icon image is required.");
      this.isAdding = true;
      axios
        .post("app/create_category", this.data)
        .then((resp) => {
          this.categories.unshift(resp.data);
          this.s("Category has been added successfully");
          this.isAdding = false;
          this.addModal = false;
          this.data.categoryName = "";
          this.data.iconImage = "";
          this.$refs.uploads.clearFiles();
        })
        .catch((e) => {
          this.swr();
          console.log(e);
          this.isAdding = false;
        });
    },
    async editTag() {
      if (this.editData.tagName.trim() == "")
        return this.e("Tag name is required.");
      if (this.editData.tagName.trim() == this.tags[this.index].tagName)
        return this.e("Please enter a new tagname.");
      const res = await this.callApi("post", "app/edit_tag", this.editData);
      if (res.status === 200) {
        this.tags[this.index].tagName = this.editData.tagName;
        this.s("Tag has been updated successfully.");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.tagName) {
            this.e(res.data.errors.tagName[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(category, i) {
      let object = {
        id: category.id,
        categoryName: category.categoryName,
        iconImage: category.iconImage,
      };
      this.editData = object;
      this.editModal = true;
      this.index = i;
    },
    showDeleteModal(category, i) {
      this.deleteModal = true;
      this.deleteData = category;
      this.index = i;
    },
    deleteCategory() {
      (this.isDeleting = true),
        axios
          .post("app/delete_category", this.deleteData)
          .then((resp) => {
            this.isDeleting = false;
            this.deleteModal = false;
            this.categories.splice(this.index, 1);
            this.s("Category has been deleted successfully!");
          })
          .catch((e) => {
            this.swr();
            this.isAdding = false;
          });
    },
    deleteImage() {
      console.log(this.data.iconImage);
      axios
        .post("app/delete_image", { iconImage: this.data.iconImage })
        .then((resp) => {
          this.data.iconImage = "";
          this.$refs.uploads.clearFiles();
        })
        .catch((e) => {
          this.swr();
        });
    },
    deleteUpdateImage() {
      console.log(this.editData.iconImage);
      axios
        .post("app/delete_image", { iconImage: this.editData.iconImage })
        .then((resp) => {
          this.editData.iconImage = "";
          // this.$refs.uploads.clearFiles();
        })
        .catch((e) => {
          this.swr();
        });
    },
    handleSuccess(res, file) {
      this.data.iconImage = res;
    },
    handleError(res, file) {
      this.$Notice.error({
        title: "The file format is incorrect",
        desc: `${file.errors.file[0]}`,
      });
    },
    handleFormatError(file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc:
          "File format of " +
          file.name +
          " is incorrect, please select jpg or png.",
      });
    },
    handleMaxSize(file) {
      this.$Notice.warning({
        title: "Exceeding file size limit",
        desc: "File  " + file.name + " is too large, no more than 2M.",
      });
    },
  },
  async created() {
    this.token = window.laravel.csrfToken;
    const res = await this.callApi("get", "app/get_categories");
    if (res.status == 200) {
      this.categories = res.data;
    } else this.swr();
  },
};
</script>

<style scoped>
.demo-upload-list {
  display: inline-block;
  width: 60px;
  height: 60px;
  text-align: center;
  line-height: 60px;
  border: 1px solid transparent;
  border-radius: 4px;
  overflow: hidden;
  background: #fff;
  position: relative;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
  margin-right: 4px;
}
.demo-upload-list-cover {
  display: none;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.6);
}
.demo-upload-list:hover .demo-upload-list-cover {
  display: block;
}
.demo-upload-list-cover i {
  color: #fff;
  font-size: 20px;
  cursor: pointer;
  margin: 0 2px;
}
</style>
