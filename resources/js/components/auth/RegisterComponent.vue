<template>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <div>
      <v-btn class="back-btn" prepend-icon="mdi-arrow-left" href="/"> Back to main page</v-btn>
    </div>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; ">
        <div class="card" style="width: 400px;  background-color: lightskyblue; padding: 20px; border-radius: 10px;">
            <form @submit.prevent="passwordCheck">
                <label style="font-size: x-large" class="form-label">Sign up</label>
                <div class="mb-3">
                    <label for="InputUsername" class="form-label">Username<span style="color: red;">*</span></label>
                    <input id="InputUsername" class="form-control"  maxlength="255" v-model="fields.name" type="text">
                    <span v-if="errorMessages.errors.name" style="color: red;">{{ errorMessages.errors.name[0] }}</span>
                </div>
                <div class="mb-3">
                    <label for="InputEmail" class="form-label">Email address<span style="color: red;">*</span></label>
                    <input id="InputEmail" class="form-control" v-model="fields.email"  maxlength="255" type="email" aria-describedby="emailHelp">
                    <span v-if="errorMessages.errors.email" style="color: red;">{{ errorMessages.errors.email[0] }}</span>
                </div>
                <div class="mb-3">
                    <label for="InputPassword" class="form-label">Password<span style="color: red;">*</span></label>
                    <input class="form-control" id="InputPassword"  maxlength="255" v-model="fields.password" type="password">
                    <span v-if="errorMessages.errors.password" style="color: red;">{{ errorMessages.errors.password[0] }}</span>
                </div>
                <div class="mb-3">
                    <label for="InputPasswordRepeat" class="form-label">Repeat password<span style="color: red;">*</span></label>
                    <input class="form-control" id="InputPasswordRepeat" maxlength="255" v-model="fields.passwordRepeat" type="password">
                    <span v-if="errorMessages.errors.passwordRepeat" style="color: red;">{{ errorMessages.errors.passwordRepeat[0] }}</span>
                    <span v-if="passwordsDoNotMatch" style="color: red;">Passwords do not match</span>
                </div>
                <div class="d-flex justify-content-center">
                  <v-btn @click="submit" color="grey-darken-3">
                    Submit
                  </v-btn>
                </div>
            </form>
            <a href="/auth/login">Already have an account? Log in here</a>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            fields: {
                name: "",
                email: "",
                password: "",
                passwordRepeat: ""
            },
            errorMessages: {
              message: "",
              errors: {
                name: null,
                email: null,
                password: null,
                passwordRepeat: null,
              }
            },
            passwordsDoNotMatch: false
        };
    },
    methods: {
        passwordCheck() {
            this.errorMessages = {}; // Reset error messages
            if (this.fields.password !== this.fields.passwordRepeat) {
                this.passwordsDoNotMatch = true;
            } else {
                this.passwordsDoNotMatch = false;
                this.submit();
            }
        },
        submit() {
            axios.post('/auth/register', this.fields)
                .then((response) => {
                    if (response){
                        window.location.href = '/';
                    }
                })
                .catch((error) => {
                    if (error.response && error.response.data.message) {
                        this.errorMessages = error.response.data;
                    }
                });
        },
    }
};
</script>

<style>
  .back-btn {
    margin: 20px;
  }
</style>
