<template>
    <div>
        <el-card class="box-card">
            <div>
                <el-form :model="model" :rules="rules" ref="registerForm">
                    <el-form-item label="Name" required prop="name">
                        <el-input v-model="model.name" aria-placeholder="Your Name" @keydown.enter.native="login('registerForm')"></el-input>
                    </el-form-item>
                    <el-form-item label="Email" prop="email">
                        <el-input v-model="model.email" @keydown.enter.native="login('registerForm')"></el-input>
                    </el-form-item>
                    <el-form-item label="Password" prop="password">
                        <el-input type="password" v-model="model.password" @keydown.enter.native="login('registerForm')"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="register('registerForm')">Register</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-card>
    </div>
</template>

<script>
    export default {
        name: 'register-form-component',
        data() {
            return {
                model: {
                    name: '',
                    email: '',
                    password: ''
                },
                rules: {
                    name: [
                        { required: true, message: 'Please input name', trigger: 'blur' }
                    ],
                    email: [
                        { required: true, message: 'Please input email', trigger: 'blur' },
                        { type: 'email', message: 'Please input correct email', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: 'Please input password', trigger: 'blur' }
                    ]
                }
            }
        },
        mounted() {
        },
        methods: {
            async register(formName) {
                const valid = await this.$refs[formName].validate();
                if (!valid) return false;

                const loader = this.$showLoader();
                try {
                    const response = await axios.post('/save-user', this.model);
                    this.handleResponse(response);
                } catch (error) {
                    this.handleError(error);
                } finally {
                    loader.close();
                }
            },
            handleResponse(response) {
                this.$notify({
                    title: 'Success',
                    message: response.data.message,
                    type: response.data.status ? 'success' : 'error'
                });

                if (response.data.status) {
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                }
            },
            handleError(error) {
                if (error.response && error.response.data.errors) {
                        for (let field in error.response.data.errors) {
                            this.$notify({
                                title: 'Error',
                                message: error.response.data.errors[field][0],
                                type: 'error'
                            });
                        }
                    } else {
                        this.$notify({
                            title: 'Error',
                            message: error.message,
                            type: 'error'
                        });
                    }
                }
        }
}

</script>