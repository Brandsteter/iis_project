<template>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <div>
      <v-btn class="back-btn" prepend-icon="mdi-arrow-left" href="/"> Back to main page</v-btn>
    </div>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; ">
            <div class="card" style="width: 400px;  background-color: lightskyblue; padding: 20px; border-radius: 10px;">
                <form>
                    <label style="font-size: x-large" class="form-label">Login</label>
                    <div class="mb-3">
                        <label for="InputEmail" class="form-label">Email address</label>
                        <input id="InputEmail" class="form-control" v-model="fields.email" type="email" maxlength="255" aria-describedby="emailHelp" required>
                        <span v-if="errorMessages.errors.email" style="color: red;">{{ errorMessages.errors.email[0] }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="InputPassword" class="form-label">Password</label>
                        <input class="form-control" id="InputPassword" v-model="fields.password" maxlength="255" type="password" required>
                        <span v-if="errorMessages.errors.password" style="color: red;">{{ errorMessages.errors.password[0] }}</span>
                        <span v-if="errorMessages.message === 'wrong login'" style="color: red;">{{errorMessages.message}}</span>
                    </div>
                    <div class="d-flex justify-content-center">
                      <v-btn @click="submit" color="grey-darken-3">
                        Submit
                      </v-btn>
                    </div>
                </form>
                <a href="/auth/register">No account yet? Register here</a>
            </div>
    </div>
</template>


<script>
export default {
    data(){
      return {
        fields: {
          email: "",
          password: ""
        },
        errorMessages: {
          message: "",
          errors: {
            email: null,
            password: null,
          }
        },
      }
    },

    methods: {
        submit() {
            this.clearErrorMessages();
            axios.post('/auth/login', this.fields).then((response) => {
                if (response) {
                    window.location.href = '/'
                }
            })
            .catch((error) => {
              if (error.response) {
                if (error.response.data.errors) {
                  this.errorMessages.errors = error.response.data.errors
                }
                if (error.response.data.message) {
                  this.errorMessages.message = error.response.data.message;
                }
              }
            });
        },
        clearErrorMessages() {
          this.errorMessages.errors.email = null;
          this.errorMessages.errors.password = null
        },
    }
}
</script>

<style>
.back-btn {
  margin: 20px;
}
</style>
