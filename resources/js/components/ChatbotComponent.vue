<template>
    <div class="chat">
        <section ref="chatArea" class="messages">
            <span v-for="(msg, index) in messages" :key="index">
                <div class="message new" v-if="msg.fromBot == true" ref="msg">
                    <figure class="avatar">
                        <img
                            src="https://guides.ryanshirley.ie/favicon/favicon-228.png"
                        />
                    </figure>
                    {{ msg.message }}
                </div>
                <div
                    class="message message-personal"
                    v-if="msg.fromBot == false"
                >
                    {{ msg.message }}
                </div>
            </span>

            <div v-if="bot_writing" class="message loading new">
                <figure class="avatar">
                    <img
                        src="https://guides.ryanshirley.ie/favicon/favicon-228.png"
                    />
                </figure>
                <span></span>
            </div>
        </section>
        <div class="message-box">
            <p v-if="this.stage === 3" class="text-center">
                <button
                    type="button"
                    class="btn btn-primary rounded-pill"
                    v-on:click="setMoney(true)"
                >
                    Guides
                </button>
                <button
                    type="button"
                    class="btn btn-primary rounded-pill"
                    v-on:click="setMoney(false)"
                >
                    Personal
                </button>
            </p>

            <p v-if="this.stage === 4" class="text-center">
                <button
                    type="button"
                    class="btn btn-primary rounded-pill"
                    v-on:click="addAnotherPayment(true)"
                >
                    Yes
                </button>
                <button
                    type="button"
                    class="btn btn-primary rounded-pill"
                    v-on:click="addAnotherPayment(false)"
                >
                    No
                </button>
            </p>

            <div class="input-group">
                <input
                    :type="
                        stage === 2 ? 'date' : stage === 1 ? 'number' : 'text'
                    "
                    class="form-control"
                    v-model="input"
                    @keyup.enter="messageSubmit"
                    :disabled="diableWriting == true"
                />
                <div class="input-group-append">
                    <button
                        class="btn btn-primary"
                        type="button"
                        v-on:click="messageSubmit"
                        :disabled="diableWriting == true"
                    >
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        console.log("Chatbot mounted.");

        this.initBot();
    },
    methods: {
        initBot() {
            let app = this;

            this.botMessage(
                `Hello ${this.$props.user_name}, I am the Guides bot.`
            );
            setTimeout(() => {
                app.botMessage(
                    "I am here to ask you what purchase you made for guides. Then I will notify Emily about it."
                );
            }, 1500);
            setTimeout(() => {
                app.botMessage("Let's get started");
            }, 4000);
            setTimeout(() => {
                app.botMessage("What did you purchase?");
            }, 5500);
        },
        messageSubmit() {
            let app = this;
            let input = this.input;

            // Ensure value has been passed
            if (input === "") {
                this.botMessage("You must provide a value");

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

                    this.botMessage(`How much did the ${input} cost?`);
                    break;
                case 1: // Amount
                    // TODO: Add any validation here
                    this.purchase.amount = input;

                    this.botMessage(`When did you purchase it?`);
                    break;
                case 2: // Date
                    // TODO: Add any validation here
                    this.purchase.purchase_date = input;
                    this.diableWriting = true;

                    this.botMessage(`Whos money did you use?`);
                    break;
            }

            // Next stage
            this.stage++;
        },
        botMessage(msg) {
            let app = this;
            app.bot_writing = true;
            app.scrollToBottom();

            setTimeout(() => {
                app.bot_writing = false;

                app.messages.push({
                    fromBot: true,
                    message: msg
                });

                app.scrollToBottom();
            }, 1000 + Math.random() * 5);
        },
        userMessage(msg) {
            this.messages.push({
                fromBot: false,
                message: msg
            });

            this.scrollToBottom();
        },
        saveExpense() {
            let app = this;
            axios
                .post("/api/payment?api_token=" + app.$props.api_token, {
                    title: app.purchase.title,
                    amount: app.purchase.amount,
                    purchase_date: app.purchase.purchase_date,
                    guide_money:
                        app.purchase.guide_money === true ? true : false
                })
                .then(response => {
                    console.log(response.data);

                    this.botMessage(
                        "Thank you! Your payment has been sent to Emily for approval."
                    );

                    setTimeout(() => {
                        app.botMessage("Would you like to add another?");
                    }, 1000 + Math.random() * 5);
                })
                .catch(err => {
                    this.botMessage(
                        "Oops looks like there was an error adding your expense."
                    );
                    this.botMessage(err);
                });
        },
        scrollToBottom() {
            Vue.nextTick(() => {
                let messageDisplay = this.$refs.chatArea;
                messageDisplay.scrollTop = messageDisplay.scrollHeight;
            });
        },
        setMoney(isGuide_money) {
            this.purchase.guide_money = isGuide_money;
            this.userMessage(isGuide_money === true ? "Guides" : "Personal");

            this.stage++;

            this.saveExpense();
        },
        addAnotherPayment(contin) {
            if (contin) {
                this.stage = 0;
                this.diableWriting = false
                this.botMessage("What did you purchase?");
            } else {
                this.stage++;
                this.botMessage("Ok bye! :)");
            }
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
            },
            bot_writing: true,
            diableWriting: false
        };
    }
};
</script>
