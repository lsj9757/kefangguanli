<style lang="less" scoped>
      
    #main{
       display: flex;
       flex-flow: column nowrap;
       min-height: 568px;
       justify-content: center;
       align-items: center; 
    } 
</style>
<template>
    <div id="main">
        <v-user></v-user>
        <Table class="tb" border :columns="data.columns" :data="data.content" stripe align="center" ></Table>
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
            return {
                user: '',
                data: {
                    columns: [{
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
                        width: 160
                    },
                    {
                        title: '房间状态',
                        key: 'room_state',
                        align: 'center',
                        width: 160
                    },
                    {
                        title: '负责人',
                        key: 'admin_name',
                        align: 'center',
                        width: 250
                    },
                    {
                        title: 'Action',
                        key: 'action',
                        width: 250,                      
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small',
                                        disabled: params.row.room_state==="空"?false:true
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            if(this.buttonshow===1){
                                                let data = {
                                                    customer_id: this.customer_id,
                                                    room_id: params.row.room_id,
                                                    message: this.customer_name + "请求住入房间" + params.row.room_tablet
                                                };
                                                let type = 1;
                                                this.request(this.customer_id, type,data);
                                            }else{
                                                let data = {
                                                    customer_id: this.customer_id,
                                                    room_id: params.row.room_id,
                                                    message: this.customer_name + "请求换入房间" + params.row.room_tablet
                                                };
                                                let type = 2;
                                                this.request(this.customer_id, type,data);
                                            }
                                        }
                                    }
                                },this.buttonshow===1?'住房':'换房'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small',
                                        disabled: params.row.customer_id===this.customer_id?false:true
                                    },
                                    on: {
                                        click: () => {
                                            let data = {
                                                    customer_id: this.customer_id,
                                                    room_id: params.row.room_id,
                                                    message: this.customer_name + "请求退出房间" + params.row.room_tablet
                                                };
                                                let type = 3;
                                                this.request(this.customer_id, type,data);
                                        }
                                    }
                                }, '退房')
                            ]);
                        }
                    }],
                    content: [],
                },

                buttonshow:"",customer_name:"",
            }
        },
        methods: {
            request (id,type,message) {
                this.$axios.post('http://139.199.2.100/roomback/request.php',qs.stringify({
                      customer_id:id,
                      request_type:type,
                      request_message:message
                })).then((res) => {
                    if(res.data === 1){
                        let rd=type===1?'住房':type===2?'换房':'退房';
                        this.$Message.success('您已成功发出<span style="color:red;">'+rd+'</span>请求,请耐心等待管理员处理');
                    }else{
                        this.$Message.error('请求出错');
                    }                   
                }).catch((err) => {
                    console.log(err);
                })
            },
            re (id,type,message) {
                this.$axios.post('http://139.199.2.100/yanchenghao/return.php',qs.stringify({
                      customer_id:id,
                      request_type:type,
                      request_message:message
                })).then((res) => {
                    console.log(res.data);                  
                }).catch((err) => {
                    console.log(err);
                })
            }
        },
        mounted () {
            this.user = Cookies.get('user');
                this.$axios.post('http://139.199.2.100/roomback/selectcustomer.php',qs.stringify({
                      type: 1,
                      user: this.user
                })).then((res) => {
                    this.customer_name = res.data.customer_name;
                    this.customer_id = res.data.customer_id;
                    if(res.data.customer_state === "0"){
                        this.buttonshow=1,
                        this.$Message.info('您还未订房，请选择一个房间发起住房请求');
                    }else{
                        this.buttonshow=2
                    }
                }).catch((err) => {
                    console.log(err);
                }),// 查用户信息

                this.$axios.post('http://139.199.2.100/roomback/selectroom.php').then((res) => {
                    for(var i=0;i<res.data.length;i++){
                        this.data.content.push({
                          room_id:res.data[i].room_id,
                          room_classification:res.data[i].room_classification,
                          room_tablet:res.data[i].room_tablet,
                          admin_name:res.data[i].admin_name,
                          room_state:res.data[i].room_state==="0"?"空":"满",
                          customer_id:res.data[i].customer_id,
                        });
                    }
                }).catch((err) => {
                    console.log(err);
                })
            },// 查房间
    };
</script>