<style lang="less" scoped>
	#register{
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
	import qs from 'qs';

	export default {
        data () {
        	var checkNumber = (rule, value, callback) => {
        		var reg = new RegExp("^[0-9]{11}$");
        		if (!reg.test(value)) {
        			callback(new Error('请输入11位电话号码'));
        		} else {
        			callback();
        		}
        	};
            return {
                customer: {
                    customer_number: '',
                    customer_password: '',
                    recustomer_password: '',
                    customer_name: '',
                    customer_sex: '0',
                },
                rulecustomer: {
                    customer_number: [{ validator: checkNumber}],
                    customer_name: [
                        { required: true, message: 'Please fill in the name' }
                    ],
                    customer_password: [
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
            			if(this.customer.customer_password === this.customer.recustomer_password){
            				this.$axios.post('http://139.199.2.100/roomback/register.php', qs.stringify({
            					customer:this.customer
            				})).then((res) => {
            					if(res.data === 1 ){
            						this.$Message.success('Success!');
            						this.$router.push('/');
            					}else{
            						this.$Message.error('该客户号码已被注册');
            					}
            				}).catch((err) => {
            					console.log(err);})	
            			}else{
            				this.$Message.error('密码2次输入不一样');
            			}
            		}else{
            			this.$Message.error('未按格式填写');
            		}
            	});
            },
            goBack() {
                this.$router.push('/');
            }
        }
    }
</script>

<template>
	<div id="register">
        <div class="title">注册</div>
	    <Form ref="customer" :model="customer" :rules="rulecustomer">
	        <FormItem prop="name">
	            <Input v-model="customer.customer_name" placeholder="姓名" size="large">
	            	<Icon type="ios-body" slot="prepend"></Icon>
	            </Input>
	        </FormItem>
	        <FormItem label="性别  ">
	            <RadioGroup v-model="customer.customer_sex">
	                <Radio label="0">男</Radio>
	                <Radio label="1">女</Radio>
	            </RadioGroup>
	        </FormItem>
	        <FormItem prop="customer_number">
	            <Input type="text" v-model="customer.customer_number" placeholder="手机号" size="large">
	                <Icon type="ios-person-outline" slot="prepend"></Icon>
	            </Input>
	        </FormItem>
	        <FormItem prop="customer_password">
	            <Input type="password" v-model="customer.customer_password" placeholder="密码" size="large">
	                <Icon type="ios-locked-outline" slot="prepend"></Icon>
	            </Input>
	        </FormItem>
	        <FormItem prop="recustomer_password">
	            <Input type="password" v-model="customer.recustomer_password" placeholder="确认密码" size="large">
	                <Icon type="ios-locked-outline" slot="prepend"></Icon>
	            </Input>
	        </FormItem>
	        <FormItem style="display: flex; flex-flow: row nowrap; justify-content: space-around">
                <Button type="primary" @click="goBack()" size="large">返回</Button>
	            <Button type="success" @click="handleSubmit('customer')" size="large">点击提交</Button>
	        </FormItem>
	    </Form>
	</div>
</template>