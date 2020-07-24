<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">
            Tags
            <Button @click="addModal=true">
              <Icon type="md-add" />Add Tag
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
              <tr v-for="(tag,i) in tags" :key="i" v-if="tags.length">
                <td>{{ tagIndex(i) }}</td>
                <td class="_table_name">{{ tag.tagName }}</td>
                <td>{{ tag.created_at }}</td>
                <td>
                  <button
                    class="_btn _action_btn edit_btn1"
                    type="button"
                    @click="showEditModal(tag, i)"
                  >Edit</button>
                  <button
                    class="_btn _action_btn make_btn1"
                    type="button"
                    @click="showDeleteModal(tag, i)"
                  >Delete</button>
                </td>
              </tr>
              <!-- ITEMS -->
            </table>
          </div>
        </div>
        <!-- Tag adding Moadl -->
        <Modal v-model="addModal" title="Add Tag" :mask-closable="false">
          <Input v-model="data.tagName" placeholder="Enter a Tag Name" />
          <div slot="footer">
            <Button type="default" @click="addModal=false">Close</Button>
            <Button
              type="primary"
              @click="addTag"
              :disabled="isAdding"
              :loading="isAdding"
            >{{ isAdding ? 'Adding..' : 'Add Tag' }}</Button>
          </div>
        </Modal>

        <!-- Editing Tag Moadl -->
        <Modal v-model="editModal" title="Edit Tag" :mask-closable="false">
          <Input v-model="editData.tagName" placeholder="Enter a Tag Name" />
          <div slot="footer">
            <Button type="default" @click="editModal=false">Close</Button>
            <Button
              type="primary"
              @click="editTag"
              :disabled="isAdding"
              :loading="isAdding"
            >{{ isAdding ? 'Updating..' : 'Update' }}</Button>
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
              @click="deleteTag"
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
        tagName: "",
      },
      addModal: false,
      isAdding: false,
      tags: [],

      editModal: false,
      editData: {
        tagName: "",
      },
      index: -1,
      deleteModal: false,
      deleteData: {},
      isDeleting: false,
    };
  },
  methods: {
    tagIndex(i) {
      return i + 1;
    },
    async addTag() {
      if (this.data.tagName.trim() == "")
        return this.e("Tag name is required.");
      this.isAdding = true;
      const res = await this.callApi("post", "app/create_tag", this.data);
      if (res.status === 201) {
        this.tags.unshift(res.data);
        this.s("Tag has been added successfully");
        this.addModal = false;
        this.data.tagName = "";
        this.isAdding = false;
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
    async editTag() {
      if (this.editData.tagName.trim() == "")
        return this.e("Tag name is required.");
      if (this.editData.tagName.trim() == this.tags[this.index].tagName)
        return this.e("Please enter a new tagname.");
      this.isAdding = true;
      const res = await this.callApi("post", "app/edit_tag", this.editData);
      if (res.status === 200) {
        this.tags[this.index].tagName = this.editData.tagName;
        this.s("Tag has been updated successfully.");
        this.editModal = false;
        this.isAdding = false;
      } else {
        this.isAdding = false;
        if (res.status === 422) {
          if (res.data.errors.tagName) {
            this.e(res.data.errors.tagName[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(tag, i) {
      let object = {
        id: tag.id,
        tagName: tag.tagName,
      };
      this.editData = object;
      this.editModal = true;
      this.index = i;
    },
    showDeleteModal(tag, i) {
      this.deleteModal = true;
      this.deleteData = tag;
      this.index = i;
    },
    deleteTag() {
      (this.isDeleting = true),
        axios
          .post("app/delete_tag", this.deleteData)
          .then((resp) => {
            this.isDeleting = false;
            this.deleteModal = false;
            this.tags.splice(this.index, 1);
            this.s("Tag has been deleted successfully!");
          })
          .catch((e) => {
            this.swr();
            this.deleteModal = false;
          });
    },
  },
  async created() {
    const res = await this.callApi("get", "app/get_tags");
    if (res.status == 200) {
      this.tags = res.data;
    } else this.swr();
  },
  // created() {
  //   axios
  //     .get("app/get_tags")
  //     .then(res => (this.tags = res.data))
  //     .catch(error => this.swr());
  // }
};
</script>

<style scoped></style>
