<template>
    <div>
        <el-card class="box-card">
            <div>
                <el-form :model="model" :rules="rules" ref="loginForm">
                    <el-form-item label="Email" prop="email">
                        <el-input v-model="model.email" @keydown.enter.native="login('loginForm')"></el-input>
                    </el-form-item>
                    <el-form-item label="Password" prop="password">
                        <el-input type="password" v-model="model.password" @keydown.enter.native="login('loginForm')"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="login('loginForm')">Login</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-card>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'login-form-component',
    data() {
        return {
            model: {
                email: '',
                password: ''
            },
            rules: {
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
    methods: {
        async login(formName) {
            this.$refs[formName].validate(async (valid) => {
                if (valid) {
                    this.submitForm();
                } else {
                    return false;
                }
            });
        },
        submitForm() {
            const loader = this.$showLoader();
            axios.post('/user-login', this.model)
                .then(response => {
                    if (response.data.status) {
                        window.location.href = '/news';
                    } else {
                        this.$notify({
                            title: 'Error',
                            message: response.data.message,
                            type: 'error'
                        });
                    }
                })
                .catch(error => {
                    this.$notify({
                        title: 'Error',
                        message: error.response && error.response.data.message ? error.response.data.message : 'An error occurred. Please try again later.',
                        type: 'error'
                    });
                })
                .finally(() => {
                    loader.close();
                });
        }
    },
    mounted() {
    }
}
</script>
