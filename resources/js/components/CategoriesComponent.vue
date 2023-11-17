<template>
    <div>
        <h1>Categories</h1>
        <div>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(category, index) in categoriesApproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                        <td><a href="/category/{{ category.name }}">{{ category.name }}</a></td>
                        <td><v-btn variant="text"
                                   color="secondary"
                                   @click="selectedOpen = false">Edit</v-btn></td>
                        <td><v-btn variant="text"
                                   color="red"
                                   @click="deleteCategory(category)">Delete</v-btn></td>
                </tr>
                </tbody>
            </table>
            <Bootstrap5Pagination
                :data="categoriesApproved"
                @pagination-change-page="fetchApprovedCategories"
            />
        </div>

        <div v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
            <h1>Unapproved categories</h1>
            <div>
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(category, index) in categoriesUnapproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                        <td>{{ category.name }}</td>
                        <td><v-btn variant="text"
                                   color="green"
                                   @click="approveCategory(category)">Approve</v-btn></td>
                        <td><v-btn variant="text"
                                   color="secondary"
                                   @click="selectedOpen = false">Edit</v-btn></td>
                        <td><v-btn variant="text"
                                   color="red"
                                   @click="deleteCategory(category)">Delete</v-btn></td>
                    </tr>
                    </tbody>
                </table>
                <Bootstrap5Pagination
                    :data="categoriesUnapproved"
                    @pagination-change-page="fetchUnapprovedCategories"
                />
            </div>
        </div>
    </div>
</template>

<script>
import {isRole, getAuthUser} from "../app";
import {RoleEnum} from "../enums/RoleEnum";
import {Bootstrap5Pagination} from "laravel-vue-pagination";

export default {
    components: {Bootstrap5Pagination},
    data() {
        return {
            categoriesApproved: [],
            categoriesUnapproved: [],
            authUser: null,
            roleEnum: RoleEnum,
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.fetchApprovedCategories();
        this.fetchUnapprovedCategories();
    },
    methods: {
        fetchApprovedCategories() {
            axios.get('/category/approved')
                .then(response => {
                    this.categoriesApproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                })
        },
        fetchUnapprovedCategories() {
            axios.get('/category/unapproved')
                .then(response => {
                    this.categoriesUnapproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
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
