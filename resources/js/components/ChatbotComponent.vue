<template>
    <div class="chat">
        <div class="chat-title">
            <h1>Guides Chatbot</h1>
            <h2>Stillorgan</h2>
            <figure class="avatar">
                <img
                    src="https://guides.ryanshirley.ie/favicon/favicon-228.png"
                />
            </figure>
        </div>
        <div class="messages">
            <div class="messages-content"></div>
            <pre>{{ message_history }}</pre>
        </div>
        <div class="message-box">
            <input 
                type="text"
                class="message-input"
                placeholder="Type message..."
                v-model="input"
                @keyup.enter="messageSubmit"
            />
            <button type="submit" class="message-submit" v-on:click="messageSubmit">Send</button>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        console.log("Component mounted.");
        this.message_history.push(this.bot_messages[0])
    },
    methods: {
        messageSubmit() {
            let app = this
            let input = this.input

            if(input === '') {
                this.message_history.push('You must provide a value')
                return
            }

            // Save values
            switch(this.stage) {
                case 0: // Title
                    this.purchase.title = input

                    break;
                case 1: // Amount
                    this.purchase.amount = input

                    break;
                case 2: // Purchase Date
                this.purchase.purchase_date = input

                    break;
                case 3: // Whoes money
                    this.purchase.guide_money = input

                    // At this point sent request
                    axios.post('/api/payment?api_token=' + app.$props.api_token, {
                        title: app.purchase.title,
                        amount: app.purchase.amount,
                        purchase_date: app.purchase.purchase_date,
                        guide_money: app.purchase.guide_money === 'guide' ? true : false
                    })
                        .then(response => {
                        console.log(response.data);
                        
                    });

                    break;
                case 4: // Add another or not
                    if(input == 'yes') {
                        this.stage = 0;
                        this.message_history.push(this.input)
                        this.input = ''
                        this.botMessage()

                        return
                    }

                    break;
            }


            // Add message to history, reset and move onto next stage
            this.message_history.push(this.input)
            this.input = ''
            this.stage++
            this.botMessage()
        },
        botMessage() {
            let app = this
            setTimeout(() => {
                app.message_history.push(this.bot_messages[this.stage])
            }, 1000 + (Math.random() * 5));
        }
    },
    props: ['api_token'],
    data() {
        return {
            bot_messages: [
                "Hello Emily, what did you purchase?",
                "How much did that cost?",
                "When did you purchase this?",
                "Whos money did you use?",
                "Thank you! That has been sent to an admin for approval. Would you like to add another?",
                "Ok bye!"
            ],
            stage: 0,
            message_history: [],
            input: '',
            purchase: {
                title: '',
                amount: '',
                purchase_date: '',
                guide_money: ''
            }
        };
    }
};
</script>

<style scoped>



</style>
