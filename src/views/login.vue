<style lang="less" scoped>
     
	#login{
		width: 33%;
		margin: 128px auto;
        .title{
            width: 100%;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            padding-bottom: 20px;
        }
	}
</style>

<script>
    import Cookies from 'js-cookie';
    import qs from 'qs';

	export default {
        data () {
            return {
                login: {
                    user: '',
                    password: '',
                    type: null
                },
                ruleInline: {
                    user: [
                        { required: true, message: 'Please fill in the user name', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: 'Please fill in the password.', trigger: 'blur' },
                        { type: 'string', min: 6, message: 'The password length cannot be less than 6 bits', trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            handleSubmit(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.doAjax();
                    }else{
                        this.$Message.error('未按格式填写');
                    }
                });
            },
            doAjax(){
                this.$axios.post('http://139.199.2.100/roomback/login.php', qs.stringify({
                    login:this.login
                })).then((res) => {
                    this.login.type = res.data;
                   if(res.data === 1 ){
                        this.setCookies(this.login.user, this.login.password, 1);
                        this.$Message.success('Success!');
                        this.$router.push('/index');
                    }
                    if(res.data === 2 ){
                        this.setCookies(this.login.user, this.login.password, 2);
                        this.$Message.success('Success!');
                        this.$router.push('/control');
                    }
                    if(res.data === 0 ){
                        this.setCookies('', '', 3);
                        this.$Message.error('账号密码错误');
                    }
                }).catch((err) => {
                    console.log(err);
                });
            },
            handleRegister() {
                this.$router.push('/register');
            },
            checkCookies() {
                return Cookies.get('user') && Cookies.get('password');
            },
            setCookies(user, password, type) {
                Cookies.set('user', user, { expires: 7 });
                Cookies.set('password', password, { expires: 7 });
                Cookies.set('type', type, { expires: 7 });
            }
        },
        mounted() {
            if(this.checkCookies()){
                this.login.user = Cookies.get('user');
                this.login.password = Cookies.get('password');
                this.login.type = Cookies.get('level');
                this.doAjax();
            }
        }
    }
</script>

<template>
	<div id="login">
        <div class="title">登录</div>
	    <Form ref="login" :model="login" :rules="ruleInline">
	        <FormItem prop="user">
	            <Input type="text" v-model="login.user" placeholder="手机号" size="large">
	                <Icon type="ios-person-outline" slot="prepend"></Icon>
	            </Input>
	        </FormItem>
	        <FormItem prop="password">
	            <Input type="password" v-model="login.password" placeholder="密码" size="large">
	                <Icon type="ios-locked-outline" slot="prepend"></Icon>
	            </Input>
	        </FormItem>
	        <FormItem style="display: flex; flex-flow: row nowrap; justify-content: space-around">
                <Button type="warning" @click="handleRegister()" size="large">注册</Button>
                <Button type="primary" @click="handleSubmit('login')" size="large">点击登录</Button>
	        </FormItem>
	    </Form>
	</div>
</template>