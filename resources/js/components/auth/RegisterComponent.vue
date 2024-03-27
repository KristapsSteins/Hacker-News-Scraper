<template>
    <div class="form-container">
        <el-card class="box-card">
            <div class="form-wrapper">
                <el-form :model="model" :rules="rules" ref="registerForm" @keydown.enter.native="register">
                    <el-form-item label="Name" required prop="name">
                        <el-input v-model="model.name" aria-placeholder="Your Name"></el-input>
                    </el-form-item>
                    <el-form-item label="Email" prop="email">
                        <el-input v-model="model.email"></el-input>
                    </el-form-item>
                    <el-form-item label="Password" prop="password">
                        <el-input type="password" v-model="model.password"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button class="form-btn" type="primary" @click="register">Register</el-button>
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
            async register() {
                const valid = await this.$refs.registerForm.validate();
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
                    }, 500);
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