<template>
    <div>
        <div id="popup" class="popup_container">
            <div class="popup_wrapper">
                <div class="popup_menu">
                    <h1 class="popup_title">Все валюты</h1>
                    <a href="#" class="popup_close"></a>
                </div>
                <div class="popup_currency_container">
                    <div style="height: 35px;"></div>
                    <!---->
                     <div class="popup_currency" v-for="(data, id) in getAlldata">
                        <div class="popup_currency_title_container">
                            <h1 class="popup_currency_title">{{ data.name }}</h1>
                        </div>
                        <button class="popup_currency_add" @click="addCurrency(data.name, data.course, id)"></button>
                    </div>
                    <!---->
                </div>
            </div>
        </div>

        <div class="body">
            <div class="content">
                <div class="menu">
                    <h1 class="title">Курсы валют</h1>
                    <!---->
                    <div class="update_container">
                        <p class="update_good" v-if="okLoading">Обновлено</p>
                        <p class="update_error" v-if="errorLoading">Что-то не так</p>
                        <button class="update" @click="getDataServer()"></button>
                    </div>
                    <!---->
                </div>
                <div class="blocks">
                    <div style="height: 35px;"></div>
                    <div class="block_active">
                        <a class="block_add" href="#popup"></a>
                    </div>
                    <!---->
                    <div class="inner">
                        <div class="block" v-for="(data, id) in currencyUser">
                            <p class="block_title">{{ data.name }}</p>
                            <p class="block_course">{{ data.course }}</p>
                            <button class="block_delete" @click="deleteCurrency(data.name, data.course, id)"></button>
                        </div>
                    </div>
                    <!---->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import './assets/library/normalize.css';
import './assets/fonts/fonts.css';
import axios from 'axios'

export default {
    name: 'App',
    data() {
        return {
            info: [],
            currencyUser: [],
            getAlldata: [],
            okLoading: false,
            errorLoading: false
        }
    },
    mounted() {
        this.getDataServer();

        // Устанавливаем таймер для получения обновлённых данных от сервера
        setInterval(this.getDataServer, 3600000);
    },
    methods: {
        addCurrency(name, course, id) {
            var currencyUser = JSON.parse(localStorage.getItem('currencyUser'));
            currencyUser = currencyUser || [];
            currencyUser.push({
                name: name,
                course: course
            });
            localStorage.setItem('currencyUser', JSON.stringify(currencyUser));

             // Получаем только те, которые выбрал пользователь
            this.currencyUser = JSON.parse(localStorage.getItem('currencyUser'));

            this.getAlldata.splice(id, 1);
        },
        deleteCurrency(name, course, id){
            var getAlldata = JSON.parse(localStorage.getItem('savedAllData'));
            getAlldata = getAlldata || [];
            getAlldata.push({
                name: name,
                course: course
            });
            localStorage.setItem('savedAllData', JSON.stringify(getAlldata));

            this.currencyUser.splice(id, 1);
            localStorage.setItem('currencyUser', JSON.stringify(this.currencyUser));
        },
        getDataServer(){
            axios
            .get('http://168.119.152.129/test_task/test-task/back-end/index.php')
            .then(response => {
                this.info = response.data
                localStorage.setItem('savedAllData', JSON.stringify(this.info));

                // Получаем все данные
                this.getAlldata = JSON.parse(localStorage.getItem('savedAllData'));
            })
            .catch(error => {
                this.errorLoading = true;
                this.okLoading = false;
            });

            // Получаем только то что сохранил пользователь
            this.currencyUser = JSON.parse(localStorage.getItem('currencyUser'));

            // Показывем тект что данные успешно обновлены
            this.okLoading = true;

            // Удаляем из DOM аллерты по таймеру
            setTimeout(this.deleteAlert, 5000);
        },
        deleteAlert(){
            this.errorLoading = false;
            this.okLoading = false;
        }
    }
}
</script>

<style>
*{
    transition: all .2s;
}

body{
    font-family: 'Roboto';
}

a{
    text-decoration: none;
}

.popup_container{
    width: 100%;
    height: 100vh;
    position: absolute;
    background-color: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 2;
}
.popup_container:target{
    display: flex;
}

.popup_wrapper{
    width: 791px;
    background-color: #fff;
    border-radius: 19px;
}
.popup_menu{
    padding: 19px 35px 19px 35px;
    background-color: #F6F1ED;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 19px 19px 0 0;
    border: solid #e4e4e4;
    border-top-width: 0;
    border-right-width: 0;
    border-bottom-width: 1px;
    border-left-width: 0;
}
.popup_title{
    color: #3C3C3C;
    font-size: 24px;
    font-weight: 900;
    margin: 0;
}
.popup_close{
    width: 35px;
    height: 35px;
    background: #F6F1ED url('./assets/img/clear-black-48dp.svg') no-repeat;
    background-position: center center;
    background-size: 70%;
    display: block;
}
.popup_close:hover{
    background-color: #e8d6c4;
    border-radius: 100%;
}

.body{
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.content{
    width: 775px;
    border-radius: 19px;
    box-shadow: 0px 0px 19px rgba(38, 35, 35, 0.25);
}

.menu{
    padding: 0 35px 0 35px;
    background-color: #F6F1ED;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 19px 19px 0 0;
    border: solid #e4e4e4;
    border-top-width: 0;
    border-right-width: 0;
    border-bottom-width: 1px;
    border-left-width: 0;
}
.title{
    color: #3C3C3C;
    font-size: 24px;
    margin: 0;
    font-weight: 900;
}

.blocks{
    width: 100%;
    max-height: 524px;
    overflow: auto;
}
.inner{
    position: relative; 
    height: auto;
    z-index: 1;
}
.block, .block_active{
    width: 108px;
    height: 86px;
    padding: 21px;
    background-color: #F6F1ED;
    border-radius: 14px;
    margin: 0 0 35px 35px;
    float: left;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.block_delete{ 
    width: 108px;
    height: 86px;
    position: absolute;
    display: none;
    background: #F6F1ED url('./assets/img/delete-black-48dp.svg') no-repeat;
    background-position: center center;
    background-size: 60%;
    cursor: pointer;
    outline: none;
    border: 0;
}
.block:hover .block_delete{
    display: block;
}
.block_title{
    color: #3C3C3C;
    font-size: 18px;
    margin: 0;
    font-weight: 500;
}
.block_course{
    color: #3C3C3C;
    font-size: 22px;
    margin: 0;
    font-weight: 900;
}
/*.block_active{
    width: 108px;
    height: 86px;
    padding: 21px;
    background-color: #F6F1ED;
    border-radius: 14px;
    margin: 0 0 35px 35px;
    float: left;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}*/
.block_active:hover{
    box-shadow: 0px 0px 6px rgba(255, 1, 1, 0.25);
}
.block_add{
    width: 108px;
    height: 86px;
    background: url('./assets/img/add_circle_outline-black-48dp.svg') no-repeat;
    background-position: center center;
    background-size: 70%;
}
.popup_currency_container{
    height: 335px;
    overflow: auto;
}
.popup_currency{
    width: 175px;
    padding: 15px 21px 15px 21px;
    background-color: #F6F1ED;
    margin: 0 0 35px 35px;
    border-radius: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    float: left;
}
.popup_currency:hover{
    box-shadow: 0px 0px 6px rgba(255, 1, 1, 0.25);
}
.popup_currency_title_container{
    width: 140px;
}
.popup_currency_title{
    color: #3C3C3C;
    font-size: 18px;
    margin: 0;
    font-weight: 500;
}
.popup_currency_add{
    width: 35px;
    height: 35px;
    background: #7efb94 url('./assets/img/add-black-48dp.svg') no-repeat;
    background-position: center center;
    background-size: 65%;
    border: 0;
    cursor: pointer;
    outline: none;
    border-radius: 100%;
}
.popup_currency_add:hover{
    background-color: #71f188;
}
.update_container{
    display: flex;
    align-items: center;
}
.update_good, .update_error{
    color: #2BCE28;
    font-size: 14px;
    font-weight: 500;
    margin: 0 22px 0 0;
}
.update_error{
    color: #ff0000;
}
.update{
    width: 35px;
    height: 35px;
    background: #F6F1ED url('./assets/img/update.svg') no-repeat;
    background-position: center center;
    background-size: 70%;
    border-radius: 7px;
    box-shadow: 0px 0px 6px #56565640;
    border: 0;
    margin: 19px 0 19px 0;
    cursor: pointer;
    outline: none;
}
.update:hover{
    box-shadow: 0px 0px 6px rgba(255, 1, 1, 0.25);
}
</style>
