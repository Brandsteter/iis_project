<template>
    <h1>Categories</h1>
    <div>
        <hr>
        <ul class="nested">
            <li v-for="(category, index) in categoriesTopLevelApproved">
                <a href="/category/{{category}}">{{category.name}}</a>
                <categoryList :categoryId="category.id"></categoryList>
            </li>
        </ul>

    </div>
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
            authUser: null,
            roleEnum: RoleEnum,
            children: [],
            parentName: null,
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
                    this.fetchApprovedCategories();
                    this.fetchUnapprovedCategories();
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
                    this.children = response.data;
                })
                .catch(error => {
                    console.error('Error fetching unapproved categories:', error);
                });
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

th, td {
    padding: 5px;
}
</style>
