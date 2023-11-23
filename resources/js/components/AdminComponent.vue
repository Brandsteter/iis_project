<template>
  <div>
    <p class="header-text-format"><b>Admin Page</b></p>
    <div class="list-container">
        <p style="font-size:20px"><b>User list</b></p>
      <table>
        <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Role</th>
          <th>Email</th>
          <th>Date Created</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(user, index) in users" :key="user.id" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.roles[0].role }}</td>
          <td>{{ user.email }}</td>
          <td>{{user.created_at}}</td>
          <td><v-btn variant="text"
                     color="secondary"
                     @click="openEditModal(user)">Edit User</v-btn></td>
          <td><v-btn variant="text"
                     color="red"
                     @click="showConfirm(user)">Delete</v-btn></td>
        </tr>
        </tbody>
      </table>
    </div>
      <v-btn class="create-event-button" @click="openCreateModal" prepend-icon="mdi-plus">Create a new user</v-btn>
  </div>

    <!--Create/Edit User-->
    <v-dialog v-model="showModal" max-width="600">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title v-if="modalMode === 'create'">Create new user</v-card-title>
            <v-card-title v-else-if="modalMode === 'edit'">Edit user</v-card-title>
            <v-card-text>
                <form>
                    <div class="mb-3">
                        <label for="InputName" class="form-label">Name<span style="color: red;">*</span></label>
                        <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
                        <span v-if="errorMessages.errors.name" style="color: red;">{{errorMessages.errors.name[0]}}</span>
                    </div>
                    <div class="mb-3">
                        <label for="InputAddress" class="form-label">Email address<span style="color: red;">*</span></label>
                        <input class="form-control" id="InputAddress" v-model="fields.email" maxlength="255" type="text" required>
                        <span v-if="errorMessages.errors.email" style="color: red;">{{errorMessages.errors.email[0]}}</span>
                    </div>
                  <div class="mb-3">
                    <label for="InputPlace" class="form-label">Select role<span style="color: red;">*</span></label>
                    <select class="form-control" id="InputPlace" v-model="fields.role" required>
                      <option value="none" selected disabled hidden>Select an Option</option>
                      <option value="Admin">Admin</option>
                      <option value="Moderator">Moderator</option>
                      <option value="User">User</option>
                    </select>
                    <span v-if="errorMessages.errors.role" style="color: red;">{{errorMessages.errors.role[0]}}</span>
                  </div>
                  <div class="mb-3">
                    <label for="InputPassword" class="form-label">Password<span style="color: red;">*</span></label>
                    <input class="form-control" id="InputPassword"  maxlength="255" v-model="fields.password" type="password">
                    <span v-if="errorMessages.errors.password" style="color: red;">{{errorMessages.errors.password[0]}}</span>
                  </div>
                  <div class="mb-3">
                    <label for="InputPasswordRepeat" class="form-label">Repeat password<span style="color: red;">*</span></label>
                    <input class="form-control" id="InputPasswordRepeat" maxlength="255" v-model="fields.passwordRepeat" type="password">
                    <span v-if="errorMessages.errors.passwordRepeat" style="color: red;">{{errorMessages.errors.passwordRepeat[0]}}</span>
                  </div>
                  <span style="color: red;">* - the field is required</span>
                  <div style="margin-bottom: 10px;"></div>
                    <div class="d-flex justify-content-center">
                        <v-btn @click="submit" color="grey-darken-3">
                            Submit</v-btn>
                    </div>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>

    <!--Delete confirmation window-->
    <v-dialog v-model="showConfirmation" max-width="400" max-height="250">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title class="confirm-title">Do you want to delete this user?</v-card-title>
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
import {RoleEnum} from "../enums/RoleEnum";
export default {
  data() {
    return {
      users: [],
      fields: {
        id: "",
        name: "",
        email: "",
        password: null,
        passwordRepeat: null,
        roles: null,
        role: "",
      },
      roles: RoleEnum,
      showModal: false,
      modalMode: 'create',
      showConfirmation: false,
      userDeleteConfirm: false,
      errorMessages: {
        message: "",
        errors: {
          name: null,
          email: null,
          password: null,
          passwordRepeat: null,
          role: null,
        }
      },
    };
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
    deleteUser(user) {
      const url = `/admin/user/${user.id}`
      axios.delete(url)
          .then(() => {
            this.fetchUsers();
          })
          .catch(error => {
            console.error('Error deleting user:', error);
          });
    },
    openEditModal(user) {
      this.modalMode = 'edit';
      this.showModal = true;
      this.fields = { ...user };
      this.fields.role = this.fields.roles[0].role;
    },
    openCreateModal() {
      this.modalMode = 'create';
      this.showModal = true;
      this.fields = {
        id: "",
        name: "",
        email: "",
        password: null,
        passwordRepeat: null,
        roles: null,
        role: "",
      };
    },
    showConfirm(user) {
      this.showConfirmation = true;
      this.userDeleteConfirm = user;
    },
    confirmDelete() {
      this.showConfirmation = false;
      this.deleteUser(this.userDeleteConfirm);
      this.userDeleteConfirm = null;
    },
    cancelDelete() {
      this.showConfirmation = false;
      this.userDeleteConfirm = null;
    },
    submit() {
      if (this.modalMode === 'create') {
        console.log(this.fields.role)
        axios.post('/admin/user/', this.fields).then((response) => {
          if (response) {
            window.location.href = '/admin'
          }
        })
        .catch((error) => {
          if (error.response && error.response.data.message) {
            this.errorMessages = error.response.data;
          }
        });
      } else if (this.modalMode === 'edit') {
        const url = `/admin/user/${this.fields.id}`;
        axios.put(url, this.fields).then((response) => {
          if (response) {
            window.location.href = '/admin'
          }
        })
        .catch((error) => {
          if (error.response && error.response.data.message) {
            this.errorMessages = error.response.data;
          }
        });
      }
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

.header-text-format {
    font-size: 40px;
    margin-bottom: 10px;
    color: #07abd5;
}

.list-container{
    background-color: #91deff;
    width: 100%;
    border-radius: 10px;
    padding: 10px;
}

table {
    border-collapse: separate;
    border-spacing: 0 10px;
    margin-top: -10px;
    width: 100%;
}
td {
    padding: 10px;
    background-color: #ffffff;
}
td:first-child {
    border-left-style: solid;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

td:last-child {
    border-right-style: solid;
    border-bottom-right-radius: 10px;
    border-top-right-radius: 10px;
}

.create-event-button{
    margin:20px;
    background-color: #91deff;
}

</style>
