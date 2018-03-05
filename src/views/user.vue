<style lang="less" scoped>
    #user{
       display: flex;
       flex-flow: row nowrap;
       height: 35px;
       p{
        line-height: 35px;
        span{
            font-size: 18px;
            font-weight: bold;
            color: red;
        }
       }
       button{
        height: 25px;
        margin: 5px;
        margin-left: 15px;
       }
    }
</style>
<template>
    <div id="user" v-show="userData.type!==null">
        <p>你好，<span>{{ userData.user?userData.user:'' }}</span>&nbsp;&nbsp;&nbsp;{{ userData.type==2?'牛逼的管理员':'普通会员' }}</p>
        <Button size="small" type="ghost" icon="log-out" title="退出账号" @click="logOut"></Button>
    </div>
</template>
<script>
    import Cookies from 'js-cookie';
    import qs from 'qs';

    export default {
        name: 'user',
        data() {
            return {
                userData: {
                    user: '',
                    password: '',
                    type: null
                }
            }
        },
        mounted() {
            this.userData.user = Cookies.get('user');
            this.userData.type = Cookies.get('type');
        },
        methods: {
            logOut() {
                Cookies.set('user', '');
                Cookies.set('password', '');
                Cookies.set('type', '');
                this.$router.push('/');
            }
        }
    }
</script>