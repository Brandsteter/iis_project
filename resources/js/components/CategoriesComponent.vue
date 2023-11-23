<template>
    <h1>Categories</h1>
    <v-btn @click="newCategory" prepend-icon="mdi-plus">Add new category</v-btn>
    <div>
        <hr>
        <ul class="custom-list">
            <li v-for="category in categoriesTopLevelApproved" class="list-item">
                <v-btn @click="toggleCollapse(category.name)" density="comfortable" icon="mdi-arrow-down"></v-btn>
                <v-btn class="category-link" :href="`/category/${category.id}/detail`" min-width="300">{{ category.name }}</v-btn>
                <categoryList v-if="categoryShown === category.name && category.categories !== {}" :categoryId="category.id"></categoryList>
            </li>
        </ul>
    </div>

    <div v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
        <h1>Unapproved categories</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Parent</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(category, index) in categoriesUnapproved" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                    <td>{{category.name}}</td>
                    <td>{{category.parent_category_id}}</td>
                    <td><v-btn variant="text"
                               color="green"
                               @click="approveCategory(category)">Approve</v-btn></td>
                    <td><v-btn variant="text"
                               color="red"
                               @click="showConfirm(category)">Delete</v-btn></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!--Create new category-->
    <v-dialog v-model="showNewCategoryModal" max-width="400" max-height="250">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title class="confirm-title">Create a new category</v-card-title>
            <form>
                <div class="mb-3">
                    <label for="InputName" class="form-label">Name<span style="color: red;">*</span></label>
                    <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
                    <span v-if="errorMessages" style="color: red;">{{errorMessages[0]}}</span>
                </div>
                <span style="color: red;">* - the field is required</span>
                <div style="margin-bottom: 10px;"></div>
                <div class="d-flex justify-content-center">
                    <v-btn @click="submit()" color="grey-darken-3">
                        Submit
                    </v-btn>
                </div>
            </form>
        </v-card>
    </v-dialog>

    <!--Delete confirmation window-->
    <v-dialog v-model="showConfirmation" max-width="400" max-height="250">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title class="confirm-title">Do you want to delete this event?</v-card-title>
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
            categoriesUnapproved: [],
            categoriesChildren: [],
            collapsedCategories: [],
            categoryShown: "",
            authUser: null,
            roleEnum: RoleEnum,
            children: [],
            parentName: null,
            categoryManipulate: null,
            showConfirmation: false,
            showNewCategoryModal: false,
            fields: {
                name: "",
                parent_category_id: null,
            },
            errorMessages: "",
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.fetchApprovedCategories();
        this.fetchUnapprovedCategories();
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
        fetchUnapprovedCategories() {
            axios.get('/category/unapproved')
                .then(response => {
                    this.categoriesUnapproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching unapproved categories:', error);
                })
        },
        approveCategory(category) {
            const url = `/category/${category.id}`;
            axios.patch(url)
                .then(response => {
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
                    this.fetchApprovedCategories();
                    this.fetchUnapprovedCategories();
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
                this.errorMessages = error.response.data.errors.name;
              }
            });
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
        isRole,
        getAuthUser,
    }
};
</script>

<style>
/* Your custom styles here */
.user-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.user-card {
    border: 1px solid #ccc;
    padding: 20px;
    /* Adjust width or other styles as needed */
}

.user-info p {
    margin: 5px 0;
    /* Adjust spacing or other styles as needed */
}

.user-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
    /* Additional styles for action buttons */
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

th, td {
    padding: 5px;
}
</style>
