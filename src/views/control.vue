<style lang="less" scoped>   

    #main{
        display: flex;
        min-width: 1024px;
        min-height: 768px;
        padding-top: 68px;
        padding-bottom: 128px;
        align-items:center; 
        flex-direction:column;
        justify-content:center;
        .room{
            flex: 1 1 auto;
            margin-top:40px;
            position: relative;
            .addr{
                position: absolute;
                right: 0px;
                top: -37px;
                box-shadow: 3px 2px 5px lightgray;
            }
        }
        .customer{
            flex: 1 1 auto;
        }
        .tb{
            box-shadow: 5px 5px 25px lightgray;
        }
        .log{
        	 flex: 1 1 auto;
        	 margin-top:40px;
        }
    } 
</style>
<template>  

    <div id="main" >
        <v-user></v-user>
		<div class="customer">
			<Table width="876" class="tb" border :columns="customer.columns" :data="customer.content" stripe align="center" ></Table>
			<Modal v-model="showrequest" title="客户请求" @on-ok="requestOK()">
				{{message.message}}
			</Modal>
			<Modal v-model="showcustomer" title="查看信息" >
                房间号：{{customer_tablet}}
            </Modal>
		</div>
        <div class="room">
            <Table width="876" class="tb" border :columns="room.columns" :data="room.content" stripe align="center" ></Table>
            <Button class="addr" type="info" @click="showaddroom = true">增加客房</Button>
            <Modal v-model="showaddroom" title="添加客房" @on-ok="SubmitRoom(1,'addroom')" >
                <Form ref="addroom" :model="addroom" :rules="ruleaddroom">
                    <FormItem prop="room_tablet">
                        <Input v-model="addroom.room_tablet" placeholder="门牌号" size="large">
                            <Icon type="ios-body" slot="prepend"></Icon>
                        </Input>
                    </FormItem>
                    <FormItem prop="room_classification" label="客房种类">
                        <RadioGroup v-model="addroom.room_classification">
                            <Radio label="双人间/288元">双人间/288元</Radio>
                            <Radio label="单人间/198元">单人间/198元</Radio>
                            <Radio label="大床房/238元">大床房/238元</Radio>
                            <Radio label="总统套房/398元">总统套房/398元</Radio>
                        </RadioGroup>
                    </FormItem>
                </Form>
            </Modal>
            <Modal v-model="showeditroom" title="修改客房" @on-ok="SubmitRoom(2,'editroom')" >
                <Form ref="editroom" :model="editroom" :rules="ruleeditroom">
                    <FormItem prop="room_tablet">
                        <Input v-model="editroom.room_tablet" placeholder="门牌号" size="large">
                            <Icon type="ios-body" slot="prepend"></Icon>
                        </Input>
                    </FormItem>
                    <FormItem prop="room_classification" label="客房种类">
                        <RadioGroup v-model="editroom.room_classification">
                            <Radio label="双人间/288元">双人间/228元</Radio>
                            <Radio label="单人间/198元">单人间/198元</Radio>
                            <Radio label="大床房/238元">大床房/238元</Radio>
                            <Radio label="总统套房/398元">总统套房/398元</Radio>
                        </RadioGroup>
                    </FormItem>
                </Form>
            </Modal>
            
        </div>
        <div class="log">
			<Table width="876" class="tb" border :columns="log.columns" :data="log.content" stripe align="center" ></Table>
		</div>     

    </div>

</template>

<script>
    import Cookies from 'js-cookie';
	import qs from 'qs';

    import user from './user.vue';

    export default {
        components: {
            'v-user': user
        },
        data() {
        	var BeNumber = (rule, value, callback) => {
        		if(!value){
        			callback("不能为空");
        		}else{
        			var reg = new RegExp(/^\d+$|^\d+\.\d+$/g);
        			if (!reg.test(value)) {
        				callback(new Error('必须为数字'));
        			} else {
        				callback();
        			}
        		}
        	};
            return {
                user: '',
                room: {
                    columns: [
                    {
                        title: 'id',
                        key: 'room_id',
                        align: 'center',
                        width: 70,
                    },
                    {
                        title: '客房种类',
                        key: 'room_classification',
                        align: 'center',
                        width: 160,
                    },
                    {
                        title: '门牌号',
                        key: 'room_tablet',
                        align: 'center',
                        width: 140
                    },
                    {
                        title: '房间状态',
                        key: 'room_state',
                        align: 'center',
                        width: 140
                    },
                    {
                        title: '负责人',
                        key: 'admin_name',
                        align: 'center',
                        width: 140
                    },
                    {
                        title: 'Action',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                           this.showeditroom = true,
                                           this.editroom.room_tablet =  params.row.room_tablet,
                                           this.editroom.room_classification =  params.row.room_classification,
                                           this.eroom_id = params.row.room_id
                                        }
                                    }
                                }, '修改'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small',
                                        disabled: params.row.room_state==="满"?true:false
                                    },
                                    on: {
                                        click: () => {
                                            this.DeleteRoom(params.row.room_id);
                                            this.room.content.map((x, i)=>{
                                                if(x.room_id === params.row.room_id){
                                                    this.room.content.splice(i, 1);
                                                }
                                            })
                                        }
                                    }
                                }, '删除')
                            ]);
                        }
                    }],
                    content: [],
                },

                customer: {
                    columns: [
                    {
                        title: 'id',
                        key: 'customer_id',
                        align: 'center',
                        width: 70,
                    },
                    {
                        title: '客户姓名',
                        key: 'customer_name',
                        align: 'center',
                        width: 90,
                    },
                    {
                        title: '客户号码',
                        key: 'customer_number',
                        align: 'center',
                        width: 120
                    },
                    {
                        title: '客户性别',
                        key: 'customer_sex',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '客户状态',
                        key: 'customer_state',
                        align: 'center',
                        width: 100
                    },
                    {
                    	title: '入住时间',
                        key: 'customer_time',
                        align: 'center',
                        width: 150
                    },
                    {
                        title: 'Action',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small',
                                        disabled: params.row.customer_state==="空闲"?true:false
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.showcustomer = true,
                                            this.customer_id = params.row.customer_id;
                                            this.selectcustomer(this.customer_id);
                                        }

                                    }
                                }, params.row.customer_state==="空闲"?'暂未入住':'查看住房')
                            ]);
                        }
                    },
                    {
                        title: 'Action',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small',
                                        disabled: params.row.request_message===""?true:false
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.showrequest = true,
                                            this.message = JSON.parse(params.row.request_message);
                                            this.message.type = params.row.request_type;
                                        }

                                    }
                                }, '处理请求')
                            ]);
                        }
                    }],
                    content: [],   
                }, 

                log: {
                    columns: [
                    {
                        title: 'id',
                        key: 'log_id',
                        align: 'center',
                        width: 70,
                    },
                    {
                        title: '客户姓名',
                        key: 'customer_name',
                        align: 'center',
                        width: 90,
                    },
                    {
                        title: '客户号码',
                        key: 'customer_number',
                        align: 'center',
                        width: 120
                    },
                    {
                        title: '日志事件',
                        key: 'log_type',
                        align: 'center',
                        width: 120
                    },
                    {
                        title: '门牌号',
                        key: 'room_tablet',
                        align: 'center',
                        width: 100
                    },
                    {
                        title: '客房种类',
                        key: 'room_classification',
                        align: 'center',
                        width: 110
                    },
                    {
                    	title: '发生时间',
                        key: 'log_time',
                        align: 'center',
                        width: 150
                    },
                    {
                    	title: '负责人',
                        key: 'log_admin',
                        align: 'center',
                        width: 110
                    }
                    ],
                    content: [],   
                }, 

                showaddroom: false,showeditroom: false,showrequest: false,showcustomer:false,eroom_id:"",message:"",customer_id:"",customer_tablet:"",

                addroom: {
                    room_tablet	: '',
                    room_classification: '',
                },

                ruleaddroom: {
                    room_classification: [
                        { required: true, message: 'Please fill in the room classification' }
                    ],
                   room_tablet: [{ validator: BeNumber}],
                },

                editroom:{
                	room_tablet	: '',
                    room_classification: '',
                },

                ruleeditroom: {
                    room_classification: [
                        { required: true, message: 'Please fill in the room classification' }
                    ],
                   room_tablet: [{ validator: BeNumber}],
                },
            }
        },
        methods: {
            requestOK(){
                this.$axios.post('http://139.199.2.100/roomback/requestProcess.php', qs.stringify({
                    room_id: this.message.room_id,
                    request_type: this.message.type,
                    customer_id: this.message.customer_id,
                    admin_name:this.user
                })).then((res) => {
                    if(res.data === 1){
                        this.$Message.success('处理请求成功！！');
                        this.doSomeAjax();
                    }
                    else{
                        this.$Message.error('失败，请重试...');
                    }
                });
            },
            SubmitRoom (type,formName) {
            	 this.$refs[formName].validate((valid) => {
            	 	if (valid) {
            	 		this.$axios.post('http://139.199.2.100/roomback/operaroom.php', qs.stringify({
            	 			room_id:this.eroom_id,
            	 			type:type,
            	 			addroom:this.addroom,
            	 			editroom:this.editroom,
                            admin_name:this.user
            	 		})).then((res) => {
            	 			if(res.data === 1 ){
            	 				this.$Message.success('添加Success!');
                                let next_id = Math.max(0, ...this.room.content.map(x=>x.room_id)) + 1;
                                this.room.content.push({
                                    room_id:next_id,
                                    room_classification:this.addroom.room_classification,
                                    room_tablet:this.addroom.room_tablet,
                                    admin_name:this.user,
                                    room_state:'空',
                                    customer_id:'',
                                });
            	 			}
            	 			else if(res.data === 0 ){
            	 				this.$Message.error('该客房已被添加');
            	 			}
            	 			else if(res.data === 2){
            	 				this.$Message.success('修改Success!');
            	 			}else{
            	 				this.$Message.error('操作错误');
            	 			}
            	 		}).catch((err) => {
            	 			console.log(err);})	
            	 	}else{
            	 		this.$Message.error('未按格式填写');
            	 	}
            	 });
            },
            DeleteRoom (id) {
            	this.$axios.post('http://139.199.2.100/roomback/operaroom.php', qs.stringify({
            	 			room_id:id,
            	 			type:3,
            	 		})).then((res) => {
            				if(res.data === 3){
            	 				this.$Message.success('删除Success!');
            	 			}else{
            	 				this.$Message.error('操作错误');
            	 			}
                    
                }).catch((err) => {
                    console.log(err);
                })
            },
            doSomeAjax() {
            	this.room.content = [];
            	this.customer.content = [];
            	this.log.content = [];
            	this.$axios.post('http://139.199.2.100/roomback/selectroom.php').then((res) => {
                    for(var i=0;i<res.data.length;i++){
                        this.room.content.push({
                          room_id:res.data[i].room_id,
                          room_classification:res.data[i].room_classification,
                          room_tablet:res.data[i].room_tablet,
                          admin_name:res.data[i].admin_name,
                          room_state:res.data[i].room_state=="0"?"空":"满",
                          customer_id:res.data[i].customer_id,
                        });
                        this.room.content.sort((a, b)=>{
                            return a.room_id > b.room_id;
                        })
                    }
                }).catch((err) => {
                    console.log(err);
                }),

                this.$axios.post('http://139.199.2.100/roomback/selectcustomer.php',qs.stringify({
                	  type:2,
                      user: this.user
                })).then((res) => {              	
                    for(var i=0;i<res.data.length;i++){
                        this.customer.content.push({
                          customer_id:res.data[i].customer_id,
                          customer_sex:res.data[i].customer_sex==0?"男":"女",
                          customer_number:res.data[i].customer_number,
                          customer_name:res.data[i].customer_name,
                          customer_state:res.data[i].customer_state=="0"?"空闲":"已住",
                          customer_time:res.data[i].customer_time==null?"暂未入住":res.data[i].customer_time,
                          request_message:res.data[i].request_message,
                          request_type:res.data[i].request_type,
                        });
                    }
                }).catch((err) => {
                    console.log(err);
                }),

                this.$axios.post('http://139.199.2.100/roomback/selectlog.php').then((res) => {
                    for(var i=0;i<res.data.length;i++){
                        this.log.content.push({
                          log_id:res.data[i].log_id,
                          room_classification:res.data[i].room_classification,
                          room_tablet:res.data[i].room_tablet,
                          log_admin:res.data[i].log_admin,
                          log_type:res.data[i].log_type==1?"入住":res.data[i].log_type==2?"换房":"退房",
                          log_time:res.data[i].log_time,
                          customer_number:res.data[i].customer_number,
                          customer_name:res.data[i].customer_name,
                        });
                        this.log.content.sort((a, b)=>{
                            return a.room_id > b.room_id;
                        })
                    }
                }).catch((err) => {
                    console.log(err);
                })
            },
            selectcustomer (id) {
            	this.$axios.post('http://139.199.2.100/roomback/selectcustomer.php',qs.stringify({
                	  type:3,
                      customer_id: id
                })).then((res) => {
                	this.customer_tablet = res.data.room_tablet;
                }).catch((err) => {
                    console.log(err);
                })
            }
        },
        mounted () {
            this.user = Cookies.get('user');
        	this.doSomeAjax();
        }
    };
</script>
