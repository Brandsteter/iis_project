<template>
    <p class="header-text-format"><b>Categories</b></p>
    <v-btn v-if="checkIfIsLoggedIn()" @click="newCategory" prepend-icon="mdi-plus">Add new category</v-btn>
    <div>
        <hr>
        <ul class="custom-list">
            <li v-for="category in categoriesTopLevelApproved" class="list-item">
                <v-btn v-if="categoryShown === category.name && category.categories !== {}" @click="toggleCollapse(category.name)" density="comfortable" icon="mdi-arrow-right" :style="{ backgroundColor: categoryColors[depthIndex % categoryColors.length] }"></v-btn>
                <v-btn v-else @click="toggleCollapse(category.name)" density="comfortable" icon="mdi-arrow-down" :style="{ backgroundColor: categoryColors[depthIndex % categoryColors.length] }"></v-btn>
                <v-btn class="category-link" :href="`/category/${category.id}/detail`" min-width="300" :style="{ backgroundColor: categoryColors[depthIndex % categoryColors.length]}">{{ category.name }}</v-btn>
                <v-btn variant="text"
                            color="secondary"
                            @click="categoryOpenEditModal(category)"
                            v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
                            Edit</v-btn>
                <v-btn variant="text"
                         color="red"
                         @click="showConfirm(category)"
                         v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
                         Delete</v-btn>
                <categoryList v-if="categoryShown === category.name && category.categories !== {}" :categoryId="category.id" :depthIndex="1"></categoryList>
            </li>
        </ul>
    </div>

    <!--Create new category-->
    <v-dialog v-model="showNewCategoryModal" max-width="400" max-height="250" :persistent="true">
        <v-card class="card" style="background-color: lightskyblue; border-radius: 10px; padding: 10px;">
            <v-card-title class="confirm-title">Create a new category</v-card-title>
            <form>
                <div class="mb-3">
                    <label for="InputName" class="form-label">Name<span style="color: red;">*</span></label>
                    <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
                    <span v-if="errorMessages.errors.name" style="color: red;">{{errorMessages.errors.name[0]}}</span>
                </div>
                <span style="color: red;">* - the field is required</span>
                <div style="margin-bottom: 10px;"></div>
                <div class="d-flex justify-content-center">
                  <div class="button-container">
                    <v-btn @click="cancelSubmit" min-width="100" color="grey-darken-3" class="mr-15">Cancel</v-btn>
                    <v-btn @click="submit" color="grey-darken-3" min-width="100">Submit</v-btn>
                  </div>
                </div>
            </form>
        </v-card>
    </v-dialog>

    <!--Edit category-->
    <v-dialog v-model="showEditCategoryModal" max-width="400" max-height="250" :persistent="true">
      <v-card class="card" style="background-color: lightskyblue; border-radius: 10px; padding: 10px;">
        <v-card-title class="confirm-title">Edit category</v-card-title>
        <form>
          <div class="mb-3">
            <label for="InputName" class="form-label">Name<span style="color: red;">*</span></label>
            <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
            <span v-if="errorMessages.errors.name" style="color: red;">{{errorMessages.errors.name[0]}}</span>
          </div>
          <span style="color: red;">* - the field is required</span>
          <div style="margin-bottom: 10px;"></div>
          <div class="d-flex justify-content-center">
            <div class="button-container">
              <v-btn @click="cancelSubmit" min-width="100" color="grey-darken-3" class="mr-15">Cancel</v-btn>
              <v-btn @click="submit" color="grey-darken-3" min-width="100">Submit</v-btn>
            </div>
          </div>
        </form>
      </v-card>
    </v-dialog>

    <!--Category delete confirmation window-->
    <v-dialog v-model="showConfirmation" max-width="600" max-height="250">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title class="confirm-title">Do you want to delete this category? </v-card-title>
            <div class="text-center"> All its subcategories will also be removed and events will lose their category</div>
            <div class="button-container">
                <v-btn @click="confirmDelete()"
                       min-width="80"
                       color="green">Yes</v-btn>
                <v-btn @click="cancelDelete()"
                       min-width="80"
                       color="red">No</v-btn>
            </div>
        </v-card>
    </v-dialog>
</template>

<script>
import {isRole, getAuthUser} from "../app";
import categoryList from "./CategoryListComponent.vue";
import {RoleEnum} from "../enums/RoleEnum";
import {Bootstrap5Pagination} from "laravel-vue-pagination";

export default {
    components: {Bootstrap5Pagination, categoryList},
    data() {
        return {
            categoriesTopLevelApproved: [],
            categoriesApproved: [],
            categoriesChildren: [],
            collapsedCategories: [],
            categoryShown: "",
            depthIndex: 0,
            categoryColors: ['#91DEFF', '#88C0F3', '#8DA1DE', '#9780C0', '#9D5F98', '#B04C7B'],
            authUser: null,
            roleEnum: RoleEnum,
            children: [],
            parentName: null,
            categoryManipulate: null,
            showConfirmation: false,
            showEditCategoryModal: false,
            showNewCategoryModal: false,
            fields: {
                id: "",
                name: "",
                category: {},
                parent_category_id: null,
            },
            errorMessages: {
              message: "",
              errors: {
                name: null,
              }
            },
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.fetchApprovedCategories();
        this.fetchTopLevelApprovedCategories();
    },
    methods: {
        fetchTopLevelApprovedCategories() {
            axios.get('/category/top')
                .then(response => {
                    this.categoriesTopLevelApproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching top level categories:', error);
                })
        },
        fetchApprovedCategories() {
            axios.get('/category/approved')
                .then(response => {
                    this.categoriesApproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching approved categories:', error);
                })
        },
        approveCategory(category) {
            const url = `/category/${category.id}`;
            axios.patch(url)
                .then(() => {
                    window.location.href = "/category";
                })
                .catch(error => {
                    console.error('Error approving category:', error);
                });
        },
        deleteCategory(category) {
            const url = `/category/${category.id}`;
            axios.delete(url)
                .then(() => {
                  window.location.href = "/category";
                })
                .catch(error => {
                    console.error('Error deleting category:', error);
                });
        },
        getChildCategories(categoryId) {
            const url = `/category/${categoryId}`;
            axios.get(url)
                .then(response => {
                    this.parentName = category.name;
                    this.children = response.data;
                    console.log("Parent" + this.parentName)
                })
                .catch(error => {
                    console.error('Error fetching unapproved categories:', error);
                });
        },
        newCategory() {
            this.showNewCategoryModal = true;
        },
        submit() {
            axios.post('/category', this.fields).then((response) => {
                if (response) {
                    window.location.href = `/category`
                }
            })
            .catch((error) => {
              if (error.response && error.response.data.message) {
                this.errorMessages = error.response.data.errors;
              }
            });
        },
        submitEdit() {
          const url = `/category/${this.fields.id}`
          axios.put(url, this.fields).then((response) => {
            if (response) {
              window.location.href = `/category`
            }
          })
          .catch((error) => {
            if (error.response && error.response.data.message) {
              this.errorMessages = error.response.data;
            }
          });
        },
        cancelSubmit() {
          this.showNewCategoryModal = false;
          this.showEditCategoryModal = false;
          this.resetErrors();
        },
        resetErrors() {
          this.errorMessages.message = "";
          this.errorMessages.errors.name = null;
        },
        categoryOpenEditModal(category) {
          this.showEditCategoryModal = true;
          this.fields = { ...category };
        },
        showConfirm(category) {
            this.showConfirmation = true;
            this.categoryManipulate = category;
        },
        confirmDelete() {
            this.showConfirmation = false;
            this.deleteCategory(this.categoryManipulate);
            this.categoryManipulate = null;
        },
        cancelDelete() {
            this.showConfirmation = false;
            this.categoryManipulate = null;
        },
        toggleCollapse(categoryName) {
          if (this.categoryShown === categoryName) {
            this.categoryShown = "";
          } else {
            this.categoryShown = categoryName;
          }
        },
        checkIfIsLoggedIn(){
          return (isRole(this.roleEnum.User , this.authUser) || isRole(this.roleEnum.Moderator , this.authUser) || isRole(this.roleEnum.Admin , this.authUser));
        },
        isRole,
        getAuthUser,
    }
};
</script>

<style>
.user-info p {
    margin: 5px 0;
    /* Adjust spacing or other styles as needed */
}

.button-container {
  margin-top: 10px;
  margin-bottom: 10px;
}

/* Remove default list styles */
.custom-list {
  list-style: none;
  padding: 0;
}

/* Adjust margins between list items */
.list-item {
  margin-bottom: 10px; /* Increase or decrease as needed */
}

/* Add space between buttons */
.category-link {
  margin-left: 10px; /* Adjust the margin as needed */
}

.text-center {
  align-items: center;
}

th, td {
    padding: 5px;
}

.header-text-format {
  font-size: 30px;
  margin: 0;
  color: #07abd5;
}
</style>
