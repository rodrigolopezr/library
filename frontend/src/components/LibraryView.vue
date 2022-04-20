<template>
  <v-container>
    <h1>Library</h1>
    <v-data-table
      class="elevation-1"
      :headers="headers"
      :items="books"
      :loading="loading"
      loading-text="Loading ..."
      :search="search"
      sort-by="Id"
      sort-desc
    >
      <template #top>
        <v-toolbar flat>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            hide-details
            label="Search"
            single-line
          />
          <v-spacer />
          <v-dialog v-model="dialog" max-width="500px" persistent>
            <template #activator="{ on, attrs }">
              <v-btn
                class="mb-2"
                color="success"
                v-bind="attrs"
                dark
                fab
                large
                v-on="on"
              >
                <v-icon>mdi-plus </v-icon>
              </v-btn>
            </template>
            <v-card>
              <v-toolbar color="primary" dark>Book</v-toolbar>

              <v-card-text>
                <v-container>
                  <v-form ref="frmBooks" v-model="validBook" lazy-validation>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field
                          v-model="editedItem.Name"
                          label="Name"
                          :rules="[(v) => !!v || 'Required']"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-text-field
                          v-model="editedItem.Author"
                          label="Author"
                          :rules="[(v) => !!v || 'Required']"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-combobox
                          v-model="editedItem.UserName"
                          label="User"
                          :rules="[(v) => !!v || 'Required']"
                          :items="users"
                          item-text="Name"
                          item-value="id"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-combobox
                          v-model="editedItem.CategoryName"
                          label="Category"
                          :rules="[(v) => !!v || 'Required']"
                          :items="categories"
                          item-text="Name"
                          item-value="id"
                        />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12">
                        <v-combobox
                          v-model="editedItem.Status"
                          label="Status"
                          :rules="[(v) => !!v || 'Required']"
                          :items="Statuses"
                        />
                      </v-col>
                    </v-row>
                  </v-form>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer />
                <v-btn color="blue darken-1" outlined @click="dialog = false">
                  Cancel
                </v-btn>
                <v-btn color="success darken-1" @click="save"> Save </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
              <v-toolbar color="primary" dark> Delete </v-toolbar>
              <v-card-title class="text-h5">
                Are yoo sure to delete it?
              </v-card-title>
              <v-card-actions>
                <v-spacer />
                <v-btn color="error" @click="closeDelete"> No </v-btn>
                <v-btn color="primary" @click="deleteItemConfirm"> Yes </v-btn>
                <v-spacer />
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template #item.actions="{ item }">
        <v-icon class="mr-2" small @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
      </template>
      <template #no-data>
        <v-btn color="primary"> Reset </v-btn>
      </template>
    </v-data-table>
    <v-snackbar v-model="snackBar">
      {{ text }}

      <template v-slot:action="{ attrs }">
        <v-btn color="error" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>
<script>
import axios from "axios";

export default {
  name: "LibraryView",
  data: () => ({
    headers: [
      {
        text: "Id",
        value: "id",
        align: " d-none",
      },
      {
        text: "Name",
        align: "start",
        value: "Name",
      },
      { text: "Author", value: "Author" },
      { text: "Publication Date", value: "PublicationDate" },
      { text: "Status", value: "Status" },
      { text: "User Borrow it", value: "UserName" },
      { text: "Category", value: "CategoryName" },
      { text: "Actions", value: "actions", sortable: false },
    ],
    books: [],
    loading: false,
    search: "",
    dialog: false,
    validBook: false,
    editedIndex: -1,
    editedItem: {},
    defaultItem: {},
    dialogDelete: false,
    snackBar: false,
    text: "",
    users: [],
    categories: [],
    Statuses: ["Available", "Not Available"],
  }),
  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.loadBooks();
    this.loadUsers();
    this.loadCategories();
  },

  methods: {
    async loadBooks() {
      this.loading = true;
      this.books = await this.getBooks();
      this.loading = false;
    },

    async loadUsers() {
      this.loading = true;
      this.users = await this.getUsers();
      this.loading = false;
    },

    async loadCategories() {
      this.loading = true;
      this.categories = await this.getCategories();
      this.loading = false;
    },

    getBooks() {
      return new Promise((resolve, reject) => {
        axios
          .get(
            `${process.env.VUE_APP_HTTP}${process.env.VUE_APP_ENDPOINT}/books`
          )
          .then((response) => {
            // eslint-disable-next-line no-console
            const { data } = response;
            resolve(data.success);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },

    getUsers() {
      return new Promise((resolve, reject) => {
        axios
          .get(
            `${process.env.VUE_APP_HTTP}${process.env.VUE_APP_ENDPOINT}/users`
          )
          .then((response) => {
            // eslint-disable-next-line no-console
            const { data } = response.data;
            resolve(data);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },

    getCategories() {
      return new Promise((resolve, reject) => {
        axios
          .get(
            `${process.env.VUE_APP_HTTP}${process.env.VUE_APP_ENDPOINT}/categories`
          )
          .then((response) => {
            // eslint-disable-next-line no-console
            const { data } = response.data;
            resolve(data);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },

    addBook(payload) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            `${process.env.VUE_APP_HTTP}${process.env.VUE_APP_ENDPOINT}/books/new`,
            payload
          )
          .then((response) => {
            // eslint-disable-next-line no-console
            const { data } = response.data;
            resolve(data);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },

    updateBook(payload) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            `${process.env.VUE_APP_HTTP}${process.env.VUE_APP_ENDPOINT}/books/update`,
            payload
          )
          .then((response) => {
            // eslint-disable-next-line no-console
            const { data } = response.data;
            resolve(data);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },

    deleteBooks(payload) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            `${process.env.VUE_APP_HTTP}${process.env.VUE_APP_ENDPOINT}/books/delete`,
            { data: payload }
          )
          .then((response) => {
            // eslint-disable-next-line no-console
            const { data } = response.data;
            resolve(data);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },

    editItem(item) {
      this.editedIndex = this.books.indexOf(item);
      this.editedItem = { ...item };
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.books.indexOf(item);
      this.editedItem = { ...item };
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      try {
        console.log(this.editedItem);
        await this.deleteBooks(this.editedItem);
        this.books.splice(this.editedIndex, 1);
        this.closeDelete();
        this.text = "Item deleted";
        this.snackBar = true;
      } catch (error) {
        this.$snotify.error(error);
      }
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = { ...this.defaultItem };
        this.editedIndex = -1;
      });
    },

    async save() {
      if (this.$refs.frmBooks.validate()) {
        if (this.editedIndex > -1) {
          try {
            await this.updateBook(this.editedItem);
            Object.assign(this.books[this.editedIndex], this.editedItem);
            this.text = "Item updated";
            this.snackBar = true;
          } catch (error) {
            this.text = error;
            this.snackBar = true;
          }
        } else {
          try {
            this.editedItem["CategoryId"] = this.editedItem.CategoryName.id;
            await this.addBook(this.editedItem);
            this.loadBooks();
            this.text = "Item created";
            this.snackBar = true;
          } catch (error) {
            this.text = error;
            this.snackBar = true;
          }
        }
        this.close();
      }
    },
  },
};
</script>

<style />
