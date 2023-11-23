<template>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; ">
        <div class="card" style="width: 400px;  background-color: lightskyblue; padding: 20px; border-radius: 10px;">
            <form @submit.prevent="passwordCheck">
                <label style="font-size: x-large" class="form-label">Sign up</label>
                <div class="mb-3">
                    <label for="InputUsername" class="form-label">Username<span style="color: red;">*</span></label>
                    <input id="InputUsername" class="form-control"  maxlength="255" v-model="fields.name" type="text">
                    <span v-if="errorMessages.name" style="color: red;">{{ errorMessages.name[0] }}</span>
                </div>
                <div class="mb-3">
                    <label for="InputEmail" class="form-label">Email address<span style="color: red;">*</span></label>
                    <input id="InputEmail" class="form-control" v-model="fields.email"  maxlength="255" type="email" aria-describedby="emailHelp">
                    <span v-if="errorMessages.email" style="color: red;">{{ errorMessages.email[0] }}</span>
                </div>
                <div class="mb-3">
                    <label for="InputPassword" class="form-label">Password<span style="color: red;">*</span></label>
                    <input class="form-control" id="InputPassword"  maxlength="255" v-model="fields.password" type="password">
                    <span v-if="errorMessages.password" style="color: red;">{{ errorMessages.password[0] }}</span>
                </div>
                <div class="mb-3">
                    <label for="InputPasswordRepeat" class="form-label">Repeat password<span style="color: red;">*</span></label>
                    <input class="form-control" id="InputPasswordRepeat" maxlength="255" v-model="fields.passwordRepeat" type="password">
                    <span v-if="errorMessages.passwordRepeat" style="color: red;">{{ errorMessages.passwordRepeat[0] }}</span>
                    <span v-if="passwordsDoNotMatch" style="color: red;">Passwords do not match</span>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
                <div v-if="errorMessages.message !== ''">
                  <span style="color: red;">{{ errorMessages.message }}</span>
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
            errorMessages: {},
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
                        this.errorMessages.message = error.response.data.message;
                    }
                });
        }
    }
};
</script>
