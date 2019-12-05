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
            <div class="messages-content" v-if="messages.length > 0">
                <div v-for="(msg, index) in messages" :key="index">
                    <span v-if="msg.fromBot" class="badge badge-pill badge-secondary float-left">{{ msg.message }}</span>
                    <span v-if="msg.fromBot" class="badge badge-pill badge-primary float-right">{{ msg.message }}</span>
                </div>
            </div>
        </div>
        <div class="message-box">
            <input
                type="text"
                class="message-input"
                placeholder="Type message..."
                v-model="input"
                @keyup.enter="messageSubmit"
            />
            <button
                type="submit"
                class="message-submit"
                v-on:click="messageSubmit"
            >
                Send
            </button>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        console.log("Chatbot mounted.");
        
        this.initBot()
    },
    methods: {
        initBot() {
            this.botMessage(`Hello ${this.$props.user_name}, I am the Guides bot.`)
            this.botMessage('I am here to ask you what purchase you made for guides. Then I will notify Emily about it.')
            this.botMessage('Lets get started.')
            this.botMessage('What did you purchase?')
        },
        messageSubmit() {
            let app = this;
            let input = this.input;

            // Ensure value has been passed
            if (input === "") {
                this.userMessage("You must provide a value");

                return;
            }

            // Add user message to screen and reset inpiut
            this.userMessage(input);
            this.input = "";

            // Switch for each stage of adding expense
            switch (this.stage) {
                case 0: // Title
                    // TODO: Add any validation here
                    this.purchase.title = input;

                    this.botMessage(`How much did the ${input} cost?`)
                    break;
                case 1: // Amount
                    // TODO: Add any validation here
                    this.purchase.amount = input;

                    this.botMessage(`When did you purchase it?`)
                    break;
                case 2: // Purchase Date
                    // TODO: Add any validation here
                    this.purchase.purchase_date = input;

                    this.botMessage('Whos money did you use?')
                    break;
                case 3: // Whoes money
                    // TODO: Add any validation here
                    this.purchase.guide_money = input;

                    // Save expense in the database
                    this.saveExpense()

                    break;
                case 4: // Add another purchase or not
                    // TODO: Add any validation here

                    if (input == "yes") {
                        this.stage = 0;
                        this.botMessage('What did you purchase?');

                        return;
                    }
                    else {
                        this.botMessage('Ok bye! :)')

                        return
                    }

                    break;
            }

            // Next stage
            this.stage++;
        },
        botMessage(msg) {
            let app = this;
            setTimeout(() => {
                app.messages.push({
                    fromBot: true,
                    message: msg
                });
            }, 1000 + Math.random() * 5);
        },
        userMessage(msg) {
            this.messages.push({
                fromBot: false,
                message: msg
            });
        },
        saveExpense() {
            let app = this
            axios
                .post(
                    "/api/payment?api_token=" + app.$props.api_token,
                    {
                        title: app.purchase.title,
                        amount: app.purchase.amount,
                        purchase_date: app.purchase.purchase_date,
                        guide_money:
                            app.purchase.guide_money === true
                                ? true
                                : false
                    }
                )
                .then(response => {
                    console.log(response.data);

                    this.botMessage('Thank you! Your payment has been sent to Emily for approval.')
                    this.botMessage('Would you like to add another?')
                }).catch(err => {
                    this.botMessage('Oops looks like there was an error adding your expense.')
                    this.botMessage(err)
                });
        }
    },
    props: ["api_token", "user_name"],
    data() {
        return {
            stage: 0,
            messages: [],
            input: "",
            purchase: {
                title: "",
                amount: "",
                purchase_date: "",
                guide_money: ""
            }
        };
    }
};
</script>