<template>
  <div>
    <h1>User List (Admin Page)</h1>
    <div>
      <table>
        <thead>
        <tr>
          <th>ID</th>
          <th>Role</th>
          <th>Name</th>
          <th>Email</th>
          <th>Date Created</th>
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
export default {
  data() {
    return {
      users: []
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
          console.log(this.users); // Verify the fetched data
        })
        .catch(error => {
          console.error('Error fetching users:', error);
        });
    }
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
