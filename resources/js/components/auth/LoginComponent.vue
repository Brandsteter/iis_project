
<template>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; ">
            <div class="card" style="width: 400px;  background-color: lightskyblue; padding: 20px; border-radius: 10px;">
                <form>
                    <label style="font-size: x-large" for="Login" class="form-label">Login</label>
                    <div class="mb-3">
                        <label for="InputEmail" class="form-label">Email address</label>
                        <input id="InputEmail" class="form-control" v-model="fields.email" type="email" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="InputPassword" class="form-label">Password</label>
                        <input class="form-control" id="InputPassword" v-model="fields.password" type="password">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary" @click="submit">Submit</button>
                    </div>
                </form>
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
            }
        }
    },

    methods: {
        submit(){
            axios.post('/auth/login', this.fields).then((response)=>{
                if (response)
                {
                    window.location.href = '/'
                }
            })
        }
    }
}
</script>
