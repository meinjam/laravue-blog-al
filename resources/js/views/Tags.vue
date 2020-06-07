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
                    @click="deleteTag(tag, i)"
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
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: {
        tagName: ""
      },
      addModal: false,
      isAdding: false,
      tags: [],

      editModal: false,
      editData: {
        tagName: ""
      },
      index: -1
    };
  },
  methods: {
    tagIndex(i) {
      return i + 1;
    },
    async addTag() {
      if (this.data.tagName.trim() == "")
        return this.e("Tag name is required.");
      const res = await this.callApi("post", "app/create_tag", this.data);
      if (res.status === 201) {
        this.tags.unshift(res.data);
        this.s("Tag has been added successfully");
        this.addModal = false;
        this.data.tagName = "";
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
    showEditModal(tag, i) {
      let object = {
        id: tag.id,
        tagName: tag.tagName
      };
      this.editData = object;
      this.editModal = true;
      this.index = i;
    },
    async deleteTag(tag, i) {
      if (!confirm("Are you want to delete this tag?"));
      const res = await this.callApi("post", "app/delete_tag", tag);
      if (res == 200) {
        // this.tags.splice(i, 1);
        this.s("Tag has been deleted successfully!");
      } else {
        // this.swr();
        this.s("Tag has been deleted successfully!");
        this.tags.splice(i, 1);
      }
    }
  },
  async created() {
    const res = await this.callApi("get", "app/get_tags");
    if (res.status == 200) {
      this.tags = res.data;
    } else this.swr();
  }
  // created() {
  //   axios
  //     .get("app/get_tags")
  //     .then(res => (this.tags = res.data))
  //     .catch(error => this.swr());
  // }
};
</script>

<style scoped></style>
