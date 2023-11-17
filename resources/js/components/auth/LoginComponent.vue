
<template>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; ">
            <div class="card" style="width: 400px;  background-color: lightskyblue; padding: 20px; border-radius: 10px;">
                <form>
                    <label style="font-size: x-large" class="form-label">Login</label>
                    <div class="mb-3">
                        <label for="InputEmail" class="form-label">Email address</label>
                        <input id="InputEmail" class="form-control" v-model="fields.email" type="email" maxlength="255" aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3">
                        <label for="InputPassword" class="form-label">Password</label>
                        <input class="form-control" id="InputPassword" v-model="fields.password" maxlength="255" type="password" required>
<!--                        <span v-if="errorMessages.password" style="color: red;">{{ errorMessages.password[0] }}</span>-->
                    </div>

                    <div class="d-flex justify-content-center">
                      <v-btn @click="submit" color="grey-darken-3">
                        Submit
                      </v-btn>

                    </div>
                </form>
                <a href="/auth/register">Don't have an account yet? Register here</a>
            </div>



    </div>
  <template>

  </template>
</template>


<script>
export default {
    data(){
      return {
        fields: {
          email: "",
          password: ""
        },
        errorMessages: {}
      }
    },

    methods: {
        submit() {
            axios.post('/auth/login', this.fields).then((response) => {
                if (response) {
                    window.location.href = '/'
                }
            })
        }
    }
}
</script>
