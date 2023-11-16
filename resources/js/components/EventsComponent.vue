<template>
    <div>
        <h1>User List (Admin Page)</h1>
        <div v-if="isRole(roleEnum.Admin, authUser)">
        </div>
        <div>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Email</th>
                    <th>Place</th>
                    <th>Capacity</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, index) in users" :key="user.id" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                    <td>{{ user.id }}</td>
                    <td>{{ user.roles[0].role }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{user.created_at}}</td>
                    <td><v-btn variant="text"
                               color="secondary"
                               @click="selectedOpen = false">Edit</v-btn></td>
                    <td><v-btn variant="text"
                               color="red"
                               @click="selectedOpen = false">Delete</v-btn></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import {isRole, getAuthUser} from "../app";
import {RoleEnum} from "../enums/RoleEnum";


export default {
    data() {
        return {
            users: [],
            authUser: null,
            roleEnum: RoleEnum,
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        fetchUsers() {
            axios.get('/admin/users')
                .then(response => {
                    this.users = response.data; // Update the users data property
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
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
